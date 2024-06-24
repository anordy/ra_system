<?php

namespace App\Jobs\Notifications;

use App\Mail\Notifications\NotifyZNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNullZnumberMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $business;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($business)
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
        if ($this->business->taxpayer->email) {
            Mail::to($this->business->taxpayer->email)
                ->send(new NotifyZNumber($this->business));
        }
    }
}
