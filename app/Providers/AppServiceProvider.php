<?php

namespace App\Providers;

use App\Http\Livewire\DriversLicense\Wizard\ApplicationDetailsStep;
use App\Http\Livewire\DriversLicense\Wizard\ApplicationInitialStep;
use App\Http\Livewire\DriversLicense\Wizard\LicenseDetailsStep;
use Livewire\Livewire;
use App\Services\LivewireModal\Modals;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Livewire::component('modals', Modals::class);
        Paginator::useBootstrap();

        Livewire::component('drivers-license.wizard.application-initial-step', ApplicationInitialStep::class);
        Livewire::component('drivers-license.wizard.application-details-step', ApplicationDetailsStep::class);
        Livewire::component('drivers-license.wizard.license-details-step', LicenseDetailsStep::class);
    }
}
