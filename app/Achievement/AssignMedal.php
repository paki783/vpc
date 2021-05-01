<?php

namespace App\Achievement;

use Illuminate\Database\Eloquent\Model;
use App\Team;
use App\Achievement\Achievement;
use App\User;
use App\Attachment;
use App\Division;
use App\Tournament;

class AssignMedal extends Model
{
    //
    protected $guarded = [];
    protected $table = "medal_assigns";

    function getTeam(){
        return $this->hasOne(Team::class, "id", "team_id");
    }

    function getMedal(){
        return $this->hasOne(Achievement::class, "id", "medal_id");
    }

    function getallUser(){
        return $this->hasOne(User::class, "id", "user_id");
    }

    function getMedia(){
        return $this->hasOne(Attachment::class, "type_id", "medal_id")->where("type", "medal");
    }

    function getLeague(){
        return $this->hasOne(Tournament::class, "id", "league_id")->where("tournament_type", "league");
    }
    function getDivision(){
        return $this->hasOne(Division::class, "id", "division_id");
    }
}
