<?php

namespace App\DesignPatterns\Strategy\Payment;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;

abstract class PaymentGateway
{
    protected string $apiToCall;
    protected string $token;
    protected string $accept;
    protected string $baseUrl;
    protected array $params = [];

    public function __construct(string $apiToCall, string $token, string $accept, string $baseUrl, array $params = [])
    {
        $this->apiToCall = $apiToCall;
        $this->token = $token;
        $this->accept = $accept;
        $this->baseUrl = $baseUrl;
        $this->params = $params;
    }

    abstract public function placeOrder(Order $order, Transaction $transaction);

    private function buildHeaders()
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
           'Authorization' => "Bearer $this->token"
        ];
    }

    public function buildPostRequest()
    {
        return Http::withHeaders($this->buildHeaders())->post($this->baseUrl . $this->apiToCall, $this->params);
    }

    public function buildGetRequest()
    {
        return Http::withHeaders($this->buildHeaders())->get($this->baseUrl . $this->apiToCall, $this->params);
    }
}