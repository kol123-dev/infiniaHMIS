<?php

namespace App\Providers;

use App\Models\Module;
use App\Models\Setting;
use App\Observers\ModuleObserver;
use App\Observers\SettingObserver;
use App\Rules\ValidRecaptcha;
use Blade;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Mariuzzo\LaravelJsLocalization\Commands\LangJsCommand;
use Mariuzzo\LaravelJsLocalization\Generators\LangJsGenerator;
use Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('localization.js', function ($app) {
            $app = $this->app;
            $laravelMajorVersion = (int) $app::VERSION;
            $files = $app['files'];

            if ($laravelMajorVersion === 4) {
                $langs = $app['path.base'].'/app/lang';
            } elseif ($laravelMajorVersion >= 5 && $laravelMajorVersion < 9) {
                $langs = $app['path.base'].'/resources/lang';
            } elseif ($laravelMajorVersion >= 9) {
                $langs = app()->langPath();
            }
            $messages = $app['config']->get('localization-js.messages');
            $generator = new LangJsGenerator($files, $langs, $messages);

            return new LangJsCommand($generator);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // app(UrlGenerator::class)->forceScheme('https');
        Paginator::useBootstrap();
        Module::observe(ModuleObserver::class);
        Setting::observe(SettingObserver::class);
        Validator::extend('recaptcha', ValidRecaptcha::class);
        app()->useLangPath(base_path('lang'));
        Schema::defaultStringLength(191);
        Blade::if('module', function ($name, $module = null) {
            $module = $module->where('name', $name)->first();
            //            $module
            if ($module) {
                return $module->is_active;
            }

            return false;
        });
    }
}
