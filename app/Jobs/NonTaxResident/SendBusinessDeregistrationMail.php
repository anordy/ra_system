<?php

namespace App\Jobs\NonTaxResident;

use App\Mail\NonTaxResident\DeregistrationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBusinessDeregistrationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const SERVICE = 'ntr-business-deregistration-mail';

    public $ownerEmail, $status, $businessName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ownerEmail, $businessName, $status)
    {
        $this->ownerEmail = $ownerEmail;
        $this->status = $status;
        $this->businessName = $businessName;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::to($this->ownerEmail)->send(new DeregistrationMail($this->businessName, $this->status));
        } catch (\Exception $exception) {
            Log::error('NON-TAX-RESIDENT-SEND-BUSINESS-DE-REGISTRATION-MAIL-JOB', [$exception]);
        }

    }
}
