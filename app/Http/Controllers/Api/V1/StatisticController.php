<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\LineUp;
use App\PlayerPosition;
use App\PlayerStatistic;
use App\PlayerStatisticScore;
use App\Statistic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class StatisticController extends Controller
{
    private $noOfRecordPerPage = 10;
    private $paginate = false;

    // getUserStatisticPosition
    public function getUserStatisticPosition(Request $request)
    {
        $input = $request->all();

        try {
            // dd($input);
            $contract = PlayerPosition::with([
                'getUser',
                'getPosition',
                'lineUp' => function($q) {
                    $q->with('match');
                }
            ])
            ->where([
                "user_id" => $input['user_id'],
            ])
            ->whereHas('getUser')
            ->whereHas('lineUp', function($q){
                $q->whereHas('match', function($q2){
                    $q2->where('match_status', 'completed');
                });
            })
            ->whereDoesntHave('playerStatistic', function($q) use ($input){
                $q->where("user_id", $input['user_id']);
            });

            $this->paginate = true;
            if (isset($input['perPage']) && $input['perPage'] != "") {
                $contract = $contract->paginate($input['perPage']);
            } else {
                $contract = $contract->paginate($this->noOfRecordPerPage);
            }

            return Helper::successResponse($contract, 'Successfully Get Record.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

    // getGameStatistic
    public function getGameStatistic(Request $request){
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "game_id" => "required|exists:games,id",
        ]);
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422, $validator->errors()->all());
        }

        try {
            $statistic = Statistic::where("game_id", $input['game_id'])->get();

            
            // data
            $data = array(
                'statistic' => $statistic
            );

            return Helper::successResponse($data, 'Successfully Get Record.');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

    // submitStatistic
    public function submitStatistic(Request $request){
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "game_id" => "required|exists:games,id",
            "match_id" => "required|exists:matches,id",
            "team_id" => "required|exists:teams,id",
            "user_id" => "required|exists:users,id",
            "line_up_id" => "required|exists:line_ups,id",
            "position_id" => "required|exists:positions,id",
        ]);
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422, $validator->errors()->all());
        }
        
        DB::beginTransaction();
        try {

            $getPlayerStatistic = PlayerStatistic::where([
                "game_id" => $input['game_id'],
                "match_id" => $input['match_id'],
                "team_id" => $input['team_id'],
                "user_id" => $input['user_id'],
                "line_up_id" => $input['line_up_id'],
                "position_id" => $input['position_id'],
            ]);
            if ($getPlayerStatistic->exists()) {
                return Helper::errorResponse(422, ['Player Statistic Is Already Submited.']);
            }

            $lineUp = LineUp::where([
                "match_id" => $input['match_id'],
                "team_id" => $input['team_id'],
            ]);
            if (!$lineUp->exists()) {
                return Helper::errorResponse(422, ['This is no line-up of this match.']);
            }
            
            $playerStatistic = PlayerStatistic::create([
                "game_id" => $input['game_id'],
                "match_id" => $input['match_id'],
                "team_id" => $input['team_id'],
                "user_id" => $input['user_id'],
                "line_up_id" => $input['line_up_id'],
                "position_id" => $input['position_id'],
            ]);

            $playerStatisticScoreData = [];
            foreach ($input['statistic'] as $k => $v) {
                $playerStatisticScoreData[] = [
                    "player_statistic_id" => $playerStatistic->id,
                    "statistic_id" => $v['statistic_id'],
                    "score" => $v['score'],
                ];
            }

            $playerStatisticScore = PlayerStatisticScore::insert($playerStatisticScoreData);

            // data
            $data = array(
                'playerStatistic' => $playerStatistic
            );

            DB::commit();
            return Helper::successResponse($data, 'Successfully Update Record.');
        } catch (\Exception $e) {
            DB::rollback();
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }

    }

}
