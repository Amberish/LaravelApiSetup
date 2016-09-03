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

/**
 * API version 1.0 routes
 */
$api->version('v1', function ($api) {
  $api->post('hello', function(){
    return ["msg" => "Hello"];
  });

  /**
   * Routes where user don't need to be authenticated.
   */
  $api->post('authenticate', 'App\Http\Controllers\Auth\AuthController@authenticate');

  /**
   * Routes those need user to be authenticated
   */
  $api->group(['middleware' => 'api.auth'], function($api){
    $api->get('users', 'App\Http\Controllers\UserController@getAllUsers');
    $api->get('user', 'App\Http\Controllers\UserController@getUser');
    $api->get('refresh-token', 'App\Http\Controllers\UserController@refreshToken');
  });
});
