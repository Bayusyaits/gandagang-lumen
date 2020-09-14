<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix' => 'completing-data/fo/{userRoleType}'], function ($app) {
    Route::get(
        '/',
        [
        'uses'             =>  'UserModuleController@getCompletingData',
        'as'               =>  'getCompletingData'
        ]
    );
    Route::put(
        '/',
        [
        'uses'             =>  'UserModuleController@putCompletingData',
        'as'               =>  'putCompletingData'
        ]
    );
});
