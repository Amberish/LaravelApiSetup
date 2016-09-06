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
    }
}
