<?php

namespace App\Console\Commands;

use App\Patient;
use App\User;
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
     * @return void
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
        $pacients = Patient::all();
        $date = new Carbon();
        $pacientsBirthday = [];

        foreach ($pacients as $pacient) {
            $birthDate = explode('-', $pacient->birth_date);
            $birthDate[0] = date('Y');
            $birthDate = implode('-', $birthDate);
            $birthDate = new Carbon($birthDate);
            $days = $date->diffInDays($birthDate, false);
            if ($days == 5) {
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
        }

        $this->comment(PHP_EOL . $date->format("d/m/Y H:i:s") . ' Cumpleaños checkeados' . PHP_EOL);
    }
}
