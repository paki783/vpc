<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Position;
use App\PlayerPosition;
use App\Helpers\Helper;
use Validator;

class PlayerPositionController extends Controller
{
    //
    function get_poistion(Request $req){
        if($req->is_api == 1){
            $api = Position::where("game_id", $req->game_id)->get();
            $parse = [
                "status" => "success",
                "message" => "success",
                "data" => $api,
            ];
            return Helper::successResponse($parse, 'Successfully get league');
        }
    }
    function savePlayerPosition(Request $req){
        $validator = Validator::make($req->all(), [
            'team_id' => 'required',
            'user_id' => 'required',
            'position_id' => 'required',
            'vpc_system_id' => 'required',
        ]);
        if ($validator->fails()) {
            if($req->is_api == 1){
                return Helper::errorResponse(422, $validator->errors()->first(),$validator->errors());
            }else{
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
        }else{
            PlayerPosition::create([
                "team_id" => $req->team_id,   
                "user_id" => $req->user_id,   
                "position_id" => $req->position_id,   
                "vpc_system_id" => $req->vpc_system_id,
            ]);
            $parse = [
                "class" => "success",
                "message" => "position assign to player successfully",
            ];
            if($req->is_api == 1){
                return Helper::successResponse($parse, 'Successfully get league');
            }else{
                return redirect()->back()->with($parse);
            }
        }
    }
}
