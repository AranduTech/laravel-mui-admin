<?php

namespace Arandu\LaravelMuiAdmin\Traits;

use Illuminate\Support\Facades\Log;

trait Importable
{
    public static function fromImportFile(array $data)
    {
        try {
            return static::create($data);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            return null;
        }
    }
}
