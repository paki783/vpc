<?php

namespace App\User;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Tournament;
use App\Division;
use App\Team;

class UserAssistant extends Model
{
    //
    protected $guarded = [];

    function getUser() {
        return $this->hasOne(User::class, "id", "user_id");
    }

    function getManager() {
        return $this->hasOne(User::class, "id", "manager_id");
    }

    function getLeague(){
        return $this->hasOne(Tournament::class, "id", "league_id");
    }

    function getDivision(){
        return $this->hasOne(Division::class, "id", "division_id");
    }

    function getTeam(){
        return $this->hasOne(Team::class, "id", "team_id");
    }
}
