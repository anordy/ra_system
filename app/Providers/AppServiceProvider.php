<?php

namespace App\Providers;

use App\Rules\AlphaNumSpaceRule;
use App\Rules\AlphaGenericRule;
use App\Rules\AlphaSpaceRule;
use App\Rules\ArrayNumberRule;
use App\Rules\NidaRule;
use App\Rules\ThousandSeparator;
use App\Rules\ValidPdfContent;
use App\Rules\ValidPhoneNo;
use Livewire\Livewire;
use App\Rules\StripTag;
use App\Services\Captcha\Captcha;
use Illuminate\Pagination\Paginator;
use App\Services\LivewireModal\Modals;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Rules\MaxFileNameLengthRule;
use App\Http\Middleware\Check2FA;

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
        Validator::extend(StripTag::handle(), StripTag::class);
        Validator::extend(ThousandSeparator::handle(), ThousandSeparator::class);
        Validator::extend(NidaRule::handle(), NidaRule::class);
        Validator::extend(ValidPhoneNo::handle(), ValidPhoneNo::class);
        Validator::extend(AlphaSpaceRule::handle(), AlphaSpaceRule::class);
        Validator::extend(AlphaNumSpaceRule::handle(), AlphaNumSpaceRule::class);
        Validator::extend(ArrayNumberRule::handle(), ArrayNumberRule::class);
        Validator::extend(ValidPdfContent::handle(), ValidPdfContent::class);
        Validator::extend(AlphaGenericRule::handle(), AlphaGenericRule::class);
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

	Livewire::addPersistentMiddleware([
            Check2FA::class
        ]);
    }
}
