<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $fillable = ['name'];

    function userGroups(){
      $this->hasMany(\App\Models\UserGroup);
    }

    function topics(){
      $this->hasMany(\App\Models\Topic);
    }
}
