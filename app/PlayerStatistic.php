<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Statistic;
use App\Position;
use App\Team;

class PlayerStatistic extends Model
{
    //
    protected $guarded = [];
    function getUser(){
        return $this->hasOne(User::class, "id", "user_id");
    }
    function getStatistic(){
        return $this->hasOne(Statistic::class, "id", "statistic_id");
    }
    function getPosition(){
        return $this->hasOne(Position::class, "id", "position_id");
    }
    function getTeam(){
        return $this->hasOne(Team::class, "id", "team_id");
    }
}
