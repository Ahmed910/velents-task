<?php

namespace App\DesignPatterns\Strategy\Payment;

interface RequestDataInterface
{
    public function getRequestData(): array;
}