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
    function save(Request $req){
        $validator = Validator::make($req->all(), [
            'user_id' => 'required',
            'type_id' => 'required',
            'type' => 'required',
        ]);
        
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422,$validator->errors()->all());
        }else{
            Favourite::where([
                "user_id" => $req->user_id,
                'type_id' => $req->type_id,
                'type' => $req->type,
            ])->delete();

            $favourite = Favourite::updateOrCreate([
                    "user_id" => $req->user_id,
                    'type_id' => $req->type_id,
                    'type' => $req->type,
                ],
                [
                    "user_id" => $req->user_id,
                    'type_id' => $req->type_id,
                    'type' => $req->type,
                ]
            );

            $data = array(
                "favourite" => $favourite
            );
            return Helper::successResponse($data, "Successfully Created.");
        }
    }
    function get(Request $req){
        $validator = Validator::make($req->all(), [
            'user_id' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return Helper::errorResponse(422, $validator->errors()->first(),$validator->errors());
        }else{
            $favourite =  Favourite::with([
                'team' => function ($q){
                    $q->with('getTeamManager');
                },
                'league' => function ($q){
                    $q->with('getDivision');
                },
                'tournament' => function ($q){
                    $q->with('getDivision');
                },
                'leaderboard',
            ])
            ->where([
                "user_id" => $req->user_id,
                'type' => $req->type,
            ])->paginate(15);

            return Helper::successResponse($favourite, 'Successfully Get Record.', true);
        }
    }
    function remove(Request $req){
        $validator = Validator::make($req->all(), [
            'user_id' => 'required',
            'type' => 'required',
            'type_id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422,$validator->errors()->all());
        }else{
            $favourite = Favourite::where([
                'user_id' => $req->user_id,
                'type' => $req->type,
                'type_id' => $req->type_id,
            ])->delete();

            $data = array(
                "favourite" => $favourite
            );
            return Helper::successResponse($data, 'Successfully Record Updated.');
        }
    }
}
