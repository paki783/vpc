<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Games;

class Position extends Model
{
    //
    protected $guarded = [];
    function getGame(){
        return $this->hasOne(Games::class, "id", "game_id");
    }
}
