<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = array('name', 'surname', 'lastname', 'nif', 'birth_date', 'age', 'profession', 'hobbies', 'address');
}
