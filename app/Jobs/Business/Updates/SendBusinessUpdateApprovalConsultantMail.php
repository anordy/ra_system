<?php

namespace App\Jobs\Business\Updates;

use App\Mail\Business\Updates\BusinessInformationApproval;
use App\Mail\Business\Updates\BusinessInformationConsultantApproval;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendBusinessUpdateApprovalConsultantMail implements ShouldQueue
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
        if ($this->payload['consultant']->taxpayer->email){
            Mail::to($this->payload['consultant']->taxpayer->email)->send(new BusinessInformationConsultantApproval($this->payload));
        }
    }
}
