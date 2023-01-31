<?php

namespace App\Jobs;

use App\Models\Returns\TaxReturn;
use App\Traits\VerificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RepostReturnSignature implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, VerificationTrait;

    public $tries = 0;
    public $backoff = 10;
    public $return;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(TaxReturn $return)
    {
        $this->return = $return;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->sign($this->return)){
            $this->fail();
            dispatch(new RepostReturnSignature($this->return));
        }
    }
}
