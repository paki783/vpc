<?php

namespace App\Tournament;

use Illuminate\Database\Eloquent\Model;
use App\Team;
use App\Tournament;

class TournamentTeam extends Model
{
    //
    protected $guarded = [];
    public function getTournament()
    {
        return $this->hasOne(Tournament::class, "id", "tournament_id");
    }
    public function getTeam()
    {
        return $this->hasOne(Team::class, "id", "team_id");
    }
}
