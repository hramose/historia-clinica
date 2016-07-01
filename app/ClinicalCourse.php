<?php

namespace App;

use Carbon\Carbon;
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

    public function setDateAttribute($date)
    {
        $this->attributes['date'] = $date instanceof Carbon ? $date : Carbon::createFromFormat('d/m/Y H:i:s', $date);
    }

    public function getDateAttribute($date)
    {
        return date('d/m/Y H:i:s', strtotime($date));
    }
}
