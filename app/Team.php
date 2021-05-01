<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TeamManager;
use App\Favourite;
use App\Countries;
use JWTAuth;
use Auth;

class Team extends Model
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
            $res = $this->getFavourite()->where("user_id", $this->userLogin())->count();
        }
        return $res;
    }
    function getTeamManager(){
        return $this->hasMany(TeamManager::class, "team_id", "id");
    }
    function getFavourite(){
        return $this->hasOne(Favourite::class, "type_id", "id")->where("type", "team");
    }
    function getCountry(){
        return $this->hasOne(Countries::class, "id", "country_id");
    }

}
