<?php

namespace Arandu\LaravelMuiAdmin\Contracts;

class Attribute implements \JsonSerializable
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

    public function jsonSerialize(): mixed
    {
        return [
            'key' => $this->key,
            'name' => $this->name,
        ];
    }

    
}
