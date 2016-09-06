<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Role;
use App\User;
use App\Permission;

class RolesAndPermissionsController extends Controller
{
    /**
     * Creating basic roles - Owner and Admin to get started
     * @return json
     */
    function basicRoles(){
      if(! $ownerExist = Role::where('name', 'owner')->first()) {
        $owner = new Role();
        $owner->name         = 'owner';
        $owner->display_name = 'Project Owner'; // optional
        $owner->description  = 'User is the owner of a given project'; // optional
        $owner->save();
      }

      if(! $adminExist = Role::where('name', 'admin')->first()) {
        $admin = new Role();
        $admin->name         = 'admin';
        $admin->display_name = 'User Administrator'; // optional
        $admin->description  = 'User is allowed to manage and edit other users'; // optional
        $admin->save();
      }

      return $this->response->array(["result" => "Basic roles created!!"]);
    }

    /**
     * Creating basic permissions -
     * @return json
     */
    function basicPermissions(){

      if(Permission::whereIn('name', ['create-user', 'edit-user', 'delete-user', 'view-all-users', 'handle-users'])->count() == 0) {
        $owner = Role::where('name', 'owner')->first();
        $admin = Role::where('name', 'admin')->first();

        $createUser = new Permission;
        $createUser->name         = 'create-user';
        $createUser->display_name = 'Create Users'; // optional
        // Allow a user to...
        $createUser->description  = 'Create new user'; // optional
        $createUser->save();

        $editUser = new Permission();
        $editUser->name         = 'edit-user';
        $editUser->display_name = 'Edit Users'; // optional
        // Allow a user to...
        $editUser->description  = 'edit existing users'; // optional
        $editUser->save();

        $deleteUser = new Permission();
        $deleteUser->name         = 'delete-user';
        $deleteUser->display_name = 'Delete Users'; // optional
        // Allow a user to...
        $deleteUser->description  = 'delete users'; // optional
        $deleteUser->save();

        $viewAllUsers = new Permission();
        $viewAllUsers->name         = 'view-all-users';
        $viewAllUsers->display_name = 'View All Users'; // optional
        // Allow a user to...
        $viewAllUsers->description  = 'view all users'; // optional
        $viewAllUsers->save();

        $handleUser = new Permission();
        $handleUser->name         = 'handle-users';
        $handleUser->display_name = 'Handle Users'; // optional
        // Allow a user to...
        $handleUser->description  = 'handle all users'; // optional
        $handleUser->save();

        try{
          $admin->attachPermissions([$createUser, $editUser, $viewAllUsers]);
          $owner->attachPermission($handleUser);
        } catch(\Exception $e){
          return $e;
        }

      }

      return $this->response->array(["result" => "Basic permissions created!!"]);
    }

    /**
     * Common method containing all logic to create role/permission.
     * @param  array $fields
     * @param  string $type
     * @return json
     */
    private function create($fields, $type){

      switch ($type) {
        case 'role':
          $role = new Role;
          $role->fill($fields);
          $role->save();
          return $role;
          break;

        case 'permission':
          $permission = new Permission;
          $permission->fill($fields);
          $permission->save();
          return $permission;
          break;

        default:
          //throw new Exception("Can't create $type");
          break;
      }
    }

    /**
     * Method to create a role
     * @param  Request $request
     * @return json
     */
    function createRole(Request $request){
      $input = $request->input();
      try{
        if($role = $this->create($input, 'role')){
          return $this->response->array(["status" => true, "message" => "Role Created!!", "data" => $role]);
        }
      } catch(\Exception $e){
        return $this->response->error($e->getMessage(), 400);
      }

    }

    /**
     * Method to create a permission
     * @param  Request $request
     * @return json
     */
    function createPermission(Request $request){
      $input = $request->input();
      try{
        if($permission = $this->create($input, 'permission')){
          return $this->response->array(["status" => true, "message" => "Permission Created!!", "data" => $permission]);
        }
      } catch(\Exception $e) {
          return $this->response->error($e->getMessage(), 400);
      }
    }

    /**
     * Method to attach a permission to a role.
     * @param  integer $permission_id
     * @param  integer $role_id
     * @return json
     */
    function attachPermissionToRole($permission_id, $role_id){
      $role = Role::find($role_id);
      try{
        $role->attachPermission($permission_id);
      } catch(\PDOException $e){
        $err_code = $e->getCode();
        if($err_code == 23000)
          return $this->response->error("Relation already exist!!", 400);

        return $this->response->error("Something went wrong!!", 400);
      }

      return $this->response->array([
        "status" => true,
        "message" => "Permission #$permission_id attached to Role #$role_id"
      ]);
    }

    /**
     * Method to attach a role to a particular user
     * @param  integer $role_id
     * @param  integer $user_id
     * @return json
     */
    function attachRoleToUser($role_id, $user_id){
      $user = User::find($user_id);
      try{
        $user->attachRole($role_id);
      } catch(\PDOException $e) {
        $err_code = $e->getCode();
        if($err_code == 23000)
          return $this->response->error("Relation already exist!!", 400);

        return $this->response->error("Something went wrong!!", 400);
      }
      return $this->response->array([
        "status" => true,
        "message" => "Role #$role_id attached to User #$user_id"
      ]);
    }
}
