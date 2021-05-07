<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Team;
use App\VPCSystems;
use App\PlayerStatistic;
use App\User\UserAssistant;

class Contract extends Model
{
    //
    protected $guarded = [];
    protected $table = "contracts";
    function getUser(){
        return $this->hasOne(User::class, "id", "user_id");
    }
    function assistant(){
        return $this->hasOne(UserAssistant::class, "user_id", "user_id");
    }
    function getManager(){
        return $this->hasOne(User::class, "id", "manager_id");
    }
    function getTeam(){
        return $this->hasOne(Team::class, "id", "team_id");
    }
    function getVPCSystem(){
        return $this->hasOne(VPCSystems::class, "id", "vpc_system_id");
    }
    function getLeague(){
        return $this->hasOne(Tournament::class, "id", "league_id");
    }
    function getDivision(){
        return $this->hasOne(Division::class, "id", "division_id");
    }
}
