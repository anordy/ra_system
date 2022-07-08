<?php

namespace App\Jobs\Business;

use App\Mail\Business\Deregister\BusinessDeregisterApproved;
use App\Models\Business;
use App\Models\Taxpayer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendBusinessDeregisterApprovedMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $business;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Business $business)
    {

        $this->business = $business;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->business->taxpayer->email){
            Mail::to($this->business->taxpayer->email)->send(new BusinessDeregisterApproved($this->business, $this->business->taxpayer));
        }
    }
}
