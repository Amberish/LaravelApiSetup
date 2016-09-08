<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\UserGroup;

class UserGroupsController extends Controller
{
  /**
   * Method fetches all user-groups.
   * Route has middleware setup to check who can access this route.
   * @return json
   */
  public function index(){
    $user_groups = UserGroup::all();
    return $this->response->array([
      "status" => true,
      "message" => "All Users Groups",
      "data" => $user_groups->toArray()
    ]);
  }

  /**
   * Method fetched a single user-group.
   * @param  user_is $group_id
   * @return json
   */
  public function show($group_id){
    $user_group = UserGroup::find($group_id);
    if($user_group)
      return $this->response->array([
        "status" => true,
        "message" => "User Group Found!!",
        "data" => $user_group->toArray()
      ]);

    return $this->response->errorNotFound("User Group not found!!");
  }

  /**
   * Method to create a new user-group.
   * @param  Request $request
   * @return json
   */
  function create(Request $request){
    $input = $request->input();

    $user_group = new UserGroup;
    $user_group->fill($input);
    $user_group->save();

    return $this->response->array([
      "status" => true,
      "message" => "User Group Created!!",
      "data" => $user_group->toArray()
    ]);
  }

  /**
   * Method to update a user-group.
   * @param  Request $request
   * @param  integer  $group_id
   * @return json
   */
  function update(Request $request, $group_id) {
    $input = $request->input();

    $user_group = UserGroup::find($group_id);
    $user_group->fill($input);
    $user_group->save();

    return $this->response->array([
      "status" => true,
      "message" => "User Group Updated!!",
      "data" => $user_group->toArray()
    ]);
  }

  /**
   * Method to delete a user-group
   * @param  integer $group_id
   * @return json
   */
  function destroy($group_id){
    $user_group = UserGroup::find($group_id);
    $user_group->delete();

    return $this->response->array([
      "status" => true,
      "message" => "User Group Deleted!!",
      "data" => $user_group->toArray()
    ]);
  }

}
