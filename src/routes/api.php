<?php
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'v1', 'middleware' => 'cors'], function () use (&$router) {

    $router->get('/notif', function () {
        return (new App\Notifications\SentNotification('bayu'))
        ->toSlack('halo');
    });
    
    $router->get('/debug-sentry', function () {
        throw new Exception('My first Sentry Errror couy!');
    });
});
