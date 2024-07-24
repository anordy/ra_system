<?php

namespace App\Console\Commands;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Jobs\SendCustomMail;
use App\Jobs\SendCustomSMS;
use App\Models\BusinessLocation;
use App\Models\LandLease;
use Illuminate\Console\Command;

class SendLandLeaseNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        foreach (LandLease::query()->whereNotNull('completed_at')->whereHas('businessLocation')->get() as $lease) {

            event(new SendSms(SendCustomSMS::SERVICE, NULL, [
                'phone' => $lease->businessLocation->taxpayer->mobile,
                'message' => "Hello {$lease->businessLocation->taxpayer->fullname}, your land lease registration for {$lease->dp_number} has been completed successfully. Please log into your ZRA account to generate control number and proceed with payments."
            ]));

            event(new SendMail(SendCustomMail::SERVICE, $lease->businessLocation->taxpayer->email, [
                'name' => $lease->businessLocation->taxpayer->fullname,
                'subject' => "Land Lease Registration - {$lease->dp_number}",
                'message' => "Your land lease registration for {$lease->dp_number} has been completed successfully. Please log into your ZRA account to generate control number and proceed with payments."
            ]));
        }
    }
}
