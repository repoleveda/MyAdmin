<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::group(['prefix' => 'admin','namespace' => 'Admin'],function ($router)
{
    $router->get('login', 'LoginController@showLoginForm')->name('admin.login');
    $router->post('login', 'LoginController@login');
    $router->post('logout', 'LoginController@logout');

    $router->resource('dash', 'DashboardController');
    $router->resource('dash/send', 'DashboardController@send');
//    $router->resource('myUser/test', 'MyUserController@test');

//    $router->get('adminUser', 'DashboardController@index');
});

//// 发送密码重置链接路由
//Route::get('password/email', 'Auth\PasswordController@getEmail');
//Route::post('password/email', 'Auth\PasswordController@postEmail');
//
//// 密码重置路由
//Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
//Route::post('password/reset', 'Auth\PasswordController@postReset');

