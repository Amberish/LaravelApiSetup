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

  //Cache driver need to be set to array, if it is file if this link doen't work
  $api->get('test', function(){
    $user = App\User::find(1);
    try{
      $res = $user->getRoles('owner');
    } catch(\Exception $e){
      return $e;
    }
  });

  /**
   * Routes where user don't need to be authenticated.
   */
  $api->post('authenticate', 'App\Http\Controllers\Auth\AuthController@authenticate');

  /**
   * Routes those need user to be authenticated
   */
  $api->group(['middleware' => 'api.auth'], function($api){

    /**
     *Token Related APIs
     */
    $api->get('refresh-token', 'App\Http\Controllers\JWTTokenController@refreshToken');

    $api->get('basic-roles', 'App\Http\Controllers\RolesAndPermissionsController@basicRoles');
    $api->get('basic-permissions', 'App\Http\Controllers\RolesAndPermissionsController@basicPermissions');

    /**
     *
     * Roles and Permissions
     */


    /**
     *
     * Main Category(called Business) APIs
     */
    $api->group(['middleware' => ['permission:handle-users']], function($api){
      $api->get('businesses', 'App\Http\Controllers\BusinessController@index');
      $api->get('business/{business_id}', 'App\Http\Controllers\BusinessController@show');
      $api->post('business', 'App\Http\Controllers\BusinessController@create');
      $api->put('business/{business_id}', 'App\Http\Controllers\BusinessController@update');
      $api->delete('business/{business_id}', 'App\Http\Controllers\BusinessController@destroy');
    });

    /**
     *
     * User Related APIs
     */
    $api->get('users', 'App\Http\Controllers\UserController@index');
    $api->get('user', 'App\Http\Controllers\UserController@show');


    $api->get('roles', function(){
      $roles = App\Models\Role::all();
      return $roles;
    });
  });
});
