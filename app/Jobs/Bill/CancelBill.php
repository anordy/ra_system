<?php

namespace App\Jobs\Bill;

use App\Traits\PaymentsTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CancelBill implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, PaymentsTrait;

    public $bill;
    public $cancellationReason;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($bill, $cancellationReason)
    {
        $this->bill = $bill;
        $this->cancellationReason = $cancellationReason;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->cancelBill($this->bill, $this->cancellationReason);
    }
}
