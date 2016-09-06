<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Business;

class BusinessController extends Controller
{
    /**
     * Method to fetch all Businesses
     * @return json
     */
    function index(){
      return $this->response->array([
        "status" => "true",
        "data" => Business::all()
      ], 200);
    }

    /**
     * Method to show individual business.
     * @param  integer $id
     * @return json
     */
    function show($id){
      return $this->response->array([
        "status" => "true",
        "data" => Business::find($id)
      ], 200);
    }

    /**
     * Method to create a business
     * @param  Request $request
     * @return json
     */
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

    /**
     * Method to update business detail
     * @param  Request $request
     * @param  integer  $id
     * @return json
     */
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

    /**
     * Method to delete a business.
     * @param  integer $id
     * @return json
     */
    function destroy($id){
      $business = Business::find($id);

      $business->delete();

      return $this->response->array([
        "status" => "true",
        "data" => $business->name . " is deleted!!"
      ], 200);
    }
}
