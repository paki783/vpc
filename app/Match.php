<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Team;
use App\VPCSystems;
use App\Division;
use App\Match\MatchScore;
use App\Tournament;
use App\Tournament\TournamentBracket;
use App\Tournament\TournamentGroupTeam;
use App\Tournament\TournamentTeam;
use URL;

class Match extends Model
{
    //
    protected $guarded = [];

    function getTeamOne(){
        return $this->hasOne(Team::class, "id", "team_one_id");
    }

    function matchScore(){
        return $this->hasMany(MatchScore::class);
    }

    function getTeamTwo(){
        return $this->hasOne(Team::class, "id", "team_two_id");
    }
    function getLeague(){
        return $this->hasOne(Tournament::class, "id", "league_id");
    }

    function getBracketDivision(){
        return $this->hasOne(TournamentBracket::class, "id", "division_id");
    }
    function getGroupName(){
        return $this->hasOne(TournamentGroupTeam::class, "team_id", "team_one_id");
    }
    function getDivision(){
        return $this->hasOne(Division::class, "id", "division_id");
    }

    public function getTournamentGroupTeamOne()
    {
        return $this->hasOne(TournamentTeam::class, "id", "team_one_id");
    }
    public function getTournamentGroupTeamTwo()
    {
        return $this->hasOne(TournamentTeam::class, "id", "team_two_id");
    }
}
