<?php

namespace App\Jobs;

use App\Mail\WithholdingAgentRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendWithholdingAgentRegistrationEmail implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $full_name;
    public $institution_name;
    public $email;

    /**
     * Create a new job instance.
     *
     * @param $_full_name
     * @param $_institution_name
     * @param $_email
     *
     * @return void
     */
    public function __construct($_full_name, $_institution_name, $_email)
    {
        $this->full_name = $_full_name;
        $this->institution_name = $_institution_name;
        $this->email = $_email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new WithholdingAgentRegistration($this->full_name, $this->institution_name, $this->email));
    }
}
