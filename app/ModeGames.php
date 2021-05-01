<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ModeGames;
use App\Games;

class ModeGames extends Model
{
    //
    protected $guarded = [];
    protected $table = "mode_games";
    function getGames(){
        return $this->hasOne(Games::class, "id", "game_id");
    }
}
