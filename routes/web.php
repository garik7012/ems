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
});

    Route::get('/e/{namespace}/login', 'Enterprises\EnterpriseController@loginEnterprise');
    Route::get('/e/{namespace}', 'Enterprises\EnterpriseController@showEnterprise')->middleware('belong');
Route::group(['prefix' => '/e/{namespace}', 'middleware' => ['belong', 'is.admin']], function() {
    Route::get('/departments/create', 'Enterprises\DepartmentsController@create');
    Route::get('/branches/create', 'Enterprises\BranchesController@create');
    Route::get('/security', 'Enterprises\SettingsController@getEnterpriseSecuritySettings');
    Route::post('/security', 'Enterprises\SettingsController@setEnterpriseSecuritySettings');
    Route::get('/user/create', 'Enterprises\EnterpriseController@createUser');
});