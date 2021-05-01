<?php

namespace App\Achievement;

use Illuminate\Database\Eloquent\Model;
use App\Team;
use App\Tournament;
use App\Division;
use App\Achievement\Achievement;

class AssignAward extends Model
{
    //
    protected $guarded = [];
    public function getTeams()
    {
        return $this->hasOne(Team::class, "id", "team_id");
    }
    public function getAllTeams()
    {
        return $this->hasMany(Team::class, "id", "team_id");
    }
    public function getLeague()
    {
        return $this->hasOne(Tournament::class, "id", "league_id");
    }
    public function getDivision()
    {
        return $this->hasOne(Division::class, "id", "division_id");
    }
    public function getAward()
    {
        return $this->hasOne(Achievement::class, "id", "award_id");
    }
}
