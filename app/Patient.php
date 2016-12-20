<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = array('name', 'surname', 'lastname', 'email', 'phone', 'nif', 'gender', 'birth_date', 'age', 'profession', 'hobbies', 'address', 'city', 'postal_code');

    protected $dates = ['birth_date'];

    protected $appends = ['full_name'];

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

    public static function checkBirthdaysNotNotified()
    {
        $pacients = Patient::whereRaw("DATE_ADD(birth_date,INTERVAL YEAR(CURDATE())-YEAR(birth_date)
  + IF(MONTH(CURDATE()) > MONTH(birth_date), 1,
     IF(MONTH(CURDATE()) = MONTH(birth_date) AND DAY(CURDATE()) > DAY(birth_date), 1, 0))
       YEAR)
        BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY)")
            ->whereNotIn('id', function ($query) {
                $query->select('patient_id')
                    ->from(with(new BirthdaysNotification())->getTable())
                    ->where('year', '=', date('Y'));
            })->get();

        $birthdays = [];
        foreach ($pacients as $pacient) {
            $date = new \Carbon\Carbon($pacient->birth_date);
            $age = $pacient->age + 1;
            $birthdays[] = [
                'full_name' => $pacient->full_name,
                'date' => $date->formatLocalized('%d de %B'),
                'age' => $age
            ];
        }

        return $birthdays;
    }

    public static function checkBirthdays()
    {
        $pacients_wo_check = Patient::whereRaw("DATE_ADD(birth_date,INTERVAL YEAR(CURDATE())-YEAR(birth_date)
  + IF(MONTH(CURDATE()) > MONTH(birth_date), 1,
     IF(MONTH(CURDATE()) = MONTH(birth_date) AND DAY(CURDATE()) > DAY(birth_date), 1, 0))
       YEAR)
        BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->get();

        $birthdays_wo_check = [];
        foreach ($pacients_wo_check as $pacient) {
            $date = new \Carbon\Carbon($pacient->birth_date);
            $age = $pacient->age + 1;
            $birthdays_wo_check[] = [
                'full_name' => $pacient->full_name,
                'date' => $date->formatLocalized('%d de %B'),
                'age' => $age
            ];
        }

        return $birthdays_wo_check;
    }

    public function isTodayHisBirthday()
    {
        $birth_date = $this->birth_date;
        $now = Carbon::now();
    }

    /*public function getBirthDateAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
    }*/
}
