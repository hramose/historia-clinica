<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'address', 'city', 'postal_code', 'cif'];
    public $timestamps = false;
}