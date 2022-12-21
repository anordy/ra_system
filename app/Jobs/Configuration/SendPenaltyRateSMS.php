<?php

namespace App\Jobs\Configuration;

use App\Models\Role;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Controllers\v1\SMSController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendPenaltyRateSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $payload;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sms_controller = new SMSController;

        // Send email to administrators
        $admin_role = Role::where('name', 'Administrator')->get()->first();
        $administrators = User::where('role_id', $admin_role->id)->get();

        $source = config('modulesconfig.smsheader');
        $customer_message = "Penalty rates for the year {$this->payload['currentYear']} have not been configured. Please log into the system and perform configurations.";

        if (count($administrators) > 0) {
            foreach ($administrators as $admin) {
                if ($admin->phone) {
                    $send_to = $admin->phone;
                    $sms_controller->sendSMS($send_to, $source, $customer_message);
                }
            }
        }
    }
}
