<?php

namespace App\Jobs;

use App\Mail\TwoFactorAuth;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOTPEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $code;
    private $email;
    private $username;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($_code, $_email, $_username)
    {
        $this->code = $_code;
        $this->email = $_email;
        $this->username = $_username;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new TwoFactorAuth($this->code, $this->username));
    }
}
