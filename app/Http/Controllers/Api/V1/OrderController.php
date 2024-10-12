<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\Order\OrderFilters;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ServiceResponse;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\TransactionResource;
use App\Models\Order;
use App\Models\Transaction;
use App\Services\Order\OrderInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\Stripe;

class OrderController extends Controller
{
    public function __construct(private readonly OrderInterface $orderInterface) {}

    public function index(OrderFilters $filters)
    {
        $dataPaginated = $this->orderInterface->index($filters);

        // Transform the data
        $dataTransformed = $dataPaginated->map(function ($record) {
            return new OrderResource($record);
        });

        // Paginate the transformed data
        $dataPaginatedAndTransformed = new \Illuminate\Pagination\LengthAwarePaginator(
            json_decode(json_encode($dataTransformed)),
            $dataPaginated->total(),
            $dataPaginated->perPage(),
            $dataPaginated->currentPage(),
            [
                'path' => \Request::url(),
                'query' => [
                    'page' => $dataPaginated->currentPage()
                ]
            ]
        );

        // Return  the response
        return $dataPaginatedAndTransformed;
    }

    public function store(CreateOrderRequest $request)
    {
        $order = $this->orderInterface->store($request->validated());

        $order = json_decode(json_encode(new OrderResource($order)));


        // Return the response
        $resp = new ServiceResponse('order created successfully', true, $order);

        return response()->json($resp->getRepr(), JsonResponse::HTTP_CREATED);
    }

    public function update(int $id)
    {
        $order = $this->orderInterface->update($id);

        $order = json_decode(json_encode(new OrderResource($order)));

        // Return the response
        $resp = new ServiceResponse('order updated successfully', true, $order);

        return response()->json($resp->getRepr(), JsonResponse::HTTP_OK);
    }

    public function placeOrder(int $id)
    {
        $response = $this->orderInterface->placeOrder($id);

        try {
            if ($response instanceof Transaction) {
                $transaction = json_decode(json_encode(new TransactionResource($response)));

                // Return the response
                $resp = new ServiceResponse('order placed successfully', true, $transaction);

                return response()->json($resp->getRepr(), JsonResponse::HTTP_OK);
            } else {
                // Return the response
                $resp = new ServiceResponse('Failed to place order: Invalid response.', false, null);

                return response()->json($resp->getRepr(), JsonResponse::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            $resp = new ServiceResponse('An error occurred while placing the order.', false, null);

            return response()->json($resp->getRepr(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function callback(Request $request)
    {
        // Prepare the success message
        $message = 'Payment Transaction is processed successfully';

        // Return the success view with the success message
        return view('success')->with('message', $message);
    }

    public function error(Request $request)
    {
        return view('error')->with('message', 'Payment Transaction is not processed successfully');
    }

    // Here Using WebHook For Stripe Integration
    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET'); // Set this in your .env

        try {
            $this->orderInterface->handleWebhook($payload, $sigHeader, $endpointSecret);

            $resp = new ServiceResponse('order paid successfully', true, null);

            return response()->json($resp->getRepr(), JsonResponse::HTTP_OK);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            $resp = new ServiceResponse('Invalid payload', false, null);
            return response()->json($resp->getRepr(), JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            $resp = new ServiceResponse('Invalid signature', false, null);
            return response()->json($resp->getRepr(), JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
