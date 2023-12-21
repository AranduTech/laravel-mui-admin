<?php

namespace Arandu\LaravelMuiAdmin\Dashboard\Dimensions;

use Arandu\LaravelMuiAdmin\Contracts\Dimension;
use Illuminate\Database\Eloquent\Builder;

class RawDimension extends Dimension
{

    private $raw;

    public function raw($raw) {
        $this->raw = $raw;
        return $this;
    }

    public function apply(Builder $query): Builder
    {
        return $query->groupBy($this->key);
    }

    public function select()
    {
        return "{$this->raw} as {$this->key}";
    }
}