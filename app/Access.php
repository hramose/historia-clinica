<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    protected $table = 'access';
    protected $fillable = ['date', 'user_id', 'ip', 'route', 'type', 'action', 'browser'];
    protected $dates = ['date'];
    public $timestamps = false;

}
