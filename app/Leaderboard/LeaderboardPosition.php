<?php

namespace App\Leaderboard;

use Illuminate\Database\Eloquent\Model;
use App\Position;

class LeaderboardPosition extends Model
{
    //
    protected $guarded = [];
    function getPosition(){
        return $this->hasOne(Position::class, "id", "position_id");
    }
}
