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
    function delete(Request $req){
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
}
