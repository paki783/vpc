<?php

namespace App;

use App\Leaderboard\Leaderboard;
use Illuminate\Database\Eloquent\Model;
use JWTAuth;

class Favourite extends Model
{
    //
    protected $guarded = [];
    
    public function team(){
        return $this->hasOne(Team::class, "id", "type_id");
    }

    public function league(){
        return $this->hasOne(Tournament::class, "id", "type_id");
    }

    public function tournament(){
        return $this->hasOne(Tournament::class, "id", "type_id");
    }
    public function leaderboard(){
        return $this->hasOne(Leaderboard::class, "id", "type_id");
    }
}
