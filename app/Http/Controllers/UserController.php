<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Auth\AuthController;
use App\User;
use JWTAuth;

class UserController extends AuthController
{
    public function getAllUsers(){
      return User::all();
    }

    public function getUser(){
      try {
        if(!$user = JWTAuth::parseToken()->toUser()){
          return $this->response->errorNotFound('User not found!!');
        }
      } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        return $this->response->error('Token is invalid!!');
      }

      return $user;
    }
}
