<?php

namespace App\Jobs\Vfms;

use App\Mail\Vetting\ToCorrectionReturn;
use App\Mail\Vfms\ClientNotification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ClientNotificationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const SERVICE = 'vfms-client-notification-mail';
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
        try {
            if ($this->payload['user_type'] == 'taxpayer'){
                Mail::to($this->payload['email'])->send(new ClientNotification($this->payload));
            } else {
                $admin_role = Role::where('name', 'Administrator')->get()->first();
                if ($admin_role) {
                    $administrators = User::where('role_id', $admin_role->id)->where('status', true)->get();
                    if (count($administrators) > 0) {
                        foreach ($administrators as $admin) {
                            if ($admin->email) {
                                $this->payload['user_name'] = $admin->fname;
                                Mail::to($admin->email)->send(new ClientNotification($this->payload));
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e){
            Log::error($e);
        }
    }
}
