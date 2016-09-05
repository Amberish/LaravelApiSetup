<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Auth\AuthController;
use App\User;
use JWTAuth;

class UserController extends Controller
{
    public function index(){
      return User::all();
    }

    public function show(){
      try {
        $user = JWTAuth::parseToken()->toUser();
        if(!$user){
          return $this->response->errorNotFound('User not found!!');
        }
      } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
        return $this->response->error('Token is invalid!!');
      }

      return $this->response->array($user->toArray());
    }
}
