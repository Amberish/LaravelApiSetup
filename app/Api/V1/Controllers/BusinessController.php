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
      $businesses = Business::all();
      return $this->response->array([
        "status" => "true",
        "message" => "All Businesses!",
        "data" => $businesses
      ], 200);
    }

    /**
     * Method to show individual business.
     * @param  integer $id
     * @return json
     */
    function show($business_id){
      $business = Business::find($business_id);
      if($business)
        return $this->response->array([
          "status" => "true",
          "message" => "Businesses Found!",
          "data" => $business
        ], 200);

      return $this->response->errorNotFound("Business Not Found!");
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
    function update(Request $request, $business_id){
      $business = Business::find($business_id);

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
    function destroy($business_id){
      $business = Business::find($business_id);

      $business->delete();

      return $this->response->array([
        "status" => "true",
        "data" => $business->name . " is deleted!!"
      ], 200);
    }
}
