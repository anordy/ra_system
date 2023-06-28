<?php

namespace App\Jobs\Vfms;

use App\Http\Controllers\v1\SMSController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ClientNotificationSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $message;
    private $phone_number;

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
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sms_controller = new SMSController;
        $source = config('modulesconfig.smsheader');
        $sms_controller->sendSMS($this->phone_number, $source, $this->message);
    }
}
