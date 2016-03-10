<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\Backup::class,
        \App\Console\Commands\EmptyZipDirectory::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*$schedule->command('inspire')->everyMinute()->appendOutputTo(storage_path('logs/laravel.log'));*/
        $schedule->command('backup_db')->daily();
        $schedule->command('empty_zip_dir')->everyMinute()->appendOutputTo(storage_path('logs/laravel.log'));
    }
}
