<?php

namespace Arandu\LaravelMuiAdmin\Facades;

use Arandu\LaravelMuiAdmin\Services\JsService;

use Illuminate\Support\Facades\Facade;

class Js extends Facade
{
    protected static function getFacadeAccessor()
    {
        return JsService::class;
    }
}
