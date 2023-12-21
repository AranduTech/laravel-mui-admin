<?php

namespace Arandu\LaravelMuiAdmin\Dashboard\Metrics;

use Arandu\LaravelMuiAdmin\Contracts\Metric;

class CountMetric extends Metric
{
    
    public function __construct(
        public $key,
        public $name,
        public $alias = null
    ) {
        parent::__construct($key, $name);

        if (!$this->alias) {
            $this->alias = "count_" . $this->key;
        }
    }

    public function select() {
        return "COUNT({$this->key}) as {$this->alias}";
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
