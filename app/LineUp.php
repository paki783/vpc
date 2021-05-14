<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Match;

class LineUp extends Model
{
    protected $guarded =  [];

    function match(){
        return $this->hasOne(Match::class, "id", "match_id");
    }
}
