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

    public function patient()
    {
        return $this->belongsTo('App\Patient');
    }

    public function setDateAttribute($date)
    {
        $this->date = $this->dateStringToCarbon($date);
    }

    protected function dateStringToCarbon($date, $format = array('d M Y', 'd#m#y', 'd#m#Y', 'd/m/Y H:i:s'))
    {
        if (!$date instanceof Carbon) {
            $validDate = false;
            if (is_array($format)) {
                foreach ($format as $f) {
                    try {
                        $date = Carbon::createFromFormat($f, $date);
                        $validDate = true;
                    } catch (Exception $e) {
                    }
                    if ($validDate) {
                        break;
                    }
                }
            } else {
                try {
                    $date = Carbon::createFromFormat($format, $date);
                    $validDate = true;
                } catch (Exception $e) {
                }
            }

            if (!$validDate) {
                try {
                    $date = Carbon::parse($date);
                    $validDate = true;
                } catch (Exception $e) {
                }
            }

            if (!$validDate) {
                $date = NULL;
            }
        }
        return $date;

    }
}
