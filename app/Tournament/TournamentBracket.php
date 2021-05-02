<?php

namespace App\Tournament;

use Illuminate\Database\Eloquent\Model;
use App\Tournament\TournamentTeam;
use App\Match;

class TournamentBracket extends Model
{
    //
    protected $guarded = [];

    public function getMatach()
    {
        return $this->hasMany(Match::class, "division_id", "id");
    }

    public function getTournamentGroupTeamHome()
    {
        return $this->hasOne(TournamentTeam::class, "id", "home_team");
    }
    public function getTournamentGroupTeamAway()
    {
        return $this->hasOne(TournamentTeam::class, "id", "away_team");
    }

}
