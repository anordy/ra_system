<?php

namespace App\Jobs\User;

use App\Http\Controllers\v1\SMSController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class SendRegistrationSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $password;
    private $first_name;
    private $phone_number;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $password, $first_name, $phone_number)
    {
        $this->email = $email;
        $this->password = $password;
        $this->first_name = $first_name;
        $this->phone_number = $phone_number;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sms_controller = new SMSController;
        $send_to = $this->phone_number;
        $source = config('modulesconfig.smsheader');
        $customer_message = "Dear {$this->first_name}, You have been registered into ZRB System. Please use credentials below to Log into the system. Email: {$this->email} Password: {$this->password}";
        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
