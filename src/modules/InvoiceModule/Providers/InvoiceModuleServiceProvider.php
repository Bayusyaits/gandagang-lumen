<?php

namespace Modules\InvoiceModule\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class InvoiceModuleServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('InvoiceModule', 'Database/Migrations'));
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
            module_path('InvoiceModule', 'Config/config.php') => config_path('invoicemodule.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('InvoiceModule', 'Config/config.php'),
            'invoicemodule'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/invoicemodule');

        $sourcePath = module_path('InvoiceModule', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/invoicemodule';
        }, \Config::get('view.paths')), [$sourcePath]), 'invoicemodule');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/invoicemodule');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'invoicemodule');
        } else {
            $this->loadTranslationsFrom(module_path('InvoiceModule', 'Resources/lang'), 'invoicemodule');
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
            app(Factory::class)->load(module_path('InvoiceModule', 'Database/factories'));
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
