<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = array('name', 'surname', 'lastname', 'nif', 'birth_date', 'age', 'profession', 'hobbies', 'address');

    public function visits()
    {
        return $this->hasMany('App\Visit', 'patient_id');
    }
}
