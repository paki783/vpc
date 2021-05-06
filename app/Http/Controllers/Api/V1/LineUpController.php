<?php

namespace App\Http\Controllers\Api\V1;

use App\Contract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\LineUp;
use App\PlayerPosition;
use App\User;
use App\User\UserAssistant;
use Illuminate\Support\Facades\DB;
use Validator;

class LineUpController extends Controller
{
    private $noOfRecordPerPage = 10;
    private $paginate = false;

    // getContractUser
    public function getContractUser(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "league_id" => "required|exists:tournaments,id",
            "division_id" => "required|exists:divisions,id",
            "team_id" => "required|exists:teams,id",
        ]);
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422, $validator->errors()->all());
        }

        try {
            // dd($input);
            $contract = Contract::with([
                'getUser' => function($q) {
                    $q->with('getSelectdTeam');
                }
            ])
            ->where([
                "league_id" => $input['league_id'],
                "division_id" => $input['division_id'],
                "team_id" => $input['team_id'],
                'status' => 'accepted'
            ])
            ->whereHas('getUser');

            $this->paginate = true;
            if (isset($input['perPage']) && $input['perPage'] != "") {
                $contract = $contract->paginate($input['perPage']);
            } else {
                $contract = $contract->paginate($this->noOfRecordPerPage);
            }

            return Helper::successResponse($contract, 'Successfully Get Record.');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

    // submit
    public function playerSubmit(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "match_id" => "required|exists:matches,id",
            "league_id" => "required|exists:tournaments,id",
            "division_id" => "required|exists:divisions,id",
            "team_id" => "required|exists:teams,id",
            "positions"    => "required|array|min:1",
            "positions.*"  => "required|distinct|min:1",
            "positions.*.user_id"  => "required|distinct|min:1|exists:users,id",
            "positions.*.position_id"  => "required|distinct|min:1|exists:positions,id",
        ]);
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422, $validator->errors()->all());
        }

        DB::beginTransaction();
        try {

            $lineUp = LineUp::create([
                "match_id" => $input['match_id'],
                "league_id" => $input['league_id'],
                "division_id" => $input['division_id'],
                "team_id" => $input['team_id'], 
            ]);

            $playerPositionData = [];
            foreach ($input['positions'] as $k => $position) {
                $playerPositionData[] = [
                    'line_up_id' => $lineUp->id,
                    'user_id' => $position['user_id'],
                    'position_id' => $position['position_id'], 
                ];
            }

            $playerPosition = PlayerPosition::insert($playerPositionData);
            
            DB::commit();
            return Helper::successResponse([], 'Successfully Update Record.');
        } catch (\Exception $e) {
            DB::rollback();
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }
}
