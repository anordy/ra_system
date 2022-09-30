<?php

namespace App\Jobs\Dispute;

use App\Traits\PaymentsTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateAssessmentDisputeControlNo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, PaymentsTrait;

    public $assessment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($assessment)
    {
        $this->assessment = $assessment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->generateWaivedAssessmentDisputeControlNo($this->assessment);
    }
}
