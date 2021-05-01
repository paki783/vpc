<?php

namespace App\Tournament;

use Illuminate\Database\Eloquent\Model;
use App\Tournament\TournamentGroupTeam;

class TournamentGroup extends Model
{
    //
    protected $guarded = [];
    function getGroupsTeam(){
        return $this->hasMany(TournamentGroupTeam::class, "group_id", "id");
    }
    function getGroup(){
        return $this->hasOne(TournamentGroup::class, "group_id", "id");
    }
}
