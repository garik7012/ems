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

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'enterprises'], function(){
    Route::get('/', 'Enterprises\EnterpriseController@welcome');
    Route::get('/registration', 'Enterprises\EnterpriseController@registration');
    Route::post('/registration', 'Enterprises\EnterpriseController@create');
});

Route::group(['prefix' => 'security'], function(){
    Route::post('/login', 'Security\AuthorizationController@login');
    Route::get('/confirm/{id}/{pass}', 'Security\RegistrationController@confirmEmail');
    Route::post('/registration/end', 'Security\RegistrationController@finishRegistration');
    Route::get('/user-not-active', 'Security\AuthorizationController@userNotActive');
    Route::post('/confirm/code', 'Security\AuthorizationController@checkConfirmCode');
});


Route::get('/e/{namespace}/login', 'Enterprises\EnterpriseController@loginEnterprise')->middleware('menu');
//Only if user is belong of this enterprise
Route::group(['prefix' => '/e/{namespace}', 'middleware' => ['belong', 'is.active', 'menu']], function() {
    Route::group(['prefix' => '/users'], function (){
        Route::get('/dashboard/show', 'Users\DashboardController@show');
    });
    Route::get('/', 'Enterprises\EnterpriseController@showEnterprise');
    Route::get('/user/profile', 'Enterprises\EnterpriseController@userProfile');
    Route::post('/user/profile', 'Enterprises\EnterpriseController@editUserProfile');
    Route::get('/departments/list', 'Enterprises\DepartmentsController@showList');
    Route::get('/branches/list', 'Enterprises\BranchesController@showList');
    Route::get('/user/list/gback', 'Enterprises\EnterpriseController@backToAdmin');
    //Routes for superadmin only
    Route::group(['middleware' => 'is.admin'], function (){
        Route::get('/departments/create', 'Enterprises\DepartmentsController@create');
        Route::get('/branches/create', 'Enterprises\BranchesController@create');
        Route::get('/security', 'Enterprises\SettingsController@getEnterpriseSecuritySettings');
        Route::post('/security', 'Enterprises\SettingsController@setEnterpriseSecuritySettings');
        Route::get('/user/create', 'Enterprises\EnterpriseController@createUser');
        Route::get('/user/list', 'Enterprises\EnterpriseController@showUsers');        
        Route::get('/user/login-as-user/{id}', 'Enterprises\EnterpriseController@loginAsUser');
        Route::get('/user/deactivate/{id}', 'Security\AuthorizationController@deactivateUser');
        Route::get('/user/activate/{id}', 'Security\AuthorizationController@activateUser');
        Route::post('/user/create', 'Enterprises\EnterpriseController@createUserByAdmin');
    });
});