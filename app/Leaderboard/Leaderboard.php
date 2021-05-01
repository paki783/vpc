<?php

namespace App\Leaderboard;

use Illuminate\Database\Eloquent\Model;
use App\Leaderboard\LeaderboardLeague;
use App\Leaderboard\LeaderboardPosition;
use App\Leaderboard\LeaderboardStatic;
use JWTAuth;
use Auth;
use App\Favourite;

class Leaderboard extends Model
{
    //
    protected $guarded = [];
    protected $appends = ["is_favourite"];
    function userLogin(){
        $user = Auth::guard('api')->user();
        if(!empty($user)){
            return $user->id;
        }else{
            return 0;
        }
    }
    function getIsFavouriteAttribute(){
        $res = [];
        if(!empty($this->userLogin())){
            $res = $this->getFavourites()->where("user_id", $this->userLogin())->count();
        }
        return $res;
    }
    function getFavourites(){
        //$user = $this->guard()->user();
        return $this->hasOne(Favourite::class, "type_id", "id")
            ->where("type", "leaderboard");
    }
    function getLeaderboardStatic(){
        return $this->hasMany(LeaderboardStatic::class, "leaderboard_id", "id");
    }
    function getLeaderboardPosition(){
        return $this->hasMany(LeaderboardPosition::class, "leaderboard_id", "id");
    }
    function getLeaderboardLeague(){
        return $this->hasMany(LeaderboardLeague::class, "leaderboard_id", "id");
    }
}
