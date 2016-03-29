<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = ['id', 'patient_id', 'client_id', 'iva', 'irpf', 'concept', 'qty', 'amount', 'creation_date', 'expiration_date', 'payment_method', 'discount', 'price_per_unit'];
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

    public function setCreationDateAttribute($date)
    {
        $this->attributes['creation_date'] = $date instanceof Carbon ? $date : Carbon::createFromFormat('d/m/Y', $date);
    }

    public function setExpirationDateAttribute($date)
    {
        $this->attributes['expiration_date'] = $date instanceof Carbon ? $date : Carbon::createFromFormat('d/m/Y', $date);
    }

    public function setPatientIdAttribute($patient_id)
    {
        $this->attributes['patient_id'] = !is_null($patient_id) && $patient_id != "" ? $patient_id : null;
    }

    public function setClientIdAttribute($client_id)
    {
        $this->attributes['client_id'] = !is_null($client_id) && $client_id != "" ? $client_id : null;
    }

    public function setPricePerUnitAttribute($price_per_unit)
    {
        $this->attributes['price_per_unit'] = str_replace(',', '.', $price_per_unit);
    }

    public function setIrpfAttribute($irpf)
    {
        $this->attributes['irpf'] = str_replace(',', '.', $irpf);
    }

    public function setDiscountAttribute($discount)
    {
        $this->attributes['discount'] = str_replace(',', '.', $discount);
    }

}