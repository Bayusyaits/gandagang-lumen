<?php

namespace Modules\ShipperModule\Providers;

use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = 'Modules\ShipperModule\Http\Controllers';

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
            'prefix'    => 'shipper',
            'namespace' => $this->moduleNamespace,
            'middleware'=> 'Modules\ShipperModule\Http\Middleware\ShipperModuleMiddleware'
        ], function ($router) {
            require module_path('ShipperModule', 'Routes/api.php');
        });
    }
}