<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use Validator;
use App\Games;
use App\Contract;
use App\VPCSystems;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use App\ModeGames;
use App\Position;

class GamesController extends Controller
{
    //
    public function guard()
    {
        return Auth::guard('api');
    }
    function all_game(Request $req){
        if(!empty($req->searhitems)){
            $data = Games::where("id", $req->searhitems)
            ->orwhere('game_name', "like", "%".$req->searhitems."%")->latest();
        }else{
            if(!empty($req->search_now) and $req->is_api == 1){
                $api = Games::where('game_name', "like", "%".$req->game_name."%")->select('game_name')->get();
                return response()->json($api, 200);
            }
            $data = Games::latest();
        }
        $data = $data->paginate(15);
        $parse = [
            "menu" => "games",
            "sub_menu" => "",
            "title" => "Games",
            'data' => $data,
        ];
        
        return view('games.all', $parse);
    }
    function add_game(){
        $data = [];
        $parse = [
            "menu" => "games",
            "sub_menu" => "",
            "title" => "Add Games",
            'data' => $data,
        ];
        
        return view('games.add', $parse);
    }
    function saveGame(Request $req){
        $id = $req->id;
        if($id == 0){
            $validator = Validator::make($req->all(), [
                'game_name' => 'required',
                'game_logo' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }else{
                if($req->hasFile('game_logo')){
                    $img = $req->file('game_logo')->store('/', 'public');
                    $img = URL("public/storage/".$img);
                }else{
                    $img = "";
                }
                $ins = [
                    "game_name" => $req->game_name,
                    "game_logo" => $img
                ];
                Games::create($ins);
                $webmsg = [
                    "class" => "success",
                    "message" => "Game created succssfully",
                ];
                return redirect()->back()->with($webmsg);
            }
        }else{
            $validator = Validator::make($req->all(), [
                'game_name' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }else{
                $ins = [
                    "game_name" => $req->game_name,
                ];
                if($req->hasFile('game_logo')){
                    $img = $req->file('game_logo')->store('/', 'public');
                    $img = URL("public/storage/".$img);
                    $ins["game_logo"] = $img;
                }
                Games::where("id", $id)->update($ins);
                $webmsg = [
                    "class" => "success",
                    "message" => "Game updated succssfully",
                ];
                return redirect()->back()->with($webmsg);
            }
        }
    }
    function delete(Request $req){
        Games::where("id", $req->id)->delete();
        VPCSystems::where("game", $req->id)->delete();
        ModeGames::where("game_id", $req->id)->delete();
        Position::where("game_id", $req->id)->delete();
        $webmsg = [
            "class" => "success",
            "message" => "Game deleted succssfully",
        ];
        return redirect()->back()->with($webmsg);
    }
    function edit(Request $req){
        $data = Games::where("id", $req->id)->first();
        $parse = [
            "menu" => "games",
            "sub_menu" => "",
            "title" => "Edit Games",
            'data' => $data,
        ];
        
        return view('games.edit_game', $parse);
    }
    function getGamesAPI(Request $req){
        if($req->is_api == 1){
            $data = Games::latest()->paginate(15);
            $res = [
                "status" => "success",
                "message" => "success",
                "data" => $data,
            ];
            return response()->json($res, 200);
        }
    }
    function gamesIPlayed(Request $req){
        try {
            $user = $this->guard()->user();
        }
        catch (\Exception $e) {
            return Helper::errorResponse(401, $e->getMessage());
        }
        $user = $this->guard()->user();
        if(!empty($user)){
            $teamids = Contract::where('user_id', $user->id)
            ->groupBy("team_id")
            ->get();
            $vpcsIDS = [];
            if(!empty($teamids)){
                foreach($teamids as $ids){
                    array_push($vpcsIDS, $ids->vpc_system_id);
                }
            }
            if(!empty($vpcsIDS)){
                $getGames = VPCSystems::with([
                    "GetGame",
                ])
                ->whereIn("id", $vpcsIDS)
                ->get();

                $res = [
                    "status" => "success",
                    "message" => "success",
                    "data" => $getGames,

                ];
            }else{
                $res = [
                    "status" => "error",
                    "message" => "no games you are  in",
                    "data" => [],
                ];
            }
            return response()->json($res, 200);
        }
    }
}
