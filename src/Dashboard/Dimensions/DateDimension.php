<?php

namespace Arandu\LaravelMuiAdmin\Dashboard\Dimensions;

use Arandu\LaravelMuiAdmin\Contracts\Dimension;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DateDimension extends Dimension
{

    public function __construct(
        public $key,
        public $name,
        public $alias = null
    ) {
        parent::__construct($key, $name);
    }

    public function apply(Builder $query): Builder {
        return $query->groupBy($this->alias ?? "DATE({$this->key})");
    }

    public function select() {
        return DB::raw(
            $this->alias
                ? "DATE({$this->key}) as {$this->alias}"
                : "DATE({$this->key})"
        );
    }

}
