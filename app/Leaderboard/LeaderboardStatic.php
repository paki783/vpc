<?php

namespace App\Leaderboard;

use Illuminate\Database\Eloquent\Model;
use App\Statistic;

class LeaderboardStatic extends Model
{
    //
    protected $guarded = [];
    function getStatic(){
        return $this->hasOne(Statistic::class, "id", "static_id");
    }
}
