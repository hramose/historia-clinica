<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    protected $fillable = ['date_of_backup', 'user_id'];
    protected $dates = ['date_of_backup'];
    public $timestamps = false;
}
