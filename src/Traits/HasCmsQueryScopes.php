<?php

namespace Arandu\LaravelMuiAdmin\Traits;

trait HasCmsQueryScopes
{

    public function scopeBeginCmsQuery($query, $request)
    {
        return $query;
    }

    public function scopeWhereCurrentUserCan($query, $action)
    {
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        $query->where(function ($query) use ($search) {
            foreach ($this->getFillable() as $fillable) {
                $query->orWhere($fillable, 'like', '%' . implode('%', explode(' ', $search)) . '%');
            }
        });
    }

    public function scopeWhereBelongsToTab($query, $tab)
    {
        if ('trashed' == $tab) {
            $query->onlyTrashed();
        }
    }

    public function scopeWhereMatchesFilter($query, $filters)
    {
        return $query;
    }

    public function scopeApplyOrderBy($query, $column, $direction)
    {
        $query->orderBy($column, $direction);
    }
}