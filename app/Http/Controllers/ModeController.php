<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Mode;
use App\Games;
use App\ModeGames;
use App\Platform;

class ModeController extends Controller
{
    //
    public function all_mode(Request $req)
    {
        if (!empty($req->searhitems)) {
            $string = $req->searhitems;
            $data = Mode::with([
                "getModedGames"  => function ($query) {
                    $query->with('getGames');
                },
            ])
            ->where("id", $string)
            ->orwhere("mode_name", "like", "%".$string."%")
            ->latest();
        } else {
            $data = Mode::with([
                "getModedGames"  => function ($query) {
                    $query->with('getGames');
                },
            ])->latest();
            if (!empty($req->mode_name)) {
                $data = $data->where("mode_name", $req->mode_name);
            }
        }
        $data = $data->paginate(15);
        $parse = [
            "menu" => "modes",
            "sub_menu" => "",
            "title" => "All Modes",
            'data' => $data,
        ];

        if ($req->is_api == 1) {
            return response()->json($parse, 200);
        }
        
        return view('mode.all_mode', $parse);
    }
    public function add_mode(Request $req)
    {
        
        $data = [];
        $parse = [
            "menu" => "modes",
            "sub_menu" => "",
            "title" => "Add Modes",
            'data' => $data,
            "games" => Games::latest()->get(),
        ];
        
        return view('mode.add_mode', $parse);
    }
    public function saveMode(Request $req)
    {
        $id = $req->id;
        
        if ($id == 0) {
            $validator = Validator::make($req->all(), [
                'mode_name' => 'required|unique:modes,mode_name',
                "gameids" => "required",
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            } else {
                $id = Mode::create(['mode_name' => $req->mode_name])->id;
                if (!empty($req->gameids)) {
                    foreach ($req->gameids as $gameid) {
                        ModeGames::create([
                            'mode_id' => $id,
                            "game_id" => $gameid,
                        ]);
                    }
                }
                $webmsg = [
                    "class" => "success",
                    "message" => "Mode created succssfully",
                ];
                return redirect()->back()->with($webmsg);
            }
        } else {
            $validator = Validator::make($req->all(), [
                'mode_name' => 'required',
                "gameids" => "required",
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            } else {
                Mode::where("id", $id)->update(['mode_name' => $req->mode_name]);
                ModeGames::where("mode_id", $id)->delete();
                if (!empty($req->gameids)) {
                    foreach ($req->gameids as $gameid) {
                        ModeGames::create([
                            'mode_id' => $id,
                            "game_id" => $gameid,
                        ]);
                    }
                }
                $webmsg = [
                    "class" => "success",
                    "message" => "Mode updated succssfully",
                ];
                return redirect()->back()->with($webmsg);
            }
        }
    }
    public function delete(Request $req)
    {
        $id = $req->id;
        Mode::where('id', $id)->delete();
        ModeGames::where("mode_id", $id)->delete();
        $webmsg = [
            "class" => "success",
            "message" => "Mode deleted succssfully",
        ];
        return redirect()->back()->with($webmsg);
    }
    public function edit(Request $req)
    {
        
        $id = $req->id;
        $data = Mode::where('id', $id)->with([
            "getModedGames"  => function ($query) {
                $query->with('getGames');
            },
        ])->first();
        $selectedIds = [];
        if (!empty($data)) {
            if (!empty($data->getModedGames)) {
                foreach ($data->getModedGames as $gameid) {
                    $selectedIds[] = $gameid->game_id;
                }
            }
        }
        $parse = [
            "menu" => "modes",
            "sub_menu" => "",
            "title" => "Edit Modes",
            'data' => $data,
            "games" => Games::latest()->get(),
            "selectedIds" => $selectedIds,
        ];
        
        return view('mode.edit_mode', $parse);
    }
    public function get_league_mode(Request $req)
    {
        $res = [
            "status" => "success",
            "message" => "success",
            "platform" => Platform::latest()->get(),
            "model" => Mode::with([
                "getModedGames"  => function ($query) {
                    $query->with('getGames');
                },
            ])->latest()->get(),
        ];
        return response()->json($res, 200);
    }
}
