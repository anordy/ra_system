<?php

namespace App\Jobs\Account;

use App\Mail\Account\ReferenceNumberRecoveryMail;
use App\Mail\Branch\BranchRegistered;
use App\Models\Taxpayer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendReferenceNumberMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const SERVICE = 'send-reference-number';

    private $taxpayer;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Taxpayer $taxpayer)
    {
        $this->taxpayer = $taxpayer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->taxpayer->email){
            Mail::to($this->taxpayer->email)->send(new ReferenceNumberRecoveryMail($this->taxpayer));
        }
    }
}
