<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(Request $request)
    {
        return view('api.index');
    }

    public function getBirthday($test_days)
    {
        $data = [];

        $pacients = Patient::whereRaw("DATE_ADD(birth_date,
                INTERVAL YEAR(CURDATE())-YEAR(birth_date)
                         + IF(DAYOFYEAR(CURDATE()) >= DAYOFYEAR(birth_date),1,0)
                YEAR)
            BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL " . $test_days . " DAY)")->get();
        $pacientsBirthday = [];

        foreach ($pacients as $pacient) {
            $birthDate = explode('-', $pacient->birth_date);
            if ($birthDate[0] != '') {
                $pacientsBirthday[] = $pacient;
            }
        }

        setlocale(LC_TIME, 'es_ES.utf8');
        $stringCumpleaños = "";
        foreach ($pacientsBirthday as $pacient) {
            $date = new \Carbon\Carbon($pacient->birth_date);
            $age = $pacient->age + 1;
            $stringCumpleaños .= nl2br("- {$pacient->full_name} cumple años el {$date->formatLocalized('%d de %B')}, serán {$age} años.\n");
        }

        $data['output'] = $stringCumpleaños;
        $data['time'] = microtime(true) - LARAVEL_START;

        return response()->json($data);
    }

    public function getBirthdayEmail($test_days)
    {
        $data = [];

        $pacients = Patient::whereRaw("DATE_ADD(birth_date,
                INTERVAL YEAR(CURDATE())-YEAR(birth_date)
                         + IF(DAYOFYEAR(CURDATE()) >= DAYOFYEAR(birth_date),1,0)
                YEAR)
            BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL " . $test_days . " DAY)")->get();
        $date = new Carbon();
        $pacientsBirthday = [];

        foreach ($pacients as $pacient) {
            $birthDate = explode('-', $pacient->birth_date);
            if ($birthDate[0] != '') {
                $pacientsBirthday[] = $pacient;
            }
        }

        if (count($pacientsBirthday) > 0) {
            setlocale(LC_TIME, 'ca_ES.utf8');
            $data = [
                'pacients' => $pacientsBirthday
            ];

            Mail::send('emails.birthday', $data, function (Message $message) {
                $message->from('fisioterapia@hcabosantos.cat', 'Cercador d\'aniversaris HCaboSantos.cat');
                $message->to('suport@hcabosantos.cat', 'Fisioteràpia HCaboSantos.cat')
                    ->subject(trans('messages.pacients_birthday_subject'));
            });

            $stringCumpleaños = "";
            foreach ($pacientsBirthday as $pacient) {
                $date = new \Carbon\Carbon($pacient->birth_date);
                $age = $pacient->age + 1;
                $stringCumpleaños .= nl2br("- {$pacient->full_name} cumple años el {$date->formatLocalized('%d de %B')}, serán {$age} años.\n");
            }
            $stringCumpleaños .= "<br>------- Mail enviado -------";

            $data['output'] = $stringCumpleaños;
        } else {
            $data['output'] = '';
        }

        $data['time'] = microtime(true) - LARAVEL_START;
        return response()->json($data);
    }
}
