<?php

namespace App\Jobs\Verification;

use App\Models\Role;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\Verification\FailedVerification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendFailedVerificationMail implements ShouldQueue
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
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Send email to administrators
        $admin_role = Role::where('name', 'Administrator')->get()->first();
        $administrators = User::where('role_id', $admin_role->id)->get();

        if (count($administrators) > 0) {
            foreach ($administrators as $admin) {
                if ($admin->email) {
                    Mail::to($admin->email)->send(new FailedVerification($this->payload));
                }
            }
        }
    }
}
