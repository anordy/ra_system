<?php

namespace App\Jobs\PublicService;

use App\Traits\PaymentsTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GeneratePublicServiceControlNo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, PaymentsTrait;


    public $psReturn;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($psReturn)
    {
        $this->psReturn = $psReturn;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $this->generatePublicServiceControlNumber($this->psReturn);
    }
}
