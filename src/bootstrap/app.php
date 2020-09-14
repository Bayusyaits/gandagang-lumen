<?php

require_once __DIR__.'/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

$app->instance('path.config', app()->basePath() . DIRECTORY_SEPARATOR . 'config');
$app->instance('path.storage', app()->basePath() . DIRECTORY_SEPARATOR . 'storage');
$app->instance('path.public', app()->basePath() . DIRECTORY_SEPARATOR . 'public');

/**
 * use notification
 */
$app->withFacades(true, [
    Illuminate\Support\Facades\Notification::class => 'Notification',
]);

// enable Eloquent
$app->withEloquent();

$app->bind(\Illuminate\Contracts\Routing\UrlGenerator::class, function ($app) {
    return new \Laravel\Lumen\Routing\UrlGenerator($app);
});

/**
 * how to use module
 * @link https://nwidart.com/laravel-modules/v2/lumen
 * ---------------------------------------------------------
 * load auth config files
 * ----------------------------------------------------------
 * load queue config files
 * @link https://lumen.laravel.com/docs/6.x/queues
 * -----------------------------------------------------------
 * how to use debugbar
 * @link https://github.com/barryvdh/laravel-debugbar
 * -----------------------------------------------------------
 *  configure email
 * @link https://lumen.laravel.com/docs/6.x/mail
 */
$app->configure('modules');
$app->configure('auth');
$app->configure('database');
$app->configure('queue');
$app->configure('mail');
$app->configure('sentry');
$app->alias('mailer', Illuminate\Mail\Mailer::class);
$app->alias('mailer', Illuminate\Contracts\Mail\Mailer::class);
$app->alias('mailer', Illuminate\Contracts\Mail\MailQueue::class);
$app->configure('elasticsearch');
$app->configure('debugbar');

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/
 
/**
 * enable cors
 * @link https://www.codementor.io/chiemelachinedum/steps-to-enable-cors-on-a-lumen-api-backend-e5a0s1ecx
 * ------------------------------------------------------------------------
 * elasticquent
 * @link https://appdividend.com/2018/06/30/laravel-elasticsearch-tutorial-example/
 */

$app->routeMiddleware([
    'auth' => Modules\AuthModule\Http\Middleware\AuthModuleMiddleware::class,
    'cors' => App\Http\Middleware\CorsMiddleware::class,
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

/**
 *
 * lumen generator
 * @link https://github.com/flipboxstudio/lumen-generator
 * ----------------------------------------------------------------
 * how to use modules
 * @link https://nwidart.com/laravel-modules/v2/basic-usage/creating-a-module
 * ----------------------------------------------------------------
 * how to use redis
 * @link https://thewebtier.com/laravel/getting-started-redis-lumen/
 * -----------------------------------------------------------------
 * how to use elasticSearch
 * @link https://github.com/cviebrock/laravel-elasticsearch
 * ------------------------------------------------------------------
 * how to use redis
 * @link https://www.cloudways.com/blog/redis-for-queuing-in-laravel-5/
 * ------------------------------------------------------------------
 * how to use notification mail
 * @link https://medium.com/@hfally/how-to-make-notifications-in-lumen-laravel-87b9956c6bd2
 */

$app->register(\Nwidart\Modules\LumenModulesServiceProvider::class);
$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);
$app->register(App\Providers\EventServiceProvider::class);
$app->register(Illuminate\Redis\RedisServiceProvider::class);
$app->register(Illuminate\Mail\MailServiceProvider::class);
$app->register(Cviebrock\LaravelElasticsearch\ServiceProvider::class);
$app->register(Illuminate\Notifications\NotificationServiceProvider::class);
$app->register(Sentry\Laravel\ServiceProvider::class);
if (env('APP_DEBUG')) {
    $app->register(Barryvdh\Debugbar\LumenServiceProvider::class);
}
if (env('APP_ENV') === 'production') {
    $app->register(BeyondCode\DumpServer\DumpServerServiceProvider::class);
    $app->register(Flipbox\LumenGenerator\LumenGeneratorServiceProvider::class);
}

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/
foreach (glob(__DIR__.'/../app/Annotations/*.php') as $annotation) {
    require_once $annotation;
}
$app->router->options(
    '/{any:.*}',
    [
        'middleware' => ['App\Http\Middleware\CorsMiddleware'],
        function () {
            return response(['status' => 'success']);
        }
    ]
);

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/api.php';
});

return $app;
