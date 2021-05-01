<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\User\UserAssistant;

class TeamManager extends Model
{
    protected $guarded = [];
    protected $table = "team_managers";
    function getUser(){
        return $this->hasOne(User::class, "id", "user_id");
    }

    function team(){
        return $this->hasOne(Team::class, "id", "team_id");
    }

    function getAssistant(){
        return $this->hasMany(UserAssistant::class, "manager_id", "user_id");
    }
    
}
