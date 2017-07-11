<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ClinicalCourse extends Model
{
    protected $dates = ['date'];
    public $timestamps = false;
    public $table = 'clinical_course';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cc) {
            /*$cc->date = Carbon::now();*/
        });
    }

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
        if (!is_null($this->review)) {
            $reviewDate = $this->review->date;
            $explodedDate = explode('/', $reviewDate);
            $correctedReviewDate = $explodedDate[2] . '-' . $explodedDate[1] . '-' . $explodedDate[0];
            return date('Y-m-d H:i:s', strtotime($correctedReviewDate));
        }

        return date('Y-m-d H:i:s', strtotime($date));
    }
}
