<?php

namespace Modules\WarehouseModule\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class WarehouseModuleServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('WarehouseModule', 'Database/Migrations'));
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
            module_path('WarehouseModule', 'Config/config.php') => config_path('warehousemodule.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('WarehouseModule', 'Config/config.php'),
            'warehousemodule'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/warehousemodule');

        $sourcePath = module_path('WarehouseModule', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/warehousemodule';
        }, \Config::get('view.paths')), [$sourcePath]), 'warehousemodule');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/warehousemodule');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'warehousemodule');
        } else {
            $this->loadTranslationsFrom(module_path('WarehouseModule', 'Resources/lang'), 'warehousemodule');
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
            app(Factory::class)->load(module_path('WarehouseModule', 'Database/factories'));
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
