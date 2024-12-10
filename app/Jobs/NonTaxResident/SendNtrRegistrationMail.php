<?php

namespace App\Jobs\NonTaxResident;

use App\Mail\NonTaxResident\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNtrRegistrationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const SERVICE = 'ntr-registration';

    private $business, $code;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($business, $code)
    {
        $this->business = $business;
        $this->code = $code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->business->email) {
            Mail::to($this->business->email)->send(new Registration($this->business, $this->code));
        }
    }
}
