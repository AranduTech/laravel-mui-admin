<?php

namespace Arandu\LaravelMuiAdmin\Dashboard\Dimensions;

use Arandu\LaravelMuiAdmin\Contracts\Dimension;
use Illuminate\Database\Eloquent\Builder;

class StringDimension extends Dimension
{
    public function apply(Builder $query): Builder
    {
        return $query->groupBy($this->key);
    }

}