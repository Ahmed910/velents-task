<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

interface FiltersContract
{
    public function apply(Builder $query): Builder;
}
