<?php

namespace App\Jobs\Business;

use App\Mail\Business\BusinessRegistered;
use App\Models\Business;
use App\Models\Taxpayer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendBusinessRegisteredMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $business, $taxpayer;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Business $business, Taxpayer $taxpayer)
    {
        $this->business = $business;
        $this->taxpayer = $taxpayer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->taxpayer->email){
            Mail::to($this->taxpayer->email)->send(new BusinessRegistered($this->business, $this->taxpayer));
        }
    }
}
