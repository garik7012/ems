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

Route::get('/', 'CoreUmsController@index');
Route::group(['prefix' => 'enterprises'], function(){
    Route::get('/', 'CoreUmsController@welcome');
    Route::get('/registration', 'CoreUmsController@registration');
    Route::post('/registration', 'CoreUmsController@create');
});
Route::post('/logout', 'Auth\LoginController@logout');

//--------------------------------   Enterprise's routes   ---------------------------------------
Route::group(['prefix' => '/e/{namespace}'], function() {
    //Auth
    Route::any('/logout', 'Security\AuthorizationController@logout')->name('logout');
    Route::get('/login', 'Security\AuthorizationController@showLoginForm');
    //email confirmation, finish registration, 2 factor authorization check confirm code
    Route::group(['prefix' => 'security'], function(){
        Route::get('/confirm/{id}/{pass}', 'Security\RegistrationController@confirmEmail');
        Route::post('/registration/end', 'Security\RegistrationController@finishRegistration');
        Route::get('/user-not-active', 'Security\AuthorizationController@userNotActive');
        Route::post('/confirm/code', 'Security\AuthorizationController@checkConfirmCode');
        Route::post('/authorization/login', 'Security\AuthorizationController@login');
    });
    //check is user belong to this enterprise, is user active, share menu according to role
    Route::group(['middleware' => ['belong', 'is.active', 'menu']], function() {
        Route::get('/', 'Enterprises\EnterpriseController@showEnterprise');
        Route::get('/user/profile', 'Enterprises\EnterpriseController@userProfile');
        Route::post('/user/profile', 'Enterprises\EnterpriseController@editUserProfile');

        Route::get('/user/loginAsUser/{id}', 'Enterprises\EnterpriseController@loginAsUser')->middleware('is.admin');
        Route::get('/user/list/gback', 'Enterprises\EnterpriseController@backToAdmin');

    //call module\controller->action according to route /{module}/{controller}/{action}
        Route::any('/{module}/{controller}/{action}/{parametr?}', 'CoreUmsController@callActionUrl')->middleware('roles');

//        Route::get('/enterprises/departments/showlist', 'Enterprises\DepartmentsController@showList');
//        Route::get('/enterprises/branches/showlist', 'Enterprises\BranchesController@showList');
//        Route::get('/enterprises/positions/showlist', 'Enterprises\PositionsController@showList');
        //        Route::group(['prefix' => '/users'], function (){
//            Route::get('/dashboard/show', 'Users\DashboardController@show');
//        });
        //Routes for superadmin only
//        Route::group(['middleware' => 'is.admin'], function (){
//            Route::post('security/registration/usercreate', 'Security\RegistrationController@createUserByAdmin')->middleware(['is.admin', 'menu']);
//            Route::get('/enterprises/departments/create', 'Enterprises\DepartmentsController@create');
//            Route::get('enterprises/branches/create', 'Enterprises\BranchesController@create');
//            Route::get('enterprises/positions/create', 'Enterprises\PositionsController@create');
//            Route::get('/enterprises/settings/getsecurity', 'Enterprises\SettingsController@getEnterpriseSecuritySettings');
//            Route::post('/enterprises/settings/setSecurity', 'Enterprises\SettingsController@setEnterpriseSecuritySettings');
//            Route::get('/enterprises/enterprise/createuser', 'Enterprises\EnterpriseController@createUser');
//            Route::get('/enterprises/enterprise/showusers', 'Enterprises\EnterpriseController@showUsers');//
//            Route::get('/user/deactivate/{id}', 'Security\AuthorizationController@deactivateUser');
//            Route::get('/user/activate/{id}', 'Security\AuthorizationController@activateUser');
//        });
    });
});


//Auth::routes();
/*
      // Authentication Routes...
        $this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
        $this->post('login', 'Auth\LoginController@login');


        // Registration Routes...
        $this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
        $this->post('register', 'Auth\RegisterController@register');

        // Password Reset Routes...
        $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
        $this->post('password/reset', 'Auth\ResetPasswordController@reset');
 */