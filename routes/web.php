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

Route::group(['prefix' => '/e/{namespace}'], function() {
    Route::get('/', 'Enterprises\EnterpriseController@showEnterprise');
    Route::get('/departments/create', 'Enterprises\DepartmentsController@create');
    Route::get('/branches/create', 'Enterprises\BranchesController@create');
    Route::get('/security', 'Enterprises\SettingsController@getEnterpriseSecurity');
});