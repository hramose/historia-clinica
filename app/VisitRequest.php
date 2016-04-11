<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VisitRequest extends Model
{
    public $timestamps = false;

    public static function boot()
    {
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }
}
