<?php

namespace App\Providers;

use Livewire\Livewire;
use App\Services\LivewireModal\Modals;
use App\View\Components\Select2;
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
        Livewire::component('select2', Select2::class);
    }
}
