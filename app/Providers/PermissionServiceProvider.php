<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\SysModule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        if(Schema::hasTable('permissions') || Schema::hasTable('sys_modules')){
            Permission::get()->map(function ($permission) {
                Gate::define($permission->name, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission);
                });
            });

            // Module
            SysModule::get()->map(function ($module) {
                Gate::define($module->name, function ($user) use ($module) {
                    return $user->hasModuleTo($module);
                });
            });
        }
    }
}