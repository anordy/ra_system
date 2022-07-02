<?php

namespace App\Jobs\Taxpayer;

use App\Mail\Taxpayer\Registration;
use App\Models\Taxpayer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendRegistrationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $taxpayer, $code;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($taxpayer, $code)
    {
        $this->taxpayer = $taxpayer;
        $this->code = $code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->taxpayer->email)->send(new Registration($this->taxpayer, $this->code));
    }
}
