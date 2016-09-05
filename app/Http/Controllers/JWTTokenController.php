<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use JWTAuth;

use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTTokenController extends Controller
{
    /**
     * Method to refresh a token
     * @return [type] [description]
     */
    public function refreshToken(){
      $token = JWTAuth::getToken();
      if(!$token){
        return $this->response->errorUnauthorized("Token is not valid!!");
      }

      try {
        $refreshedToken = JWTAuth::refresh($token);
      } catch (JWTException $e) {
        return $this->response->error('Token is not valid!!');
      }

      return $this->response->array(['token' => $refreshedToken]);
    }
}
