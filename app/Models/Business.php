<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $fillable = ['name'];

    function userGroup(){
      return $this->hasMany('App\Models\UserGroup');
    }

    function topics(){
      return $this->hasMany('App\Models\Topic');
    }
}
