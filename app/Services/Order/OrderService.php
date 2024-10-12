<?php

namespace App\Services\Order;

use App\DesignPatterns\Strategy\Payment\Context;
use App\DesignPatterns\Strategy\Payment\InvoiceRequest;
use App\DesignPatterns\Strategy\Payment\MyFatoorahGateway;
use App\Enums\NotificationOption;
use App\Enums\OrderStatus;
use App\Enums\Pagination;
use App\Filters\Order\OrderFilters;
use App\Models\Order;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;
use Stripe\PaymentIntent;

class OrderService implements OrderInterface
{
    public function index(OrderFilters $filters)
    {
        return Order::filters($filters)->paginate(Pagination::PAGINATION_COUNT->value);
    }

    public function store(array $data): Order
    {
        /** @var Order $order */
        $order = Order::create($data);
        $order->refresh();

        return $order;
    }

    public function update(int $id)
    {
        /** @var Order $order */
        $order = Order::where(['status' => OrderStatus::PENDING->value, 'id' => $id])->firstOrFail();

        $order->update(['status' => OrderStatus::PAID->value]);

        return $order;
    }

    public function placeOrder(int $id)
    {
        DB::beginTransaction();

        try {

            /** @var Order $order */
            $order = Order::where(['status' => OrderStatus::PENDING->value, 'id' => $id])->firstOrFail();
            $user = auth()->user();

            //  MyFatoorah Integration
            /** @var Transaction $transaction */
            $transaction = $order->transactions()->create([
                'amount' => $order->total_amount,
                'order_quantity' => $order->quantity,
                'order_price_per_unit' => $order->price,
                'customer_name' => $user->name,
                'notification_option' => NotificationOption::LNK->value,
                'created_by' => $user->id
            ]);

            $requestData = (new InvoiceRequest($order->total_amount, $user->name, NotificationOption::LNK->value, route('order.callback'), route('order.error')))->getRequestData();

            $transaction = (new Context(new MyFatoorahGateway(
                'v2/SendPayment',
                env('FATOORAH_API_KEY'),
                'application/json',
                env('FATOORAH_BASE_URL'),
                $requestData
            )))->placeOrder($order, $transaction);

            // Stripe Integration


            // Set your Stripe API key.
            // \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            // PaymentIntent::create([
            //     'amount' => $order->total_amount,
            //     'currency' => 'usd',
            //     'payment_method_types' => ['card'],
            //     'metadata' => [
            //         'order_id' => $order->id, // Include the order ID
            //     ],
            // ]);


            // $transaction = $order->transactions()->create([
            //     'amount' => $order->total_amount,
                // 'order_quantity' => $order->quantity,
                // 'order_price_per_unit' => $order->price,
            //     'customer_name' => $user->name,
            //     'notification_option' => NotificationOption::LNK->value,
            //     'created_by' => $user->id
            // ]);

            DB::commit();
            // all good
            return $transaction;
        } catch (\Exception $e) {
            DB::rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function handleWebhook($payload, $sigHeader, $endpointSecret)
    {
        $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);

        switch ($event->type) {
            case 'payment_intent.created':
                $paymentIntent = $event->data->object; // Contains a \Stripe\PaymentIntent
                // Retrieve the order ID from metadata
                $orderId = $paymentIntent->metadata->order_id;

                // Find the order and update its status
                $order = Order::find($orderId);
                if ($order) {
                    $order->status = 1; // Update status to paid
                    $order->save();
                }
                // Handle successful payment here (e.g., update order status)
                break;

            case 'payment_intent.failed':
                $paymentIntent = $event->data->object; // Contains a \Stripe\PaymentIntent
                // Handle payment failure here (e.g., notify user)
                break;

                // Handle other event types as needed
        }
    }
}
