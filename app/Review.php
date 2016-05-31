<?php

namespace App;

use Carbon\Carbon;
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

    public function clinicalCourse()
    {
        return $this->hasOne('App\ClinicalCourse');
    }

    public function setDateAttribute($date)
    {
        $this->attributes['date'] = $date instanceof Carbon ? $date : Carbon::createFromFormat('d-m-Y', $date);
    }

    public function getDateAttribute($date)
    {
        return date('d/m/Y', strtotime($date));
    }

    public function createOrStoreClinicalCourse()
    {
        /**
         * @var $clinicalCourse ClinicalCourse
         */
        $clinicalCourse = null;
        if ($this->clinicalCourse != null) {
            $clinicalCourse = $this->clinicalCourse;
        } else {
            $clinicalCourse = new ClinicalCourse();
        }
        $clinicalCourse->patient_id = $this->patient_id;
        $clinicalCourse->review_id = $this->id;
        /**
         * Create the content
         */
        $reviewContent = $this->review;
        mb_internal_encoding('UTF-8');
        $content = "";
        if ($reviewContent['antecedents'] != "") {
            $content .= mb_strtoupper(trans('models.Reviewantecedents')) . "\\n";
            $content .= $reviewContent['antecedents'] . "\\n";
        }
        if ($reviewContent['motiu_consulta'] != "") {
            $content .= mb_strtoupper(trans('models.Reviewmotiu_consulta')) . "\\n";
            $content .= $reviewContent['motiu_consulta'] . "\\n";
        }
        if ($reviewContent['limit_articular']['observacions'] != "") {
            $content .= mb_strtoupper(trans('models.Reviewlimitarticular_observacions')) . "\\n";
            $content .= $reviewContent['limit_articular']['observacions'] . "\\n";
        }
        if ($reviewContent['dolor']['observacions'] != "") {
            $content .= mb_strtoupper(trans('models.Reviewdolor_descripcio')) . "\\n";
            $content .= $reviewContent['dolor']['observacions'] . "\\n";
        }
        if ($reviewContent['forca_muscular']['observacions'] != "") {
            $content .= mb_strtoupper(trans('models.Reviewlimitarticular_observacions')) . "\\n";
            $content .= $reviewContent['forca_muscular']['observacions'] . "\\n";
        }
        if ($reviewContent['sensibilitzacio'] != "") {
            $content .= mb_strtoupper(trans('models.Reviewssensibilitzacio')) . "\\n";
            $content .= $reviewContent['sensibilitzacio'] . "\\n";
        }
        $clinicalCourse->content = $content;
        /**
         * End content
         */
        $clinicalCourse->date = $clinicalCourse->date == null ? Carbon::now() : $clinicalCourse->date;
        $clinicalCourse->save();
    }
}
