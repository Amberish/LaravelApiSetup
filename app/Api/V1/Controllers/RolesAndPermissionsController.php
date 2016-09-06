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
