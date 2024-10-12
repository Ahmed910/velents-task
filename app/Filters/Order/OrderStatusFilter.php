<?php

namespace App\Filters\Order;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;

class OrderStatusFilter
{
    public function __invoke(Builder $query, array $filters): Builder
    {
        $status = OrderStatus::value($filters['status'] ?? null);

        return $query
            ->when(
                ! is_null($status),
                fn (Builder $q) => $q->where(
                    (new Order())->getTable().'.status',
                    $status
                )
            );
    }
}
