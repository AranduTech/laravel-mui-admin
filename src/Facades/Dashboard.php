<?php

namespace Arandu\LaravelMuiAdmin\Facades;

use Arandu\LaravelMuiAdmin\Services\DashboardService;
use Illuminate\Support\Facades\Facade;

class Dashboard extends Facade
{
    protected static function getFacadeAccessor()
    {
        return DashboardService::class;
    }
}