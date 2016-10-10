<?php

$router = app('admin.router');

$router->get('/', 'HomeController@index');
//$router->get('/myUser', 'MyUserController@index');
//$router->get('/adminUser','DashboardController@index11');
$router->resource('myUser', 'MyUserController');
//$router->resource('dash','DashboardController');