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

Route::group(['middleware' => ['web', 'auth', 'access']], function () {
    Route::get('/', [
        'as' => 'home', 'uses' => 'FrontController@index']);
});

Route::group(['middleware' => ['web', 'access']], function () {

    Route::get('requests', [
        'as' => 'homepage',
        'uses' => 'FrontController@showGuestHome'
    ]);

    Route::post('requests/check_dni', [
        'as' => 'searchByDni',
        'uses' => 'PatientController@findPacientByDni'
    ]);

    Route::post('requests/nou_pacient', [
        'as' => 'requestsNewPatient',
        'uses' => 'PatientController@requestNewPatient'
    ]);

    Route::post('requests/demanar_visita', [
        'as' => 'requestNewVisit',
        'uses' => 'VisitRequestController@requestsNewVisit'
    ]);

    // Authentication routes...
    Route::get('auth/login', ['as' => 'getLogin', 'uses' => 'Auth\AuthController@getLogin']);
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

    Route::controllers([
        'password' => 'Auth\PasswordController',
    ]);

    Route::controllers([
        'api' => 'ApiController',
    ]);

    Route::get('users/llista', [
        'as' => 'llistaUsers',
        'uses' => 'FrontController@userList'
    ]);

    Route::get('users/dades/{id}', [
        'as' => 'userData',
        'uses' => 'FrontController@showUser'
    ]);

    Route::get('users/nou/', [
        'as' => 'userNou',
        'uses' => 'FrontController@create'
    ]);

    Route::post('users/nou/', [
        'as' => 'userCreate',
        'uses' => 'FrontController@store'
    ]);

    Route::post('users/dades/{id}', [
        'as' => 'userDadesUpdate',
        'uses' => 'FrontController@update'
    ]);

    Route::get('users/eliminar/{id}', [
        'as' => 'usersDelete',
        'uses' => 'FrontController@destroyUser'
    ]);

    Route::post('auth/reset_password', [
        'as' => 'reset_password',
        'uses' => 'Auth\AuthController@postResetPassword'
    ]);

    Route::get('backup/{tables?}', [
        'as' => 'backupDb',
        'uses' => 'BackupController@backup_tables'
    ]);

    Route::get('backup/download/{file}', [
        'as' => 'backupDownload',
        'uses' => 'BackupController@decryptBackup'
    ]);

    Route::get('backup/lista/all', [
        'as' => 'backupList',
        'uses' => 'BackupController@listBackups'
    ]);

    Route::get('birthdays/list', [
        'as' => 'birthdaysList',
        'uses' => 'FrontController@showNextBirthdays'
    ]);

    Route::post('birthdays/notify', [
        'as' => 'notifiedPacientsBirthday',
        'uses' => 'FrontController@notifyBirthdays'
    ]);

    Route::group(['prefix' => 'pacients'], function () {
        Route::get('/', [
            'as' => 'pacientsIndex',
            'uses' => 'PatientController@index'
        ]);

        Route::get('/nou', [
            'as' => 'pacientsNouGet',
            'uses' => 'PatientController@create'
        ]);

        Route::post('/nou', [
            'as' => 'pacientsNou',
            'uses' => 'PatientController@store'
        ]);

        Route::get('/dades/{id}', [
            'as' => 'pacientsDades',
            'uses' => 'PatientController@show'
        ]);

        Route::post('/dades/{id}', [
            'as' => 'pacientsDadesUpdate',
            'uses' => 'PatientController@update'
        ]);

        Route::get('/llista', [
            'as' => 'pacientsLlista',
            'uses' => 'PatientController@index'
        ]);

        Route::get('/eliminar/{id}', [
            'as' => 'pacientsDelete',
            'uses' => 'PatientController@destroy'
        ]);

        Route::post('/s/{term}', [
            'as' => 'pacientsListFront',
            'uses' => 'PatientController@listaFront'
        ]);
    });

    Route::group(['prefix' => 'histories'], function () {
        Route::get('/', 'HistoryController@index');
    });

    Route::group(['prefix' => 'factures'], function () {
        Route::get('/', 'HistoryController@index');

        Route::get('/llista',
            [
                'as' => 'veureBills',
                'uses' => 'BillController@index'
            ]);
        Route::get('/create',
            [
                'as' => 'ferBills',
                'uses' => 'BillController@create'
            ]);
        Route::post('/save',
            [
                'as' => 'saveBills',
                'uses' => 'BillController@store'
            ]);
        Route::post('/update/{id}',
            [
                'as' => 'updateBill',
                'uses' => 'BillController@update'
            ]);
        Route::get('/show/{id}',
            [
                'as' => 'mostrarBill',
                'uses' => 'BillController@show'
            ]);
        Route::get('/delete/{id}',
            [
                'as' => 'eliminarBill',
                'uses' => 'BillController@destroy'
            ]);
        Route::get('/bill-config',
            [
                'as' => 'urlBillInfo',
                'uses' => 'BillController@getConfig'
            ]);
        Route::post('/search/{term}',
            [
                'as' => 'urlSearch',
                'uses' => 'BillController@getClientsAndPatients'
            ]);
        Route::get('/pdf/{id}', [
            'as' => 'billPdf',
            'uses' => 'BillController@generatePdf'
        ]);
    });

    Route::group(['prefix' => 'valoracions'], function () {
        Route::get('/', 'ReviewController@index');
        Route::get('/pacient/{id}',
            [
                'as' => 'valoracions.pacient.show',
                'uses' => 'ReviewController@show'
            ]);
        Route::post('/pacient/{id}',
            [
                'as' => 'valoracions.pacient.nou',
                'uses' => 'ReviewController@store'
            ]);
    });

    Route::get('crear_menus', 'FrontController@getFormMenu');

    Route::post('crear_menu', [
        'as' => 'crear_menu',
        'uses' => 'FrontController@postCreateMenu']);
});