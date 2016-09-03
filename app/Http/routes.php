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

$api = app('Dingo\Api\Routing\Router');

Route::get('/', function () {
    return view('welcome');
});

$api->version('v1', function ($api) {
  $api->post('hello', function(){
    return ["msg" => "Hello"];
  });

  $api->post('authenticate', 'App\Http\Controllers\Auth\AuthController@authenticate');
});

$api->version('v1', ['middleware' => 'api.auth'], function ($api) {
  $api->get('users', 'App\Http\Controllers\UserController@getAllUsers');
  $api->get('user', 'App\Http\Controllers\UserController@getUser');
  $api->get('refresh-token', 'App\Http\Controllers\UserController@refreshToken');
});
