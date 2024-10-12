<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BaseFilters implements FiltersContract
{
    protected array $filters = [];

    protected array $filterClasses = [];

    public function __construct(protected Request $request)
    {
    }

    public function apply(Builder $query): Builder
    {
        foreach ($this->filterClasses as $class) {
            $instance = new $class;
            if (is_callable($instance)) {
                $query = $instance($query, $this->receivedFilters());
            }
        }

        return $query;
    }

    protected function receivedFilters(): array
    {
        return $this->request->only($this->filters);
    }
}
