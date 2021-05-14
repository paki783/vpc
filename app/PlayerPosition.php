<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Position;
use App\lineUp;

class PlayerPosition extends Model
{
    //
    protected $guarded = [];

    function playerStatistic(){
        return $this->hasOne(PlayerStatistic::class, "line_up_id", "line_up_id");
    }

    function lineUp(){
        return $this->hasOne(lineUp::class, "id", "line_up_id");
    }
    
    function getUser(){
        return $this->hasOne(User::class, "id", "user_id");
    }
    
    function getPosition(){
        return $this->hasOne(Position::class, "id", 'position_id');
    }
}
