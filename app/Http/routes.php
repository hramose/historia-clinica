<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route:
get('/', 'FrontController@index');

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', ['as' => 'auth/login', 'uses' => 'Auth\AuthController@postLogin']);
Route::get('auth/logout', ['as' => 'auth/logout', 'uses' => 'Auth\AuthController@getLogout']);

/*Route::group(['middleware' => 'auth'], function(){*/
// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', ['as' => 'auth/register', 'uses' => 'Auth\AuthController@postRegister']);
/*});*/

// Verify email routes
Route::get('auth/verify/{confirmationCode}', [
    'as' => 'confirmation_path',
    'uses' => 'Auth\AuthController@getConfirmation'
]);

Route::get('auth/sendverificationmail', [
    'as' => 'sendverificationmail',
    'uses' => 'Auth\AuthController@getSendVerificationMail'
]);

Route::post('auth/sendverificationmail', [
    'as' => 'sendverificationmail',
    'uses' => 'Auth\AuthController@getSendVerificationMail'
]);

Route::get('auth/reset_password', [
    'as' => 'reset_password',
    'uses' => 'Auth\AuthController@getResetPassword'
]);

Route::post('auth/reset_password', [
    'as' => 'reset_password',
    'uses' => 'Auth\AuthController@getResetPassword'
]);

Route::group(['prefix' => 'pacients'], function () {
    Route::get('/', 'PatientController@index');
});

Route::group(['prefix' => 'histories'], function () {
    Route::get('/', 'HistoryController@index');
});

Route::get('crear_menus', 'FrontController@getFormMenu');

Route::post('crear_menu', [
    'as' => 'crear_menu',
    'uses' => 'FrontController@postCreateMenu']);