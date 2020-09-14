<?php

namespace Modules\AgentModule\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class AgentModuleServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('AgentModule', 'Database/Migrations'));
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
            module_path('AgentModule', 'Config/config.php') => config_path('agentmodule.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('AgentModule', 'Config/config.php'),
            'agentmodule'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/agentmodule');

        $sourcePath = module_path('AgentModule', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/agentmodule';
        }, \Config::get('view.paths')), [$sourcePath]), 'agentmodule');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/agentmodule');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'agentmodule');
        } else {
            $this->loadTranslationsFrom(module_path('AgentModule', 'Resources/lang'), 'agentmodule');
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
            app(Factory::class)->load(module_path('AgentModule', 'Database/factories'));
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
