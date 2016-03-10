<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['review'];

    public function patient()
    {
        return $this->belongsTo('App\Patient');
    }
}
