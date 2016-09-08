<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Api\Controllers\Auth\AuthController;
use App\User;
use JWTAuth;

class UserController extends Controller
{
    /**
     * Method fetches all Users.
     * Route has middleware setup to check who can access this route.
     * @return json
     */
    public function index(){
      $users = User::all();
      return $this->response->array([
        "status" => true,
        "message" => "All Users",
        "data" => $users->toArray()
      ]);
    }

    /**
     * Method fetched a single user.
     * @param  user_is $user_id [Optional parameter]
     * @return json
     */
    public function show($user_id = null){

      if($user_id != null){
        $user = User::find($user_id);
        if($user)
          return $this->response->array([
            "status" => true,
            "message" => "User Found!!",
            "data" => $user->toArray()
          ]);

        return $this->response->errorNotFound("User not found!!");
      }

      try {
        $user = JWTAuth::parseToken()->toUser();
        if(!$user)
          return $this->response->errorNotFound('User not found!!');
      } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
        return $this->response->error('Token is invalid!!');
      }

      return $this->response->array([
        "status" => true,
        "message" => "User Found!!",
        "data" => $user->toArray()
      ]);
    }

    /**
     * Method to create a new user.
     * @param  Request $request
     * @return json
     */
    function create(Request $request){
      $input = $request->input();

      $user = new User;
      $user->fill($input);

      $user['password'] = bcrypt($input['password']);
      $user->save();

      return $this->response->array([
        "status" => true,
        "message" => "User Created!!",
        "data" => $user->toArray()
      ]);
    }

    /**
     * Method to update a user.
     * @param  Request $request
     * @param  integer  $user_id
     * @return json
     */
    function update(Request $request, $user_id) {
      $input = $request->input();

      $user = User::find($user_id);
      $user->fill($input);
      $user->save();

      return $this->response->array([
        "status" => true,
        "message" => "User Updated!!",
        "data" => $user->toArray()
      ]);
    }

    /**
     * Method to delete a user
     * @param  integer $user_id
     * @return json
     */
    function destroy($user_id){
      $user = User::find($user_id);
      $user->delete();

      return $this->response->array([
        "status" => true,
        "message" => "User Deleted!!",
        "data" => $user->toArray()
      ]);
    }

    /**
     * Method to import batch users to DB
     * @param UserListImport  $import  [This class resolves the uploaded file and provide data]
     * @return json
     */
    function batchImport(\Helpers\UserListImport $import) {
      $user_list = $import->get(['first_name', 'last_name', 'email', 'phone']);
      $users_existed = [];
      $users_created = [];
      //$import->dd();
      foreach ($user_list as $item) {
        try{
          //create user
          $user = new User;
          //dd($item->toArray());
          $user->fill($item->toArray());
          $user->phone = ($item->phone)?:'';
          $user->save();
          $users_created[] = $item;
        } catch(\PDOException $e) {
          $status_code = $e->getCode();
          switch ($status_code) {
            case 23000:
              if(isset($item->first_name) && isset($item->last_name) && isset($item->email) && isset($item->phone)){
                $users_existed[] = $item;
              }else
                return $this->response->error("Input file format not valid!!", 400);
              break;
            default:
              return $this->response->error("Something went wrong!!", 400);
          }
        }
      }

      return $this->response->array([
        "status" => true,
        "message" => count($users_created) . " user(s) imported!! " . count($users_existed) . " already existed!!",
        "user_created" => $users_created,
        "user_existed" => $users_existed
      ]);
    }
}
