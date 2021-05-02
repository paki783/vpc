<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Tournament;
use Illuminate\Support\Facades\Auth;
use Validator;

class TournamentController extends Controller
{
    private $noOfRecordPerPage = 10;
    private $paginate = false;

    public function getBracketTournament(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "league_id" => "required|exists:tournaments,id",
            // "division_id" => "required|exists:tournament_brackets,id",
        ]);
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422, $validator->errors()->all());
        }

        try {
            
            $tournament = Tournament::where('id', $input['league_id'])
                ->with([
                    'tournamentBracket' => function($q) use ($input) {
                        $q->with([
                            'getMatach' => function($q2) use ($input) {
                                $q2->where('league_id', $input['league_id']);
                                $q2->where('match_type', 'bracket');
                                $q2->with([
                                    "getTournamentGroupTeamOne" => function ($q) {
                                        $q->with('getTeam');
                                    },
                                    "getTournamentGroupTeamTwo" => function ($q) {
                                        $q->with('getTeam');
                                    }
                                ]);
                            }
                        ]);
                        $q->orderBy('round', 'DESC');
                    }
                ])
                ->get();

            $data = array(
                'tournament' => $tournament
            );
            
            return Helper::successResponse($data, 'Successfully Get Record.');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }
}
