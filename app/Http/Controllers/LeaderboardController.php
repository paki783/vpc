<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Leaderboard\Leaderboard;
use App\Leaderboard\LeaderboardLeague;
use App\Leaderboard\LeaderboardPosition;
use App\Leaderboard\LeaderboardStatic;
use App\PlayerStatistic;
use Validator;

class LeaderboardController extends Controller
{
    //
    function all_leaderboard(){

        $data = Leaderboard::with([
            "getLeaderboardStatic" => function($query) {
                $query->with('getStatic');
            },
            "getLeaderboardPosition" => function($query) {
                $query->with('getPosition');
            },
            "getLeaderboardLeague" => function($query) {
                $query->with('getLeague');
            },
        ])->latest()->paginate(15);

        $parse = [
            "menu" => "leaderboard",
            "sub_menu" => "",
            "title" => "All Leaderboard",
            'data' => $data,
        ];
        return view('leaderboard.all_leaderboard', $parse);
    }
    function add_leaderboard(Request $req){
        $parse = [
            "menu" => "leaderboard",
            "sub_menu" => "",
            "title" => "Add Leaderboard",
            'data' => [],
        ];
        return view('leaderboard.add_leaderboard', $parse);
    }
    function saveLeaderboard(Request $req){
        $id = $req->id;
        if($id == 0){
            $validator = Validator::make($req->all(), [
                'leaderboard_name' => 'required|unique:leaderboards,name',
                'static_id' => 'required',
                'league_id' => 'required',
                'position_id' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }else{
                $leaderboard_id = Leaderboard::create([
                    "name" => $req->leaderboard_name,
                ])->id;

                if(!empty($req->league_id)){
                    foreach($req->league_id as $lid){
                        LeaderboardLeague::create([
                            "leaderboard_id" => $leaderboard_id,
                            "league_id" => $lid,
                        ]);
                    }
                }

                if(!empty($req->position_id)){
                    foreach($req->position_id as $lid){
                        LeaderboardPosition::create([
                            "leaderboard_id" => $leaderboard_id,
                            "position_id" => $lid,
                        ]);
                    }
                }

                if(!empty($req->static_id)){
                    foreach($req->static_id as $lid){
                        LeaderboardStatic::create([
                            "leaderboard_id" => $leaderboard_id,
                            "static_id" => $lid,
                        ]);
                    }
                }

                $webmsg = [
                    "class" => "success",
                    "message" => "Leaderboard created successfully",
                ];
                return redirect()->back()->with($webmsg);
            }
        }else{
            $validator = Validator::make($req->all(), [
                'leaderboard_name' => 'required',
                'static_id' => 'required',
                'league_id' => 'required',
                'position_id' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }else{
                Leaderboard::where("id", $id)->update([
                    "name" => $req->leaderboard_name,
                ]);
                $leaderboard_id = $id;
                LeaderboardLeague::where([
                    "leaderboard_id" => $leaderboard_id,
                ])->delete();
                LeaderboardPosition::where([
                    "leaderboard_id" => $leaderboard_id,
                ])->delete();
                LeaderboardStatic::where([
                    "leaderboard_id" => $leaderboard_id,
                ])->delete();
                if(!empty($req->league_id)){
                    foreach($req->league_id as $lid){
                        LeaderboardLeague::create([
                            "leaderboard_id" => $leaderboard_id,
                            "league_id" => $lid,
                        ]);
                    }
                }

                if(!empty($req->position_id)){
                    foreach($req->position_id as $lid){
                        LeaderboardPosition::create([
                            "leaderboard_id" => $leaderboard_id,
                            "position_id" => $lid,
                        ]);
                    }
                }

                if(!empty($req->static_id)){
                    foreach($req->static_id as $lid){
                        LeaderboardStatic::create([
                            "leaderboard_id" => $leaderboard_id,
                            "static_id" => $lid,
                        ]);
                    }
                }
                $webmsg = [
                    "class" => "success",
                    "message" => "Leaderboard updated successfully",
                ];
                return redirect()->back()->with($webmsg);
            }
        }
    }
    function delete(Reqeust $req){
        $id = $req->id;
        LeaderboardStatic::create([
            "leaderboard_id" => $id,
        ])->delete();
        LeaderboardPosition::create([
            "leaderboard_id" => $id,
        ])->delete();
        LeaderboardLeague::create([
            "leaderboard_id" => $id,
        ])->delete();
        Leaderboard::where("id", $id)->delete();

        $webmsg = [
            "class" => "success",
            "message" => "Leaderboard deleted successfully",
        ];
        return redirect()->back()->with($webmsg);
    }
    function edit(Request $req){

        $data = Leaderboard::with([
            "getLeaderboardStatic" => function($query) {
                $query->with('getStatic');
            },
            "getLeaderboardPosition" => function($query) {
                $query->with('getPosition');
            },
            "getLeaderboardLeague" => function($query) {
                $query->with('getLeague');
            },
        ])->where("id", $req->id)->first();

        $parse = [
            "menu" => "leaderboard",
            "sub_menu" => "",
            "title" => "Edit Leaderboard",
            'data' => $data,
        ];
        return view('leaderboard.edit_leaderboard', $parse);
    }
    function getLeaderboardbyuser(Request $req){
        $leaderboard_id = $req->leaderboard_id;
        $getStatistic = LeaderboardStatic::where("leaderboard_id", $leaderboard_id)
        ->with(["getStatic"])->get();
        $getPosition = LeaderboardPosition::where("leaderboard_id", $leaderboard_id)->get();
        $positionIDS = [];
        $statisticIDS = [];
        if(!empty($getPosition)){
            foreach($getPosition as $p){
                array_push($positionIDS, $p->position_id);
            }
        }
        if(!empty($getStatistic)){
            foreach($getStatistic as $p){
                array_push($statisticIDS, $p->static_id);
            }
        }
        
        $PlayerStatistics = PlayerStatistic::with([
            "getUser",
            "getTeam",
        ])
        ->orderByDesc("score")
        ->whereIn("statistic_id", $statisticIDS)
        ->whereIn("position_id", $positionIDS)
        ->groupBy('user_id')
        ->get();
        $result = [];
        if(!empty($PlayerStatistics)){
            foreach($PlayerStatistics as $key => $statistic){
                $PlayerStatistics[$key]->statistic = PlayerStatistic::where([
                    "user_id" => $statistic->user_id,
                ])
                ->whereIn("statistic_id", $statisticIDS)
                ->whereIn("position_id", $positionIDS)
                ->with([
                    "getStatistic",
                    "getPosition",
                ])->get();
            }
        }
        $res = [
            "status" => "success",
            "message" => "success",
            "data" => $PlayerStatistics,
            "statistic" => $getStatistic,
        ];
        return response()->json($res, 200);
    }
    function getLeaderboardbyteam(Request $req){
        $leaderboard_id = $req->leaderboard_id;
        $getStatistic = LeaderboardStatic::where("leaderboard_id", $leaderboard_id)
        ->with(["getStatic"])->get();
        $getPosition = LeaderboardPosition::where("leaderboard_id", $leaderboard_id)->get();
        $positionIDS = [];
        $statisticIDS = [];
        if(!empty($getPosition)){
            foreach($getPosition as $p){
                array_push($positionIDS, $p->position_id);
            }
        }
        if(!empty($getStatistic)){
            foreach($getStatistic as $p){
                array_push($statisticIDS, $p->static_id);
            }
        }
        
        $PlayerStatistics = PlayerStatistic::with([
            "getTeam",
        ])
        ->orderByDesc("score")
        ->whereIn("statistic_id", $statisticIDS)
        ->whereIn("position_id", $positionIDS)
        ->groupBy('team_id')
        ->get();
        $result = [];
        if(!empty($PlayerStatistics)){
            foreach($PlayerStatistics as $key => $statistic){
                $PlayerStatistics[$key]->statistic = PlayerStatistic::where([
                    "team_id" => $statistic->team_id,
                ])
                ->whereIn("statistic_id", $statisticIDS)
                ->whereIn("position_id", $positionIDS)
                ->with([
                    "getStatistic",
                    "getPosition",
                ])->get();
            }
        }
        $res = [
            "status" => "success",
            "message" => "success",
            "data" => $PlayerStatistics,
            "statistic" => $getStatistic,
        ];
        return response()->json($res, 200);
    }
    function getLeaderboards(Request $req){
        $data = Leaderboard::latest()->get();

        $res = [
            "status" => "success",
            "message" => "success",
            "data" => $data,
        ];

        return response($res, 200);
    }
}
