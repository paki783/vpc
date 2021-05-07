<?php

namespace App\Http\Controllers\Api\V1;

use App\Contract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\LineUp;
use App\Match\MatchScore;
use App\Match;
use App\PlayerPosition;
use App\User\UserAssistant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class MatchController extends Controller
{
    private $noOfRecordPerPage = 10;
    private $paginate = false;

    // winByManager
    // checkMatchResult
    // getContractUser
    public function winByManager(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "match_id" => "required|exists:matches,id",
            "team_id" => "required|exists:teams,id",
            "home_score" => "required|numeric",
            "away_score" => "required|numeric",
        ]);
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422, $validator->errors()->all());
        }

        DB::beginTransaction();
        try {

            if (!(Auth::user()->hasRole(['assistant','manager']))) {
                return Helper::errorResponse(422, ['Role Doesn\'t exist or missing access rights to application.']);
            }

            Match::where('id', $input['match_id'])->update([
                'home_score' => $input['home_score'],
                'away_score' => $input['away_score'],
                'match_status' => 'in progress',
            ]);

            $matchScore = MatchScore::firstOrCreate([
                'user_id' => Auth::user()->id,
                'match_id' => $input['match_id'],
                'team_id' => $input['team_id'],
                'home_score' => $input['home_score'],
                'away_score' => $input['away_score'],
            ]);

            $data = array(
                'matchScore' => $matchScore
            );

            DB::commit();
            return Helper::successResponse($data, 'Successfully Update Record.');
        } catch (\Exception $e) {
            DB::rollback();
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

    public function checkMatchResult(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "match_id" => "required|exists:matches,id",
            "team_id" => "required|exists:teams,id",
        ]);
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422, $validator->errors()->all());
        }

        DB::beginTransaction();
        try {

            if (!(Auth::user()->hasRole(['assistant','manager']))) {
                return Helper::errorResponse(422, ['Role Doesn\'t exist or missing access rights to application.']);
            }

            $matchScore = MatchScore::where([
                'match_id' => $input['match_id'],
                'team_id' => $input['team_id']
            ])
            ->first();

            $data = array(
                'matchScore' => $matchScore
            );

            DB::commit();
            return Helper::successResponse($data, 'Successfully Get Record.');
        } catch (\Exception $e) {
            DB::rollback();
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }
}
