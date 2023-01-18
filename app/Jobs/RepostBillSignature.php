<?php

namespace App\Jobs;

use App\Models\ZmBill;
use App\Traits\VerificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RepostBillSignature implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, VerificationTrait;

    public $bill;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ZmBill $bill)
    {
        $this->bill = $bill;
        $this->onQueue('verification');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->sign($this->bill)){
            dispatch(new RepostBillSignature($this->bill));
        }
    }
}
