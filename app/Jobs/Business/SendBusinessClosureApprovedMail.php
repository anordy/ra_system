<?php

namespace App\Jobs\Business;

use App\Mail\Business\Closure\BusinessClosureApproved;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendBusinessClosureApprovedMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $closure;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($closure)
    {
        $this->closure = $closure;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->closure->business->taxpayer->email){
            Mail::to($this->closure->business->taxpayer->email)->send(new BusinessClosureApproved($this->closure));
        }
    }
}
