<?php

namespace App\Jobs\Vetting;

use App\Mail\Vetting\ToCorrectionReturn;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendToCorrectionReturnMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tax_return;

    const SERVICE = 'vetted-to-correction';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tax_return)
    {
        $this->tax_return = $tax_return;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->tax_return->taxpayer->email) {
            Mail::to($this->tax_return->taxpayer->email)->send(new ToCorrectionReturn($this->tax_return));
        }
    }
}
