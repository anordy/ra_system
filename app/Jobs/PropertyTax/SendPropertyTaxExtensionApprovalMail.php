<?php

namespace App\Jobs\PropertyTax;

use App\Mail\PropertyTax\PaymentExtensionApproval;
use App\Mail\PropertyTax\PropertyTaxApproved;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendPropertyTaxExtensionApprovalMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $extensionPayload;

    const SERVICE = 'property-tax-payment-extension-approved';


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($extensionPayload)
    {
        $this->extensionPayload = $extensionPayload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->extensionPayload['email']) {
            Mail::to($this->extensionPayload['email'])->send(new PaymentExtensionApproval($this->extensionPayload['name'], $this->extensionPayload['message']));
        }
    }
}
