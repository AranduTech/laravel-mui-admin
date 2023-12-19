<?php

namespace Arandu\LaravelMuiAdmin\Dashboard\Metrics;

use Arandu\LaravelMuiAdmin\Contracts\Metric;
use Illuminate\Support\Facades\DB;

class SumMetric extends Metric
{
    
    public function __construct(
        public $key,
        public $name,
        public $alias = null
    ) {
        parent::__construct($key, $name);
    }

    public function select() {
        return DB::raw(
            $this->alias
                ? "SUM({$this->key}) as {$this->alias}"
                : "SUM({$this->key})"
        );
    }
}