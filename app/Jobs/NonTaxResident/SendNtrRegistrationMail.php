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

    private $taxpayer, $code;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($taxpayer, $code)
    {
        $this->taxpayer = $taxpayer;
        $this->code = $code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->taxpayer->email) {
            Mail::to($this->taxpayer->email)->send(new Registration($this->taxpayer, $this->code));
        }
    }
}
