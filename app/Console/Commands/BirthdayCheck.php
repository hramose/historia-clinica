<?php

namespace App\Console\Commands;

use App\BirthdaysNotification;
use App\Patient;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class BirthdayCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthday_check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for pacients birthday';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
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
                $message->to('fisioterapia@hcabosantos.cat', 'Fisioteràpia HCaboSantos.cat')
                    ->subject(trans('messages.pacients_birthday_subject'));
            });

            $stringCumpleaños = "";
            foreach ($pacientsBirthday as $pacient) {
                $datePacient = new Carbon($pacient->birth_date);
                $age = $pacient->age + 1;
                $stringCumpleaños .= "- {$pacient->full_name} cumple años el {$datePacient->formatLocalized('%d de %B')} serán {$age} años.\n";
            }
            $this->comment(PHP_EOL . $stringCumpleaños);
        }

        $this->comment(PHP_EOL . $date->format("d/m/Y H:i:s") . ' Fin Cumpleaños checkeados' . PHP_EOL);
    }
}
