<?php

namespace App\Traits;

use App\Filters\FiltersContract;
use Illuminate\Database\Eloquent\Builder;

trait FiltersScope
{
    public function scopeFilters(Builder $q, FiltersContract $filters): Builder
    {
        return $filters->apply($q);
    }
}
