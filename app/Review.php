<?php

namespace App;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['review', 'date'];
    protected $dates = ['date'];
    protected $casts = [
        'review' => 'array',
    ];
    public $timestamps = false;

    public function patient()
    {
        return $this->belongsTo('App\Patient');
    }

    public function setDateAttribute($date)
    {
        $this->attributes['date'] = $date instanceof Carbon ? $date : Carbon::createFromFormat('d-m-Y', $date);
    }

    public function getDateAttribute($date) {
        return date('d/m/Y', strtotime($date));
    }
}
