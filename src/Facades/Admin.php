<?php

namespace Arandu\LaravelMuiAdmin\Facades;

use Arandu\LaravelMuiAdmin\Services\AdminService;
use Illuminate\Support\Facades\Facade;

class Admin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return AdminService::class;
    }
}
