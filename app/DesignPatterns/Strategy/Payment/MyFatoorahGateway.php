<?php

namespace App\DesignPatterns\Strategy\Payment;

use App\Models\Order;
use App\Models\Transaction;

class MyFatoorahGateway extends PaymentGateway
{
    public function placeOrder(Order $order, Transaction $transaction)
    {
        $response = $this->buildPostRequest();

        if($response->ok()) {
            $stdObject = json_decode($response->body());
            $stdObjectData = $stdObject->Data;
            $transaction->update(['invoice_id' => $stdObjectData->InvoiceId, 'invoice_url' => $stdObjectData->InvoiceURL, 'customer_reference' => $stdObjectData->CustomerReference, 'user_defined_field' => $stdObjectData->UserDefinedField]);

            return $transaction;
        }

        return $response;
    }
}