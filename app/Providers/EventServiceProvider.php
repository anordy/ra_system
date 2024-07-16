<?php

namespace App\Providers;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Listeners\SendMailFired;
use App\Listeners\SendSmsFired;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SendMail::class => [
            SendMailFired::class,
        ],
        SendSms::class => [
            SendSmsFired::class,
        ]
    ];

    protected $subscribe = [
        'App\Listeners\AuthenticationFired',
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
    }
}
