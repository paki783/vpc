<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Mode;

class TournamentMode extends Model
{
    //
    protected $guarded = [];
    protected $table = "tournament_game_mode";

    function getMode(){
        return $this->hasOne(Mode::class, "id", "modeid");
    }
}
