<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Games;

class Statistic extends Model
{
    //
    protected $guarded = [];
    protected $table = "statistics";
    
    function getGame(){
        return $this->hasOne(Games::class, "id", "game_id");
    }
}
