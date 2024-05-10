<?php

namespace App\Jobs\Vfms;

use App\Http\Controllers\v1\SMSController;
use App\Models\Role;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ClientNotificationSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $message, $phone_number, $user_type, $source, $sms_controller;

    const SERVICE = 'vfms-client-notification-sms';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->message = $payload['message'];
        $this->phone_number = $payload['phone_number'];
        $this->user_type = $payload['user_type'];
        $this->source = config('modulesconfig.smsheader');
        $this->sms_controller = new SMSController;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->user_type == 'taxpayer'){
            $this->sms($this->phone_number, $this->message);
        } else {
            $admin_role = Role::where('name', 'Administrator')->get()->first();
            Log::info($admin_role);
            if ($admin_role) {
                $administrators = User::where('role_id', $admin_role->id)->where('status', true)->get();
                if (count($administrators) > 0) {
                    foreach ($administrators as $admin) {
                        if ($admin->phone) {
                            $this->sms($admin->phone, $this->message);
                        }
                    }
                }
            }
        }

    }

    private function sms($send_to, $customer_message){
        $this->sms_controller->sendSMS($send_to, $this->source, $customer_message);
    }
}
