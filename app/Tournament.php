<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Seasons;
use App\VPCSystems;
use App\TournamentMode;
use App\Division;
use App\Favourite;
use App\Attachment;
use App\Tournament\TournamentBracket;
use JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Tournament\TournamentTeam;
use App\Tournament\TournamentGroup;
use App\Tournament\TournamentGroupTeam;

class Tournament extends Model
{
    //
    protected $guarded = [];
    protected $appends = ["is_favourite"];

    protected $table = "tournaments";
    public function userLogin()
    {
        $user = Auth::guard('api')->user();
        if (!empty($user)) {
            return $user->id;
        } else {
            return 0;
        }
    }
    public function getIsFavouriteAttribute()
    {
        $res = 0;
        if (!empty($this->userLogin())) {
            $res = $this->getFavourites()->where("user_id", $this->userLogin())->count();
        }
        return $res;
    }
    public function getSeasons()
    {
        return $this->hasMany(Seasons::class, "tournament_id", "id");
    }

    public function tournamentBracket()
    {
        return $this->hasMany(TournamentBracket::class, "tournament_id", "id");
    }

    public function getVPCSystem()
    {
        return $this->hasOne(VPCSystems::class, "id", "vpc_systemid");
    }
    public function getTournamentMode()
    {
        return $this->hasMany(TournamentMode::class, "tournament_id", "id");
    }
    public function getDivision()
    {
        return $this->hasMany(Division::class, "league_id", "id");
    }
    public function getFavourites()
    {
        //$user = $this->guard()->user();
        return $this->hasOne(Favourite::class, "type_id", "id")
        ->whereRaw("(`type` = 'tournament' or `type` = 'league')");
    }
    public function getTournamentTeams()
    {
        return $this->hasMany(TournamentTeam::class, "tournament_id", "id");
    }
    public function getTournamentGroupbyTeam()
    {
        return $this->hasMany(TournamentGroup::class, "tournament_id", "id");
    }
    public function getLeagueRules()
    {
        return $this->hasOne(Attachment::class, "type_id", "id")->where('type', "league_rules");
    }
}
