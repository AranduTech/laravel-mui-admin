<?php

namespace Arandu\LaravelMuiAdmin\Facades;

use Arandu\LaravelMuiAdmin\Services\SpreadsheetService;
use Illuminate\Support\Facades\Facade;

class Spreadsheet extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SpreadsheetService::class;
    }
}
