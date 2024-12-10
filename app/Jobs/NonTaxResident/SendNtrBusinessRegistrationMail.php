<?php

namespace App\Jobs\NonTaxResident;

use App\Mail\NonTaxResident\BusinessRegistrationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNtrBusinessRegistrationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const SERVICE = 'ntr-business-registration-mail';

    public $business, $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($business, $message)
    {
        $this->business = $business;
        $this->message = $message;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if ($this->business->email) {
                Mail::to($this->business->email)->send(new BusinessRegistrationMail($this->business, $this->message));
            }
        } catch (\Exception $exception) {
            Log::error('NON-TAX-RESIDENT-SEND-BUSINESS-REGISTRATION-MAIL-JOB', [$exception]);
        }

    }
}
