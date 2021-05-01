<?php

namespace App\Tournament;

use Illuminate\Database\Eloquent\Model;
use App\Tournament\TournamentGroup;

class TournamentGroupTeam extends Model
{
    //
    protected $guarded = [];
    function groupName(){
        return $this->hasOne(TournamentGroup::class, "id", "group_id");
    }
}
