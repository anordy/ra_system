<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\SysModule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.env') === 'production') {
            if (Schema::hasTable('permissions') && Schema::hasTable('sys_modules')) {
                Permission::get()->map(function ($permission) {
                    Gate::define($permission->name, function ($user) use ($permission) {
                        return $user->hasPermissionTo($permission);
                    });
                });

                // Module
                SysModule::get()->map(function ($module) {
                    Gate::define($module->code, function ($user) use ($module) {
                        return $user->hasModuleTo($module);
                    });
                });
            }
        }
    }
}