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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('daily:debt')->everyMinute()->runInBackground();
        $schedule->command('daily:reopen-business')->everyMinute()->runInBackground();
        $schedule->command('daily:debt-penalty')->everyMinute()->runInBackground();
        $schedule->command('daily:return-notice')->everyMinute()->runInBackground();
        $schedule->command('daily:debt-notice')->everyMinute()->runInBackground();
        $schedule->command('daily:tax-effective-date')->everyMinute()->runInBackground();
        $schedule->command('daily:debt-zero-balance')->everyMinute()->runInBackground();
        $schedule->command('daily:check-taxpayer-password-expire')->everyMinute()->runInBackground();
        $schedule->command('daily:check-user-password-expire')->everyMinute()->runInBackground();

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
