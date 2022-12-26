<?php

namespace App\Providers;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Events\SendWithholdingAgentRegistrationEmail;
use App\Listeners\SendMailFired;
use App\Listeners\SendNewBusinessRegisteredNotification;
use App\Listeners\SendSmsFired;
use App\Models\ZmBill;
use App\Notifications\NewUserNotification;
use App\Observers\ZmBillObserver;
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
        ],
        SendWithholdingAgentRegistrationEmail::class => [
            SendMailFired::class
        ],
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
        ZmBill::observe(ZmBillObserver::class);
    }
}
