<?php

namespace App\Services\Order;

use App\Filters\Order\OrderFilters;

interface OrderInterface
{
   public function index(OrderFilters $filters);
   public function store(array $data);
   public function update(int $id);
   public function placeOrder(int $id);
   public function handleWebhook($payload, $sigHeader, $endpointSecret);
}