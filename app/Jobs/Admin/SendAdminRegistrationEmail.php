<?php

namespace App\Jobs\Admin;

use App\Mail\Admin\AdminRegistrationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendAdminRegistrationEmail implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $payload;
    public $password;
    public $full_name;
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
    public function __construct($payload)
    {
        $this->full_name = $payload['full_name'];
        $this->password = $payload['password'];
        $this->email = $payload['email'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->email) {
            Mail::to($this->email)->send(new AdminRegistrationMail($this->full_name, $this->email, $this->password));
        }
    }
}
