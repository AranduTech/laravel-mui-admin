<?php

namespace Arandu\LaravelMuiAdmin\Contracts;

class Attribute
{

    public function __construct(
        public $key, 
        public $name
    ) {
    }

    public static function create($key, $name)
    {
        return new static($key, $name);
    }

    
}
