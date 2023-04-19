<?php

namespace App\Jobs\Branch;

use App\Mail\Branch\BranchRegistered;
use App\Models\BusinessLocation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendBranchRegisteredMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $location;

    const SERVICE = 'branch-registration';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BusinessLocation $location)
    {
        $this->location = $location;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->location->taxpayer->email){
            Mail::to($this->location->taxpayer->email)->send(new BranchRegistered($this->location));
        }
    }
}
