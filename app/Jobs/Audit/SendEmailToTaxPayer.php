<?php

namespace App\Jobs\Audit;

use App\Mail\Audit\AuditSendEmailTaxpayer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailToTaxPayer implements ShouldQueue
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
        //
        $taxpayerName = $this->payload->first_name;
        $email = $this->payload->email;
        if ($email) {
            Mail::to($email)->send(new AuditSendEmailTaxpayer($taxpayerName));
        }
    }
}
