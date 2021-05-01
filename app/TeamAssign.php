<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Tournament;
use App\Division;
use App\Team;
use App\Seasons;

class TeamAssign extends Model
{
    //
    protected $guarded = [];
    public function getLeagues()
    {
        return $this->hasOne(Tournament::class, "id", "league_id");
    }
    public function getDivision()
    {
        return $this->hasOne(Division::class, "id", "division_id");
    }
    public function getTeams()
    {
        return $this->hasOne(Team::class, "id", "team_id");
    }
    public function getSeasons()
    {
        return $this->hasMany(Seasons::class, "tournament_id", "league_id");
    }
    public function getAllTeams()
    {
        return $this->hasMany(Team::class, "id", "team_id");
    }
    public function getTeamManager()
    {
        return $this->hasMany(TeamManager::class, "team_id", "team_id");
    }
}
