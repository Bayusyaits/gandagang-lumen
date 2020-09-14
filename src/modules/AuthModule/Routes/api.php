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
    '/firewall',
    [
    'uses'             =>  'AuthModuleController@postRegistrationFirewall',
    'as'               =>  'postRegistrationFirewall'
    ]
);

Route::post(
    '/client',
    [
    'uses'             =>  'AuthModuleController@postRegistrationClient',
    'as'               =>  'postRegistrationClient'
    ]
);

Route::group([
    'middleware' => 'Modules\AuthModule\Http\Middleware\AuthModuleMiddleware'
], function ($app) {
    
    Route::post(
        '/registration',
        [
        'uses'             =>  'AuthModuleController@postRegistration',
        'as'               =>  'postRegistration'
        ]
    );
    Route::post('/check', [
        'uses'             =>  'AuthModuleController@postCheck',
        'as'               =>  'postCheck'
    ]);
    Route::post('/login', [
        'uses'             =>  'AuthModuleController@postLogin',
        'as'               =>  'postLogin'
    ]);
    Route::get('/confirmation-email/{token}', [
        'uses'             =>  'AuthModuleController@getVerificationEmail',
        'as'               =>  'getVerificationEmail'
    ]);
    Route::group(
        ['Modules\AuthModule\Http\Middleware\AuthHeaderModuleMiddleware'],
        function () {
            Route::post('/otp', [
                'uses'             =>  'AuthModuleController@postOTP',
                'as'               =>  'postOTP'
            ]);
            Route::post('/confirmation/{uri}', [
                'uses'             =>  'AuthModuleController@postVerification',
                'as'               =>  'postVerification'
            ]);
            Route::post('/refresh/verification-code/{uri}', [
                'uses'             =>  'AuthModuleController@postRefreshVerificationCode',
                'as'               =>  'postRefreshVerificationCode'
            ]);
        }
    );
    Route::post('/logout', [
        'uses'             =>  'AuthModuleController@postLogout',
        'as'               =>  'postLogout'
    ]);
    Route::post('/rest-password', [
        'uses'             =>  'AuthModuleController@postResetPassword',
        'as'               =>  'postResetPassword'
    ]);
});
