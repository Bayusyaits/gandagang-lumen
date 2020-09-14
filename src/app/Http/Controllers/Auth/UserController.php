<?php

namespace App\Http\Controllers\Auth;

/**
 * log
 *
 * @link https://github.com/Seldaek/monolog
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function show($id)
    {
        //https://lumen.laravel.com/docs/5.3/errors
        $logger = new Logger('name');

        $logger->pushHandler(new StreamHandler(__DIR__.'/my_app.log', Logger::DEBUG));
        $logger->pushHandler(new FirePHPHandler());
        
        // You can now use your logger
        $logger->info('My logger is now ready');
        
        return User::findOrFail($id);
    }
}
