<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Games;
use App\Statistic;
use App\PlayerStatistic;
use App\Attachment;
use Validator;
use App\PlayerPosition;
use App\Leaderboard\LeaderboardStatic;

class StatisticController extends Controller
{
    //
    function all_statistic(Request $req){
        
        if(!empty($req->search_now) and $req->is_api == 1){
            $data = Statistic::with([
            "getGame"
            ])->where('name', "like", "%".$req->q."%")->latest()->get();
            $res = [
                "status" => "success",
                "message" => "success",
                "data" => $data,
            ];
            return response()->json($res, 200);
        }
        if(!empty($req->searhitems)){
            $string = $req->searhitems;

            $data = Statistic::with([
                "getGame"
            ])
            ->where("id", $string)
            ->orwhere("name", "like", "%".$string."%")
            ->orwhere("statistic_abr", "like", "%".$string."%")
            ->latest()->paginate(15);
        }else{
            $data = Statistic::with([
                "getGame"
            ])->latest()->paginate(15);    
        }
        
        $parse = [
            "menu" => "statistic",
            "sub_menu" => "",
            "title" => "All Statistic",
            "data" => $data,
        ];
        
        return view('statistic.all_statistic', $parse);
    }
    function add_statistic(Request $req){
        
        $data = Games::latest()->get();

        $parse = [
            "menu" => "statistic",
            "sub_menu" => "",
            "title" => "Add Statistic",
            'data' => $data,
        ];
        
        return view('statistic.add_statistic', $parse);
    }
    function saveStatistic(Request $req){
        $id = $req->id;
        
        if($id == 0){
            $validator = Validator::make($req->all(), [
                'name' => 'required|unique:statistics,name',
                "statistic_abr" => "required",
                "game_id" => "required",
                "weight" => "required",
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }else{
                $ins = [
                    'name' => $req->name,
                    "statistic_abr" => $req->statistic_abr,
                    "game_id" => $req->game_id,
                    "weight" => $req->weight,
                    "multiplication" => $req->multi,
                ];
                Statistic::create($ins);
                $webmsg = [
                    "class" => "success",
                    "message" => "Statistic created successfully",
                ];
                return redirect()->back()->with($webmsg)->withInput();
            }
        }else{
            $validator = Validator::make($req->all(), [
                'name' => 'required',
                "statistic_abr" => "required",
                "game_id" => "required",
                "weight" => "required",
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }else{
                $ins = [
                    'name' => $req->name,
                    "statistic_abr" => $req->statistic_abr,
                    "game_id" => $req->game_id,
                    "weight" => $req->weight,
                    "multiplication" => $req->multi,
                ];
                Statistic::where("id", $id)->update($ins);
                $webmsg = [
                    "class" => "success",
                    "message" => "Statistic updated successfully",
                ];
                return redirect()->back()->with($webmsg)->withInput();
            }
        }
    }
    function edit(Request $req){
        $id = $req->id;
        
        $data = Statistic::with([
            "getGame"
        ])->where('id', $id)->first();
        //dd($data);

        $parse = [
            "menu" => "statistic",
            "sub_menu" => "",
            "title" => "Edit Statistic",
            "data" => $data,
            "games" => Games::latest()->get(),
        ];
        
        return view('statistic.edit_statistic', $parse);
    }
    function delete(Request $req){
        LeaderboardStatic::where("static_id", $req->id)->delete();
        Statistic::where("id", $req->id)->delete();
        PlayerStatistic::where("statistic_id", $req->id)->delete();
        $webmsg = [
            "class" => "success",
            "message" => "Statistic deleted successfully",
        ];
        return redirect()->back()->with($webmsg)->withInput();
    }
    
}
