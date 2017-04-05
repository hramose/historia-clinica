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

// Authentication routes...
Route::get('auth/login', 'Auth\LoginController@getLogin')->name('getLogin');
Route::post('auth/login', ['as' => 'postLogin', 'uses' => 'Auth\LoginController@postLogin']);
Route::get('auth/logout', ['as' => 'auth/logout', 'uses' => 'Auth\LoginController@logout']);

//Auth::routes();

Route::group(['middleware' => ['web', 'access']], function () {
    Route::get('/', [
        'as' => 'home', 'uses' => 'FrontController@index']);

    Route::get('auth/logout', 'Auth\LoginController@logout');

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

    Route::get('convert_reviews', 'FrontController@convertReviewIntoClinicalCourse')->name('app.convert_reviews');

    /*Route::group(['middleware' => 'auth'], function(){*/
    // Registration routes...
    Route::get('auth/register', 'Auth\LoginController@getRegister');
    Route::post('auth/register', ['as' => 'auth/register', 'uses' => 'Auth\LoginController@postRegister']);
    /*});*/

    // Verify email routes
    Route::get('auth/verify/{confirmationCode}', [
        'as' => 'confirmation_path',
        'uses' => 'Auth\LoginController@getConfirmation'
    ]);

    Route::get('auth/sendverificationmail', [
        'as' => 'sendverificationmail',
        'uses' => 'Auth\LoginController@getSendVerificationMail'
    ]);

    Route::post('auth/sendverificationmail', [
        'as' => 'sendverificationmail',
        'uses' => 'Auth\LoginController@getSendVerificationMail'
    ]);

    Route::get('auth/reset_password', [
        'as' => 'reset_password',
        'uses' => 'Auth\ResetPasswordController@getResetPassword'
    ]);

    Route::get('api', [
        'as' => 'apiIndex',
        'uses' => 'ApiController@getIndex'
    ]);

    Route::get('api/birthday', [
        'as' => 'apiBirthDay',
        'uses' => 'ApiController@getBirthday'
    ]);

    Route::get('api/birthday-email', [
        'as' => 'apiBirthDayEmail',
        'uses' => 'ApiController@getBirthdayEmail'
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
        'uses' => 'Auth\ResetPasswordController@postResetPassword'
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

    Route::get('birthdays/list/no_check', [
        'as' => 'birthdaysListNoCheck',
        'uses' => 'FrontController@showNextBirthdaysWoNotificationCheck'
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

        Route::post('/cerca/{term}', [
            'as' => 'pacientsSearch',
            'uses' => 'PatientController@getMatchPatients'
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
        Route::get('/pacient/{patient}',
            [
                'as' => 'valoracions.pacient.show',
                'uses' => 'ReviewController@show'
            ]);
        Route::post('/pacient/{id}',
            [
                'as' => 'valoracions.pacient.nou',
                'uses' => 'ReviewController@store'
            ]);
        Route::get('/pacient/{patient}/show/{review}',
            [
                'as' => 'valoracionsEditar',
                'uses' => 'ReviewController@show'
            ]);
        Route::get('/pacient/{id}/delete/{id_review}',
            [
                'as' => 'valoracionsEliminar',
                'uses' => 'ReviewController@destroy'
            ]);
        Route::post('/pacient/{id}/save/',
            [
                'as' => 'valoracionsGuarda',
                'uses' => 'ReviewController@store'
            ]);
    });

    Route::group(['prefix' => 'curs-clinic'], function () {
        /*Route::get('/', 'ClinicalCourseController@index');*/
        Route::get('/pacient/{patient}',
            [
                'as' => 'curso.pacient.show',
                'uses' => 'ClinicalCourseController@show'
            ]);
        Route::post('/pacient/{id}',
            [
                'as' => 'curso.pacient.nou',
                'uses' => 'ClinicalCourseController@store'
            ]);
        Route::get('/pacient/{id}/show/{id_review}',
            [
                'as' => 'cursoEditar',
                'uses' => 'ClinicalCourseController@show'
            ]);
        Route::get('/pacient/{id}/delete/{id_review}',
            [
                'as' => 'cursoEliminar',
                'uses' => 'ClinicalCourseController@destroy'
            ]);
        Route::post('/pacient/{patient}/save',
            [
                'as' => 'cursoGuarda',
                'uses' => 'ClinicalCourseController@store'
            ]);
        Route::put('/pacient/{patient}/save/{clinicalCourse}',
            [
                'as' => 'cursoActualiza',
                'uses' => 'ClinicalCourseController@update'
            ]);
        Route::delete('/pacient/{patient}/delete/{clinicalCourse}',
            [
                'as' => 'cursoElimina',
                'uses' => 'ClinicalCourseController@destroy'
            ]);
        Route::post('pacients/{term}',
            [
                'as' => 'cursoGetPacients',
                'uses' => 'ClinicalCourseController@getPacients'
            ]);
    });

    Route::get('crear_menus', 'FrontController@getFormMenu');

    Route::post('crear_menu', [
        'as' => 'crear_menu',
        'uses' => 'FrontController@postCreateMenu']);
});