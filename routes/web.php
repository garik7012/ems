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

Route::get('/', 'CoreController@index');
Route::group(['prefix' => 'enterprises'], function () {
    Route::get('/', 'CoreController@welcome');
    Route::get('/registration', 'CoreController@registration');
    Route::post('/registration', 'CoreController@create');
    Route::get('/goToEnterprise/{enterprise_id}', 'CoreController@goToEnterprise');
});
Route::post('/logout', 'Auth\LoginController@logout');

//--------------------------------   Enterprise's routes   ---------------------------------------
Route::group(['prefix' => config('ems.prefix') . "{namespace}"], function () {
    //Auth
    Route::any('/logout', 'Security\AuthorizationController@logout')->name('logout');
    Route::get('/login', 'Security\AuthorizationController@showLoginForm');
    //email confirmation, finish registration, 2 factor authorization, check confirm code, force change password
    Route::group(['prefix' => 'security'], function () {
        Route::get('/confirm/{id}/{pass}', 'Security\RegistrationController@confirmEmail');
        Route::post('/registration/end', 'Security\RegistrationController@finishRegistration');
        Route::post('/authorization/login', 'Security\AuthorizationController@login');
        Route::post('/confirm/code', 'Security\AuthorizationController@checkConfirmCode');
        Route::get('/user-not-active', 'Security\AuthorizationController@userNotActive');
    });

    //check is user belong to this enterprise, is user active, share menu according to role
    Route::group(['middleware' => ['belong', 'is.active', 'menu']], function () {
        Route::get('/', 'Enterprises\EnterpriseController@showEnterprise')->middleware('pwd.change');
        Route::get('/user/changePassword', 'Security\RegistrationController@showChangePasswordForm');
        Route::post('/user/changePassword', 'Security\RegistrationController@changePassword');

        Route::get('/user/list/gback', 'Enterprises\EnterpriseController@backToAdmin');

        Route::post('/user/makeSuperadmin', 'Users\SettingsController@makeSuperadmin')->middleware('is.admin');
        Route::post('/user/depriveSuperadmin', 'Users\SettingsController@depriveSuperadmin')->middleware('is.admin');

    //call module\controller->action according to route /{module}/{controller}/{action}
        Route::any('/{module}/{controller}/{action}/{parametr?}', 'CoreController@callActionUrl')->middleware(['roles', 'pwd.change']);
    });
});
