<?php

namespace App\Jobs\PropertyTax;

use App\Mail\PropertyTax\PropertyTaxApproved;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendPropertyTaxApprovalMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $property;

    const SERVICE = 'property-tax-approved';


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
            Mail::to($this->property->taxpayer->email)->send(new PropertyTaxApproved($this->property));
        }
    }
}
