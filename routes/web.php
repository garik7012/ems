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
    Route::get('/user-not-active', 'Security\AuthorizationController@userNotActive');
});
Route::post('/logout', 'Auth\LoginController@logout');

//--------------------------------   Enterprise's routes   ---------------------------------------
Route::group(['prefix' => config('ems.prefix') . "{namespace}", 'middleware' => 'ent.active'], function () {
    //Auth
    Route::any('/logout', 'Security\AuthorizationController@logout')->name('logout');
    Route::get('/login', 'Security\AuthorizationController@showLoginForm');
    //Logo
    Route::get('/logo/{ent_id}', 'Tools\FilesController@getLogo');
    //get user avatar
    Route::get('/user/avatar', 'Tools\FilesController@getAvatar');
    //Signup
    Route::any('/register', 'Security\RegistrationController@registerNewUser');
    //email confirmation, finish registration, 2 factor authorization, check confirm code, force change password
    Route::group(['prefix' => 'security'], function () {
        Route::get('/confirm/{id}/{pass}', 'Security\RegistrationController@confirmEmail');
        Route::post('/registration/end', 'Security\RegistrationController@finishRegistration');
        Route::post('/authorization/login', 'Security\AuthorizationController@login');
        Route::post('/confirm/code', 'Security\AuthorizationController@checkConfirmCode');
        Route::get('/image', 'Tools\FilesController@getImage');
        //Reset password routes
        Route::get('/forgotPassword', 'Security\ResetPasswordController@showForm');
        Route::post('/sendResetLink', 'Security\ResetPasswordController@sendResetLink');
        Route::get('/reset/{user_id}/{token}', 'Security\ResetPasswordController@showResetForm');
        Route::post('/reset', 'Security\ResetPasswordController@reset');
        Route::post('/sendCode', 'Security\ResetPasswordController@sendSMSCode');
        Route::post('/checkCode', 'Security\ResetPasswordController@checkCode');
    });
    /*check is user belong to this enterprise, is user active,
     *share menu according to roles, supervisors, users_and_controllers
    */
    Route::group(['middleware' => ['belong', 'firewall', 'is.active', 'menu']], function () {
        Route::get('/', 'Enterprises\EnterpriseController@showEnterprise')->middleware('pwd.change');
        Route::get('/user/changePassword', 'Security\RegistrationController@showChangePasswordForm');
        Route::post('/user/changePassword', 'Security\RegistrationController@changePassword');
        Route::any('/user/selectCategories', 'Security\RegistrationController@selectCategories');
        Route::post('/user/changeCategories', 'Security\RegistrationController@changeUserCategories');

        Route::get('/user/list/gback', 'Enterprises\EnterpriseController@backToAdmin');

        Route::get('/ext/goToExt/{ext_id}', 'Enterprises\ExternalOrganizationsController@goToExt')->middleware('is.admin');
        Route::post('/user/makeSuperadmin', 'Users\SettingsController@makeSuperadmin')->middleware('is.admin');
        Route::post('/user/depriveSuperadmin', 'Users\SettingsController@depriveSuperadmin')->middleware('is.admin');

    //call module\controller->action according to route /{module}/{controller}/{action}
        Route::any('/{module}/{controller}/{action}/{parametr?}', 'CoreController@callActionUrl')
            ->middleware(['roles', 'pwd.change', 'log']);
    });
});
