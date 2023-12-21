<?php

namespace Arandu\LaravelMuiAdmin\Dashboard\Dimensions;

use Arandu\LaravelMuiAdmin\Contracts\Dimension;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class BelongsToDimension extends Dimension
{

    public function __construct(
        public $relation,
        public $name,
        public $key = null,
    ) {
        if (!$key) {
            $key = $this->relation . '_id';
        }

        parent::__construct($key, $name);
    }

    public function apply(Builder $query): Builder {
        return $query->with($this->relation)->groupBy($this->key);
    }

    public function jsonSerialize(): mixed
    {
        return array_merge(
            parent::jsonSerialize(),
            [
                'relation' => $this->relation,
            ]
        );
    }
}
