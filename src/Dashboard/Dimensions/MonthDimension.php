<?php

namespace Arandu\LaravelMuiAdmin\Dashboard\Dimensions;

use Arandu\LaravelMuiAdmin\Contracts\Dimension;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class MonthDimension extends Dimension
{

    public function __construct(
        public $key,
        public $name,
        public $alias = null
    ) {
        parent::__construct($key, $name);

        if (!$alias) {
            $this->alias = "month_" . $this->key;
        }
    }

    public function apply(Builder $query): Builder {
        return $query->groupBy($this->alias);
    }

    public function select() {
        return DB::raw("DATE_FORMAT({$this->key}, '%Y-%m') as {$this->alias}");
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
