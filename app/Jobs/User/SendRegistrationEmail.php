<?php

namespace App\Jobs\User;

use App\Mail\User\UserRegistration;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendRegistrationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $payload;


    /**
     * Create a new job instance.
     *
     * @param $_full_name
     * @param $_email
     * @param $_password
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::find($this->payload);
        $code = Str::random(8);
        $user->password = Hash::make($code);
        $user->save();
        Mail::to($user->email)->send(new UserRegistration($user->full_name, $user->email, $code));
    }
}
