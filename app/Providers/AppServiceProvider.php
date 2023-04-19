<?php

namespace App\Providers;

use Livewire\Livewire;
use App\Rules\StripTag;
use App\Services\Captcha\Captcha;
use Illuminate\Pagination\Paginator;
use App\Services\LivewireModal\Modals;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Http\Livewire\DriversLicense\Wizard\LicenseDetailsStep;
use App\Http\Livewire\DriversLicense\Wizard\ApplicationDetailsStep;
use App\Http\Livewire\DriversLicense\Wizard\ApplicationInitialStep;
use App\Rules\MaxFileNameLengthRule;

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
        if (env('APP_ENV') === 'production') {
            \URL::forceScheme('https');
        }

        Paginator::useBootstrap();
        Livewire::component('modals', Modals::class);
        Livewire::component('drivers-license.wizard.application-initial-step', ApplicationInitialStep::class);
        Livewire::component('drivers-license.wizard.application-details-step', ApplicationDetailsStep::class);
        Livewire::component('drivers-license.wizard.license-details-step', LicenseDetailsStep::class);
        Validator::extend(StripTag::handle(), StripTag::class);
        Validator::extend('max_file_name_length', function ($attribute, $value, $parameters, $validator) {
            return (new MaxFileNameLengthRule($parameters[0]))->passes($attribute, $value);
        });
        
        Validator::replacer('max_file_name_length', function ($message, $attribute, $rule, $parameters) {
            return str_replace([':attribute', ':max_length'], [$attribute, $parameters[0]], 'The :attribute Filename is too long (maximum :max_length characters).');
        });

        $this->app->bind('captcha', function ($app) {
            return new Captcha(
                $app['Illuminate\Filesystem\Filesystem'],
                $app['Illuminate\Contracts\Config\Repository'],
                $app['Intervention\Image\ImageManager'],
                $app['Illuminate\Session\Store'],
                $app['Illuminate\Hashing\BcryptHasher'],
                $app['Illuminate\Support\Str']
            );
        });
    }
}
