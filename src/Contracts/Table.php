<?php

namespace Arandu\LaravelMuiAdmin\Contracts;

use Arandu\LaravelMuiAdmin\Traits\Formable;

abstract class Table 
{
    use Formable;

    abstract public function default();

}