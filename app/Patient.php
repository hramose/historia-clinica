<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = array('name', 'surname', 'lastname', 'email', 'phone', 'nif', 'gender', 'birth_date', 'age', 'profession', 'hobbies', 'address', 'city', 'postal_code');

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
        $this->attributes['birth_date'] = $date ? Carbon::createFromFormat('d/m/Y', $date) : null;
    }

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->surname . ' ' . $this->lastname;
    }

    /*public function getBirthDateAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
    }*/
}
