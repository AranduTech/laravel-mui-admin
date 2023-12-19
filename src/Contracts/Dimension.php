<?php


namespace Arandu\LaravelMuiAdmin\Contracts;

use Illuminate\Database\Eloquent\Builder;

abstract class Dimension extends Attribute
{

    /**
     * Applies the dimension to the query.
     * 
     * @param Builder $query 
     * @return Builder 
     */
    public function apply(Builder $query): Builder {
        return $query;
    }

    /**
     * Adds a column or expression to the SELECT clause of the query.
     * 
     * @return string|\Illuminate\Database\Query\Expression
     */
    public function select() {
        return $this->key;
    }


}
