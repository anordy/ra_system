<?php

namespace App\Jobs;

use App\Mail\CustomMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCustomMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const SERVICE = 'SEND-CUSTOM-MAIL';

    public $email;
    public $payload;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $payload)
    {
        $this->email = $email;
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new CustomMail($this->payload));
    }
}
