<?php

namespace App\Jobs\PropertyTax;

use App\Traits\PaymentsTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GeneratePropertyTaxControlNo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, PaymentsTrait;

    public $propertyPayment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($propertyPayment)
    {
        $this->propertyPayment = $propertyPayment;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $this->generatePropertyTaxControlNumber($this->propertyPayment);
    }
}
