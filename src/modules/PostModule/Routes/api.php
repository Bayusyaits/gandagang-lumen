<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "api" middleware group. Now create something great!
|
*/
Route::post(
    '/',
    [
    'uses'             =>  'PostModuleController@postMessage',
    'as'               =>  'postMessage'
    ]
);
