<?php

namespace App\Providers;

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
    }
}
