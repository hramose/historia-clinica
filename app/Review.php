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

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Review $review) { // before delete() method call this
            $review->clinicalCourse->delete();
            // do the rest of the cleanup...
        });
    }

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
        if ($reviewContent['sist_musculesqueletic']['observacions'] != "") {
            $content .= mb_strtoupper(trans('models.Reviewsist_musculesqueletic_observacions')) . "\\n";
            $content .= $reviewContent['sist_musculesqueletic']['observacions'] . "\\n";
        }
        if ($reviewContent['dolor']['observacions'] != "") {
            $content .= mb_strtoupper(trans('models.Reviewdolor_descripcio')) . "\\n";
            $content .= $reviewContent['dolor']['observacions'] . "\\n";
        }
        if ($reviewContent['sist_nervios'] != "") {
            $content .= mb_strtoupper(trans('models.Reviewssist_nervioso')) . "\\n";
            $content .= $reviewContent['sist_nervios'] . "\\n";
        }
        if ($reviewContent['sist_cardiovascular'] != "") {
            $content .= mb_strtoupper(trans('models.Reviewssist_cardiovascular')) . "\\n";
            $content .= $reviewContent['sist_cardiovascular'] . "\\n";
        }
        if ($reviewContent['sist_respiratori'] != "") {
            $content .= mb_strtoupper(trans('models.Reviewssist_respiratori')) . "\\n";
            $content .= $reviewContent['sist_respiratori'] . "\\n";
        }
        if ($reviewContent['sist_urogenital'] != "") {
            $content .= mb_strtoupper(trans('models.Reviewssist_urogenital')) . "\\n";
            $content .= $reviewContent['sist_urogenital'] . "\\n";
        }
        if ($reviewContent['sist_digestiu'] != "") {
            $content .= mb_strtoupper(trans('models.Reviewssist_digestiu')) . "\\n";
            $content .= $reviewContent['sist_digestiu'] . "\\n";
        }
        if ($reviewContent['altres'] != "") {
            $content .= mb_strtoupper(trans('models.Reviewsaltres')) . "\\n";
            $content .= $reviewContent['altres'] . "\\n";
        }
        $clinicalCourse->content = $content;
        /**
         * End content
         */
        $clinicalCourse->save();
    }
}