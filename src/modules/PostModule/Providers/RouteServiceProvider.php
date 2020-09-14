<?php

namespace Modules\PostModule\Providers;

use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = 'Modules\PostModule\Http\Controllers';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    
    public function map()
    {
        $this->mapApiRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    
    protected function mapApiRoutes()
    {
        Route::group([
            'prefix'    => 'post',
            'namespace' => $this->moduleNamespace,
            'middleware'=> 'Modules\PostModule\Http\Middleware\PostModuleMiddleware'
        ], function ($router) {
            require module_path('PostModule', 'Routes/api.php');
        });
    }
}
