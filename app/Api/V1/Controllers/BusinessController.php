<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Business;

class BusinessController extends Controller
{
    function index(){
      return $this->response->array([
        "status" => "true",
        "data" => Business::all()
      ], 200);
    }

    function show($id){
      return $this->response->array([
        "status" => "true",
        "data" => Business::find($id)
      ], 200);
    }

    function create(Request $request){
      $input = $request->input();

      $business = new Business;
      $business->fill($input);
      $business->save();

      return $this->response->array([
        "status" => "true",
        "data" => $business
      ], 200);
    }

    function update(Request $request, $id){
      $business = Business::find($id);

      $input = $request->input();
      $business->fill($input);
      $business->save();

      return $this->response->array([
        "status" => "true",
        "data" => $business
      ], 200);
    }

    function destroy($id){
      $business = Business::find($id);

      $business->delete();

      return $this->response->array([
        "status" => "true",
        "data" => $business->name . " is deleted!!"
      ], 200);
    }
}
