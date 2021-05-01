<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ModeGames;

class Mode extends Model
{
    //
    protected $guarded = [];
    protected $table = "modes";
    function getModedGames(){
        return $this->hasMany(ModeGames::class, "mode_id", "id");
    }
}
