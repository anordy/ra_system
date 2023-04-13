<?php

namespace App\Jobs\Installment;

use App\Mail\Installment\InstallmentRejected;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendInstallmentRejectedMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const SERVICE = 'installment-rejected';

    private $installment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($installment)
    {
        $this->installment = $installment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->installment->business->taxpayer->email) {
            Mail::to($this->installment->business->taxpayer->email)
                ->send(new InstallmentRejected($this->installment));
        }
    }
}
