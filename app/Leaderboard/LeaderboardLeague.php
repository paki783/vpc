<?php

namespace App\Leaderboard;

use Illuminate\Database\Eloquent\Model;
use App\Tournament;
use App\Leaderboard\Leaderboard;

class LeaderboardLeague extends Model
{
    //
    protected $guarded = [];
    function getLeague(){
        return $this->hasOne(Tournament::class, "id", "league_id");
    }
    function getLeaderBoard(){
        return $this->hasOne(Leaderboard::class, "id", "leaderboard_id");
    }
}
