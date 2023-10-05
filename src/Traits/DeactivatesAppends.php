<?php

namespace Arandu\LaravelMuiAdmin\Traits;

trait DeactivatesAppends
{
    /** @var bool */
    public static $withoutAppends = false;

    protected function getArrayableAppends()
    {
        if (static::$withoutAppends) {
            return [];
        }

        return parent::getArrayableAppends();
    }
}
