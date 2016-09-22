<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    protected $table = "users_groups";
    protected $fillable = ['name', 'slug', 'business_id'];

    function business(){
      return $this->belongsTo('App\Models\Business');
    }

    function users(){
      return $this->hasMany('App\User', 'id', 'group_id');
    }
}
