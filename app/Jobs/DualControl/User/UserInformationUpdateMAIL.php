<?php

namespace App\Jobs\DualControl\User;

use App\Mail\DualControl\User\UserInformationUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserInformationUpdateMAIL implements ShouldQueue
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
            Mail::to($this->payload['email'])->send(new UserInformationUpdate($this->payload));
        } else {
            Log::error("User Information Update MAIL: { $this->payload['email'] } Invalid Email");
        }
    }
}
