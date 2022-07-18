<?php

namespace App\Listeners;

use Exception;
use Carbon\Carbon;
use App\Models\Audit;
use Illuminate\Support\Facades\Log;

class AuthenticationFired
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle user login events.
     */
    public function handleUserLogin($event) {
        $data = [
            'auditable_id' => auth()->user()->id,
            'event'      => Audit::LOGGED_IN,
            'url'        => request()->fullUrl(),
            'ip_address' => request()->getClientIp(),
            'user_agent' => request()->userAgent(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'user_id'    => auth()->user()->id,
            'user_type' => "App\Models\User",
            'auditable_type' => "App\Models\User",
        ];


        try {
            Audit::create($data);
        } catch(Exception $e){
            Log::error($e);
        }
    }
 
    /**
     * Handle user logout events.
     */
    public function handleUserLogout($event) {
        $data = [
            'auditable_id' => auth()->user()->id,
            'event'      => Audit::LOGGED_OUT,
            'url'        => request()->fullUrl(),
            'ip_address' => request()->getClientIp(),
            'user_agent' => request()->userAgent(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'user_id'    => auth()->user()->id,
            'user_type' => "App\Models\User",
            'auditable_type' => "App\Models\User",
        ];


        try {
            Audit::create($data);
        } catch(Exception $e){
            Log::error($e);
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\AuthenticationFired@handleUserLogin'
        );
 
        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'App\Listeners\AuthenticationFired@handleUserLogout'
        );
    }
}
