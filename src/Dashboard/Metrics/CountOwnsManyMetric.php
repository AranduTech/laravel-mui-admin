<?php

namespace Arandu\LaravelMuiAdmin\Dashboard\Metrics;

use Arandu\LaravelMuiAdmin\Contracts\Metric;
use Illuminate\Database\Eloquent\Builder;

class CountOwnsManyMetric extends Metric
{

    public function apply(Builder $query): Builder
    {
        return $query->withCount($this->key);
    }

    public function select()
    {
        return "{$this->key}_count";
    }

    public function jsonSerialize(): mixed
    {
        return array_merge(
            parent::jsonSerialize(),
            [
                'alias' => "{$this->key}_count",
            ]
        );
    }

}
