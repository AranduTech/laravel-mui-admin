<?php

namespace Arandu\LaravelMuiAdmin\Dashboard\Metrics;

use Arandu\LaravelMuiAdmin\Contracts\Metric;
use Illuminate\Support\Facades\DB;

class AverageMetric extends Metric
{
    
    public function __construct(
        public $key,
        public $name,
        public $alias = null
    ) {
        parent::__construct($key, $name);

        if (!$this->alias) {
            $this->alias = "avg_" . $this->key;
        }
    }

    public function select() {
        return DB::raw("AVG({$this->key}) as {$this->alias}");
    }

    public function jsonSerialize(): mixed
    {
        return array_merge(
            parent::jsonSerialize(),
            [
                'alias' => $this->alias,
            ]
        );
    }
}