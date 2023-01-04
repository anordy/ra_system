<?php

namespace App\Jobs\User;

use App\Mail\TooManyLoginAttemptsMAIL;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class TooManyLoginAttempts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $payload;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        //
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        if ($this->payload['email']) {
            Mail::to($this->payload['email'])->send(new TooManyLoginAttemptsMAIL($this->payload));
        } elseif ($this->payload['phone']) {
            Log::error("User Information MAIL: { $this->payload['email'] } Invalid Email");
        }
    }
}
