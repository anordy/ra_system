<?php

namespace App\Console\Commands;

use App\Jobs\Notifications\SendNullZnumberMail;
use App\Jobs\Notifications\SendNullZnumberSMS;
use App\Models\Business;
use Illuminate\Console\Command;

class NotifyNullZNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:znumber';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to businesses with no Z-number';

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
     * @return int
     */
    public function handle()
    {
        $businesses = Business::whereNull('previous_zno')->get();

        foreach ($businesses as $business) {
            SendNullZnumberMail::dispatch($business);
            SendNullZnumberSMS::dispatch($business);
        }
    }
}
