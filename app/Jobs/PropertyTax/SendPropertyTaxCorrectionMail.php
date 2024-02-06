<?php

namespace App\Jobs\PropertyTax;

use App\Mail\PropertyTax\PropertyTaxApproved;
use App\Mail\PropertyTax\PropertyTaxCorrection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPropertyTaxCorrectionMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $property;

    const SERVICE = 'property-tax-correction';


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($property)
    {
        $this->property = $property;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->property->taxpayer->email) {
            Mail::to($this->property->taxpayer->email)->send(new PropertyTaxCorrection($this->property));
        }
    }
}
