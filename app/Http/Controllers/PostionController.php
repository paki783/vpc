<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Position;
use App\Games;
use Validator;
use App\User;
use App\Helpers\Helper;
use App\Leaderboard\LeaderboardPosition;
use App\PlayerStatistic;

class PostionController extends Controller
{
    //
    function all_postion(Request $req){
        
        if($req->is_api == 1){
            $data = Position::with([
                "getGame",
            ])->where("name", "like", "%".$req->q."%")->latest()->get();
            $res = [
                "status" => "success",
                "message" => "success",
                "data" => $data,
            ];
            return response()->json($res, 200);
        }
        if(!empty($req->searhitems)){
            $string = $req->searhitems;

            $data = Position::with([
                "getGame",
            ])
            ->where("id", $string)
            ->orwhere("name", "like", "%".$string,"%")
            ->orwhere("position_abr", "like", "%".$string,"%")
            ->latest()->paginate(15);
        }else{
            $data = Position::with([
                "getGame",
            ])->latest()->paginate(15);
        }
        
        $parse = [
            "menu" => "position",
            "sub_menu" => "",
            "title" => "Position",
            'data' => $data,
        ];
        return view('position.all_position', $parse);
    }
    function add_position(Request $req){
        
        $data = Games::latest()->get();
        $parse = [
            "menu" => "position",
            "sub_menu" => "",
            "title" => "Position",
            'data' => $data,
        ];
        
        return view('position.add_position', $parse);
    }
    function savePosition(Request $req){
        $id = $req->id;
        
        if($id == 0){
            $validator = Validator::make($req->all(), [
                'name' => 'required|unique:positions,name',
                "position_abr" => "required",
                "game_id" => "required",
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }else{
                $ins = [
                    "name" => $req->name,
                    "position_abr" => $req->position_abr,
                    "game_id" => $req->game_id,
                ];
                Position::create($ins);
                $webmsg = [
                    "class" => "success",
                    "message" => "Position created successfully",
                ];
                return redirect()->back()->with($webmsg)->withInput();
            }
        }else{
             $validator = Validator::make($req->all(), [
                'name' => 'required',
                "position_abr" => "required",
                "game_id" => "required",
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }else{
                $ins = [
                    "name" => $req->name,
                    "position_abr" => $req->position_abr,
                    "game_id" => $req->game_id,
                ];
                Position::where("id", $req->id)->update($ins);
                $webmsg = [
                    "class" => "success",
                    "message" => "Position updated successfully",
                ];
                return redirect()->back()->with($webmsg)->withInput();
            }
        }
    }
    function edit(Request $req){
        
        $data = Games::latest()->get();
        $position = Position::where("id", $req->id)->first();

        $parse = [
            "menu" => "position",
            "sub_menu" => "",
            "title" => "Position",
            'data' => $data,
            "position" => $position,
        ];
        
        return view('position.edit_position', $parse);
    }
    function delete(Request $req){
        Position::where("id", $req->id)->delete();
        User::where("position_id", $req->id)->update([
            "position_id" => 0,
        ]);
        $webmsg = [
            "class" => "success",
            "message" => "Position deleted successfully",
        ];
        return redirect()->back()->with($webmsg)->withInput();
    }
}
