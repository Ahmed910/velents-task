<?php

namespace App\Filters\Order;

use App\Filters\BaseFilters;
use App\Filters\FiltersContract;

class OrderFilters extends BaseFilters implements FiltersContract
{
    protected array $filters = [
        'status',
        'date',
    ];

    protected array $filterClasses = [
        OrderStatusFilter::class,
        OrderDateFilter::class,
    ];
}
