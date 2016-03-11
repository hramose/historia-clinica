<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    public $full_name = '';

    protected $fillable = array('name', 'surname', 'lastname', 'nif', 'gender', 'birth_date', 'age', 'profession', 'hobbies', 'address');

    protected $dates = ['birth_date'];

    protected $appends = ['full_name'];

    public function visits()
    {
        return $this->hasMany('App\Visit', 'patient_id');
    }

    public function reviews()
    {
        return $this->hasMany('App\Review');
    }

    public function setBirthDateAttribute($date)
    {
        $this->attributes['birth_date'] = $date ? Carbon::createFromFormat('d/m/Y', $date) : null;
    }

    /*public function getBirthDateAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
    }*/

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->surname . ' ' . $this->lastname;
    }
}
