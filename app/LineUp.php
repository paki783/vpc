<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Match;
use App\Match\MatchScore;

class LineUp extends Model
{
    protected $guarded =  [];

    function match(){
        return $this->hasOne(Match::class, "id", "match_id");
    }

    function matchScore(){
        return $this->hasMany(MatchScore::class, "match_id", "match_id");
    }
}
