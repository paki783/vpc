<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Position;

class PlayerPosition extends Model
{
    //
    protected $guarded = [];
    function lineUp(){
        return $this->hasOne(LineUp::class, "id", "line_up_id");
    }
    
    function getUser(){
        return $this->hasOne(User::class, "id", "user_id");
    }
    
    function getPosition(){
        return $this->hasOne(Position::class, "id", 'position_id');
    }
}
