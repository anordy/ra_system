<?php

namespace App\Jobs\Debt;

use App\Traits\PaymentsTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateAssessmentDebtControlNo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, PaymentsTrait;

    public $debt;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($debt)
    {
        $this->debt = $debt;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->generateAssessmentDebtControlNo($this->debt);
    }
}
