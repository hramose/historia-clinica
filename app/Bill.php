<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = ['id', 'patient_id', 'client_id', 'iva', 'irpf', 'concept', 'qty', 'amount', 'creation_date', 'expiration_date'];
    public $timestamps = false;
    public $dates = ['creation_date', 'expiration_date'];

    public function patient()
    {
        return $this->belongsTo('App\Patient');
    }

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

}
