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
        \App\Console\Commands\auto_insert::class,
        \App\Console\Commands\DayIssue::class,
        \App\Console\Commands\count_result::class,
        \App\Console\Commands\issu_close::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        // $schedule->command('auto:update')
        //          ->everyMinute();
        // $schedule->command('command:dayissue')
        //          ->dailyAt('09:15');
        // $schedule->command('command:ResultCount')
        //          ->everyTenMinutes();
        // $schedule->command('command:close')
        //          ->everyMinutes();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
    
}
