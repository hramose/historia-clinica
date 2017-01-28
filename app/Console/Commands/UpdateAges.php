<?php

namespace App\Console\Commands;

use App\Patient;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateAges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_ages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the patients ages to their actual age';

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
        $pacients = Patient::all();

        $pacients->each(function (Patient $patient) {
            if (!$patient->isTodayBirthdayOrPrevious()) {
                $birth_date = $patient->birth_date;
                $now = Carbon::now();

                $difference_in_years = $now->diffInYears($birth_date);
                $patient->age = $difference_in_years;
                $patient->save();
            }
        });

        $this->comment(PHP_EOL . Carbon::now()->format("d/m/Y H:i:s") . ' Fin Actualizar edades' . PHP_EOL);
    }
}
