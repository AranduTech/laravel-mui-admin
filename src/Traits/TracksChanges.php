<?php

namespace Arandu\LaravelMuiAdmin\Traits;

trait TracksChanges {

    public static function bootTracksChanges()
    {
        static::creating(function ($model) {
            if (!$model->isDirty('created_by')) {
                $model->created_by = auth()->id();
            }
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = auth()->id();
            }
        });

    }

    public function createdBy()
    {
        return $this->belongsTo(config('admin.cms.user_model'), 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(config('admin.cms.user_model'), 'updated_by');
    }
}
