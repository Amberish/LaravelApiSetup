<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Role;
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
}
