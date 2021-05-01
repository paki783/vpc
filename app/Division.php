<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Tournament;
use App\DivisionTeam;

class Division extends Model
{
    //
    protected $guarded = [];
    function getLeagues(){
        return $this->hasOne(Tournament::class, "id", "league_id");
    }
    function getDivisionTeams(){
        return $this->hasMany(DivisionTeam::class, "division_id", "id");
    }
}
