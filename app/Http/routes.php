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
    // return $users = App\User::find(2)->group;
});

/**
 * API version 1.0 routes
 */
$api->version('v1', function ($api) {

  $api->post('hello', function(){
    return ["msg" => "Hello"];
  });

  $api->get('testing_helper', function(){
    $helper = new Helpers\TestHelper;
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
  $api->post('authenticate', 'App\Api\V1\Controllers\Auth\AuthController@authenticate');

  /**
   * Routes those need user to be authenticated
   */
  $api->group(['middleware' => 'api.auth'], function($api){

    /**
     *Token Related APIs
     */
    $api->get('refresh-token', 'App\Api\V1\Controllers\JWTTokenController@refreshToken');

    /**
     *
     * Roles and Permissions
     */
     $api->post('role/create', 'App\Api\V1\Controllers\RolesAndPermissionsController@createRole');
     $api->post('permission/create', 'App\Api\V1\Controllers\RolesAndPermissionsController@createPermission');
     $api->patch('attach/permission/{permission_id}/role/{role_id}', 'App\Api\V1\Controllers\RolesAndPermissionsController@attachPermissionToRole');
     $api->patch('attach/role/{role_id}/user/{user_id}', 'App\Api\V1\Controllers\RolesAndPermissionsController@attachRoleToUser');

     /**
      *
      * User APIs
      */
     $api->group(['middleware' => ['ability:admin|owner,manage-users']], function($api){

       $api->get('users', 'App\Api\V1\Controllers\UserController@index')
           ->middleware('permission:view-all-users|manage-users');

       $api->get('user/{user_id?}', 'App\Api\V1\Controllers\UserController@show')
           ->where('user_id', '[0-9]+')
           ->middleware('permission:view-user|manage-users');

       $api->post('user/create', 'App\Api\V1\Controllers\UserController@create')
           ->middleware('permission:create-user|manage-users');

       $api->post('user/{user_id}', 'App\Api\V1\Controllers\UserController@update')
           ->where('user_id', '[0-9]+')
           ->middleware('permission:edit-user|manage-users');

       $api->delete('user/{user_id}', 'App\Api\V1\Controllers\UserController@destroy')
           ->where('user_id', '[0-9]+')
           ->middleware('permission:delete-user|manage-users');

       $api->post('user/batch', 'App\Api\V1\Controllers\UserController@batchImport')
           ->middleware('permission:add-batch-users|manage-users');

     });

    /**
     *
     * Main Category(called Business) APIs
     */
    $api->group(['middleware' => ['ability:admin|owner,manage-businesses']], function($api){

      $api->get('businesses', 'App\Api\V1\Controllers\BusinessController@index')
          ->middleware('permission:view-all-businesses|manage-businesses');

      $api->get('business/{business_id}', 'App\Api\V1\Controllers\BusinessController@show')
          ->middleware('permission:view-business|manage-businesses');

      $api->post('business/create', 'App\Api\V1\Controllers\BusinessController@create')
          ->middleware('permission:create-business|manage-businesses');

      $api->post('business/{business_id}', 'App\Api\V1\Controllers\BusinessController@update')
          ->middleware('permission:edit-business|manage-businesses');

      $api->delete('business/{business_id}', 'App\Api\V1\Controllers\BusinessController@destroy')
          ->middleware('permission:delete-business|manage-businesses');
    });


     /**
      *
      * User Groups APIs
      */
     $api->group(['middleware' => ['ability:admin|owner,manage-user-groups']], function($api){

       $api->get('user-groups', 'App\Api\V1\Controllers\UserGroupsController@index')
           ->middleware('permission:view-all-user-groups|manage-user-groups');

       $api->get('user-group/{group_id}', 'App\Api\V1\Controllers\UserGroupsController@show')
           ->where('group_id', '[0-9]+')
           ->middleware('permission:view-user-group|manage-user-groups');

       $api->post('user-group/create', 'App\Api\V1\Controllers\UserGroupsController@create')
           ->middleware('permission:create-user-group|manage-user-groups');

       $api->post('user-group/{group_id}', 'App\Api\V1\Controllers\UserGroupsController@update')
           ->where('group_id', '[0-9]+')
           ->middleware('permission:edit-user-group|manage-user-groups');

       $api->delete('user-group/{group_id}', 'App\Api\V1\Controllers\UserGroupsController@destroy')
           ->where('group_id', '[0-9]+')
           ->middleware('permission:delete-user-group|manage-user-groups');
     });

  });
});
