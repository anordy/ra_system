<?php

namespace App\Jobs;

use App\Http\Controllers\v1\SMSController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCustomSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const SERVICE = 'send-custom-sms';

    private $phone;
    private $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phone, $message)
    {
        $this->phone = $phone;
        $this->message = $message;
    }

    /**
     * Execute the job.
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $sms_controller = new SMSController;
        $source = config('modulesconfig.smsheader');
        $sms_controller->sendSMS($this->phone, $source, $this->message);
    }
}
