<?php

namespace Modules\GlobalParamModule\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class GlobalParamModuleServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path('GlobalParamModule', 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('GlobalParamModule', 'Config/config.php') => config_path('globalparammodule.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('GlobalParamModule', 'Config/config.php'),
            'globalparammodule'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/globalparammodule');

        $sourcePath = module_path('GlobalParamModule', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/globalparammodule';
        }, \Config::get('view.paths')), [$sourcePath]), 'globalparammodule');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/globalparammodule');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'globalparammodule');
        } else {
            $this->loadTranslationsFrom(module_path('GlobalParamModule', 'Resources/lang'), 'globalparammodule');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(module_path('GlobalParamModule', 'Database/factories'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
