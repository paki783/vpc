<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Auth;
use Validator;

class TournamentController extends Controller
{
    private $noOfRecordPerPage = 10;
    private $paginate = false;

    // $data = Match::with([
    //             "getBracketDivision",
    //             "getTournamentGroupTeamOne" => function ($q) {
    //                 $q->with('getTeam');
    //             },
    //             "getTournamentGroupTeamTwo" => function ($q) {
    //                 $q->with('getTeam');
    //             },
    //             "getLeague"
    //         ])->where("match_type", "bracket")
    //         ->where("league_id", $req->tournament)
    //         ->where("division_id", $req->id)
    //         ->orderBy('id', "ASC")
    //         ->get();
    public function getBracketTournament(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "league_id" => "required|exists:tournaments,id",
            "division_id" => "required|exists:tournament_brackets,id",
        ]);
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422, $validator->errors()->all());
        }

        try {
            
            // return Helper::successResponse($games, 'Successfully Get Record.');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }
}
