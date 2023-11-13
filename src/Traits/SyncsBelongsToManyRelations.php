<?php

namespace Arandu\LaravelMuiAdmin\Traits;

trait SyncsBelongsToManyRelations
{

    public function getSyncs()
    {
        if (!isset($this->syncs)) {
            return [];
        }

        return $this->syncs;
    }
}