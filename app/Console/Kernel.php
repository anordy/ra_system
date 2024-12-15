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
        /**
         * For Cron Jobs use:
         *   NOTE: JOBS MUST BE PLACED SEQUENTIALLY
         *   CRON FORMAT: 
         * 
         * *    *    *    *    *
         *    -    -    -    -    -
         *  |    |    |    |    |
         *  |    |    |    |    |
         *  |    |    |    |    +----- day of week (0 - 7) (Sunday=0 or 7)
         *  |    |    |    +---------- month (1 - 12)
         *  |    |    +--------------- day of month (1 - 31)
         *  |    +-------------------- hour (0 - 23)
         *  +------------------------- min (0 - 59)
         *   SAMPLE CODE: $schedule->command('daily:debt')->cron('55 12 04 01 *')->runInBackground();
         */
        $schedule->command('daily:recon')->dailyAt('00:00')->runInBackground();
        $schedule->command('daily:debt')->dailyAt('00:10')->runInBackground();
        $schedule->command('daily:debt-penalty')->dailyAt('00:15')->runInBackground();
        $schedule->command('daily:debt-notice')->dailyAt('00:20')->runInBackground();
        $schedule->command('daily:reopen-business')->dailyAt('00:25')->runInBackground();
        $schedule->command('update:installment')->dailyAt('00:30')->runInBackground();
        $schedule->command('daily:delete-draft-businesses-exceed-seven-days')->dailyAt('00:45')->runInBackground();
        $schedule->command('app:ps-close')->dailyAt('01:00')->runInBackground();
        $schedule->command('app:ps-reopen')->dailyAt('01:02')->runInBackground();
        $schedule->command('app:ps-de-register')->dailyAt('01:04')->runInBackground();
        $schedule->command('dl:expired')->dailyAt('01:05')->runInBackground();
        $schedule->command('daily:debt-ntr')->dailyAt('01:10')->runInBackground();
        $schedule->command('rg:schedule')->dailyAt('12:00')->runInBackground();
        $schedule->command('annual:property-tax-bill')->yearlyOn(1,1,'00:50')->runInBackground();

        // RUNNING AT SPECIFIC TIME & DAY
        // $schedule->command('daily:debt')->cron('51 14 04 01 *')->runInBackground();
        // $schedule->command('daily:reopen-business')->cron('51 14 04 01 *')->runInBackground();
        // $schedule->command('daily:debt-penalty')->cron('51 14 04 01 *')->runInBackground();
        // $schedule->command('daily:debt-notice')->cron('51 14 04 01 *')->runInBackground();
        // $schedule->command('daily:tax-effective-date')->cron('51 14 04 01 *')->runInBackground();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
