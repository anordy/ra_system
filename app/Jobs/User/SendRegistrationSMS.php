<?php

namespace App\Jobs\User;

use App\Http\Controllers\v1\SMSController;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SendRegistrationSMS implements ShouldQueue
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
        $user = User::find($this->payload);
        $code = Str::random(8);
        $user->password = Hash::make($code);
        $user->save();

        $sms_controller = new SMSController;
        $send_to = $user->phone_number;
        $source = config('modulesconfig.smsheader');
        $customer_message = "Dear {$user->fname}, You have been registered into ZRB System. Please use credentials below to Log into the system. Email: {$user->email} Password: {$code}";
        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
