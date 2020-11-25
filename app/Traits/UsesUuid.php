<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UsesUuid
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = Str::uuid();
        });
    }
}
