<?php

namespace App\Jobs\Business\Taxtype;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\Business\Taxtype\ChangeTaxType;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendTaxTypeMail implements ShouldQueue
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
        if ($this->payload['business']->taxpayer->email){
            Mail::to($this->payload['business']->taxpayer->email)->send(new ChangeTaxType($this->payload));
        }
    }
}
