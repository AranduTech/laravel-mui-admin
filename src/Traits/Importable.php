<?php

namespace Arandu\LaravelMuiAdmin\Traits;

use Illuminate\Support\Facades\Log;

trait Importable
{
    public static function fromImportFile(array $row)
    {
        try {
            return static::create($row);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            return null;
        }
    }
}
