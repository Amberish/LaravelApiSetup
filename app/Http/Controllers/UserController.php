<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Auth\AuthController;
use App\User;
use JWTAuth;

class UserController extends Controller
{
    public function getAllUsers(){
      return User::all();
    }

    public function getUser(){
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

    public function refreshToken(){
      $token = JWTAuth::getToken();
      if(!$token){
        return $this->response->errorUnauthorized("Token is not valid!!");
      }

      try {
        $refreshedToken = JWTAuth::refresh($token);
      } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
        return $this->response->error('Token is not valid!!');
      }

      return $this->response->array(['token' => $refreshedToken]);
    }
}
