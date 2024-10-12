<?php

namespace App\DesignPatterns\Strategy\Payment;

use App\Models\Order;
use App\Models\Transaction;

class Context
{
    private PaymentGateway $gateway;

    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function placeOrder(Order $order, Transaction $transaction)
    {
       return $this->gateway->placeOrder($order, $transaction);
    }
}