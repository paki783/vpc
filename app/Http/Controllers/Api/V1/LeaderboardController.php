<?php

namespace App\Http\Controllers\Api\V1;

use App\Contract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;

use App\Helpers\Helper;

use App\Leaderboard\LeaderboardLeague;
use App\Leaderboard\LeaderboardStatic;
use App\PlayerStatistic;

class LeaderboardController extends Controller
{
    function getLeaderboardByLeague(Request $req){
        $validator = Validator::make($req->all(), [
            'league_id' => 'required'
        ]);
        if ($validator->fails()) {
            return Helper::errorResponse(401, "error", $validator->errors());
        }

        $leaderboard = LeaderboardLeague::where("league_id", $req->league_id)
        ->with([
            'getLeaderBoard'
        ])->latest()->get();

        if(empty($leaderboard)){
            return Helper::errorResponse(401, "error", "no league found");
        }
        
        return Helper::successResponse($leaderboard, "success");
    }
    function getLeaderboard(Request $req){
        $validator = Validator::make($req->all(), [
            'leaderboard_id' => 'required'
        ]);

        if ($validator->fails()) {
            return Helper::errorResponse(401, "error", $validator->errors());
        }

        $data = LeaderboardStatic::where('leaderboard_id', $req->leaderboard_id)->with([
            'getStatic'
        ])->get();

        return Helper::successResponse($data, "success");
    }
}