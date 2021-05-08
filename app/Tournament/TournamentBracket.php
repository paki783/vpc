<?php

namespace App\Tournament;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use App\Tournament\TournamentTeam;
use App\Match;

class TournamentBracket extends Model
{
    //
    protected $guarded = [];

    protected $appends = ['round_stage_name'];

    public function getRoundStageNameAttribute()
    {
        return Helper::roundName($this->round);
    }

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
