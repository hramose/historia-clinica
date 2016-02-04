<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = array('name', 'surname', 'lastname', 'nif', 'gender', 'birth_date', 'age', 'profession', 'hobbies', 'address');

    protected $dates = ['birth_date'];

    public function visits()
    {
        return $this->hasMany('App\Visit', 'patient_id');
    }

    public function review()
    {
        return $this->hasOne('App\Review', 'patient_id');
    }

    public function setBirthDateAttribute($date)
    {
        $this->attributes['birth_date'] = Carbon::createFromFormat('d/m/Y', $date);
    }

    /*public function getBirthDateAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
    }*/
}
