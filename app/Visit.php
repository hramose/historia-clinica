<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    public function patient() {
        return $this->belongsTo('App\Patients');
    }
}
