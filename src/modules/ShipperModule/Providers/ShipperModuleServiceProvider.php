<?php

namespace Modules\ShipperModule\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class ShipperModuleServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('ShipperModule', 'Database/Migrations'));
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
            module_path('ShipperModule', 'Config/config.php') => config_path('shippermodule.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('ShipperModule', 'Config/config.php'),
            'shippermodule'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/shippermodule');

        $sourcePath = module_path('ShipperModule', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/shippermodule';
        }, \Config::get('view.paths')), [$sourcePath]), 'shippermodule');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/shippermodule');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'shippermodule');
        } else {
            $this->loadTranslationsFrom(module_path('ShipperModule', 'Resources/lang'), 'shippermodule');
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
            app(Factory::class)->load(module_path('ShipperModule', 'Database/factories'));
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
