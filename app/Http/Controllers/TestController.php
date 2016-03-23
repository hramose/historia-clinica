<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * @param Request $request
     */
    public function getIndex(Request $request)
    {
        return view('test.index');
    }

    public function getBirthday($test_days)
    {
        $data = [];

        $pacients = Patient::all();
        $date = new Carbon();
        $pacientsBirthday = [];

        foreach ($pacients as $pacient) {
            $birthDate = explode('-', $pacient->birth_date);
            if ($birthDate[0] != '') {
                $birthDate[0] = date('Y');
                $birthDate = implode('-', $birthDate);
                $birthDate = new Carbon($birthDate);
                $days = $date->diffInDays($birthDate, false);
                if ($days <= $test_days) {
                    $pacientsBirthday[] = $pacient;
                }
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
}
