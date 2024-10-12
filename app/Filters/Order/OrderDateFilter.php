<?php

namespace App\Filters\Order;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;

class OrderDateFilter
{
    public function __invoke(Builder $query, array $filters): Builder
    {
        $date = isset($filters['date']) ? $filters['date'] : null;

        return $query
            ->when(
                ! is_null($date),
                fn (Builder $q) => $q->where(
                    (new Order())->getTable().'.created_at',
                    $date
                )
            );
    }
}
