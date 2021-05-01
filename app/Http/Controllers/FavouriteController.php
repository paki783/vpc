<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Favourite;
use Validator;
use App\Helpers\Helper;
use App\Team;
use App\Tournament;

class FavouriteController extends Controller
{
    //
    function saveFavourate(Request $req){
        $validator = Validator::make($req->all(), [
            'user_id' => 'required',
            'type_id' => 'required',
            'type' => 'required',
            'is_api' => 'required',
        ]);
        if ($validator->fails()) {
            return Helper::errorResponse(422, $validator->errors()->first(),$validator->errors());
        }else{
            Favourite::create([
                "user_id" => $req->user_id,
                'type_id' => $req->type_id,
                'type' => $req->type,
            ]);

            $res = [
                "status" => "success",
                "message" => "success",
                "data" => [],
            ];
            return response()->json($res, 200);
        }
    }
    function getFavourates(Request $req){
        $validator = Validator::make($req->all(), [
            'user_id' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return Helper::errorResponse(422, $validator->errors()->first(),$validator->errors());
        }else{
            $check =  Favourite::where([
                "user_id" => $req->user_id,
                'type' => $req->type,
            ])->paginate(15);
            $result = [];
            if(!empty($check)){
                foreach($check as $k => $c){
                    if($c->type == "team"){
                        //$result[] = Team::where("id", $c->type_id)->first();
                        $result = Team::where("id", $c->type_id)->with([
                            "getTeamManager"
                        ])->first();
                    }
                    if($c->type == "league" || $c->type == "tournament"){
                        $result = Tournament::where([
                            "id" => $c->type_id,
                            "tournament_type" => $c->type
                        ])->with([
                            "getDivision",
                        ])->first();
                    }
                    $check[$k]->results = $result;
                }

                $res = [
                    "status" => "success",
                    "message" => "success",
                    "data" => $check,
                ];
            }else{
                $res = [
                    "status" => "error",
                    "message" => "no favourites found",
                    "data" => [],
                ];
            }
            return response()->json($res, 200);
        }
    }
    function removeFavourite(Request $req){
        $validator = Validator::make($req->all(), [
            'user_id' => 'required',
            'type' => 'required',
            'type_id' => 'required',
        ]);
        if ($validator->fails()) {
            return Helper::errorResponse(422, $validator->errors()->first(),$validator->errors());
        }else{
            Favourite::where([
                'user_id' => $req->user_id,
                'type' => $req->type,
                'type_id' => $req->type_id,
            ])->delete();

            $res = [
                "status" => "success",
                "message" => "favourite deleted successfully",
                "data" => [],
            ];

            return response()->json($res, 200);
        }
    }
}
