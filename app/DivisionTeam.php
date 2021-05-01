<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Team;
use App\Division;

class DivisionTeam extends Model
{
    //
    protected $guarded = [];
    function getTeam(){
        return $this->hasOne(Team::class, "id", "team_id");
    }
    function getDivision(){
        return $this->hasOne(Division::class, "id", "division_id");
    }
}
