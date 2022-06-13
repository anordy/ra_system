<?php

namespace App\Jobs;

use App\Mail\UserRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendRegistrationEmail implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $password;
    public $full_name;
    public $username;
    public $email;

    /**
     * Create a new job instance.
     *
     * @param $_full_name
     * @param $_username
     * @param $_email
     * @param $_password
     *
     * @return void
     */
    public function __construct($_full_name, $_username, $_email, $_password)
    {
        $this->full_name = $_full_name;
        $this->username = $_username;
        $this->password = $_password;
        $this->email = $_email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new UserRegistration($this->full_name, $this->username, $this->password));
    }
}
