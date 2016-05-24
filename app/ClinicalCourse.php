<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClinicalCourse extends Model
{
    protected $dates = ['date'];
    public $timestamps = false;
    public $table = 'clinical_course';

    public function review()
    {
        return $this->belongsTo('App\Review');
    }

    public function patient()
    {
        return $this->hasOne('App\Patient');
    }
}
