<?php

namespace App\Jobs\Bill;

use App\Traits\PaymentsTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateBill implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, PaymentsTrait;

    public $bill;
    public $expirationDate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($bill, $expirationDate)
    {
        $this->bill = $bill;
        $this->expirationDate = $expirationDate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->updateBill($this->bill, $this->expirationDate);
    }
}
