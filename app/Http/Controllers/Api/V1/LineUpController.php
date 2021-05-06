<?php

namespace App\Http\Controllers\Api\V1;

use App\Contract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\User;
use App\User\UserAssistant;
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
    public function submit(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "match" => "required|exists:tournaments,id",
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
            $userAssistant = UserAssistant::with([
                'getUser' => function($q) {
                    $q->with('getSelectdTeam');
                }
            ])
            ->where([
                "league_id" => $input['league_id'],
                "division_id" => $input['division_id'],
                "team_id" => $input['team_id'],
                'status' => 'active'
            ])
            ->whereHas('getUser');

            $this->paginate = true;
            if (isset($input['perPage']) && $input['perPage'] != "") {
                $userAssistant = $userAssistant->paginate($input['perPage']);
            } else {
                $userAssistant = $userAssistant->paginate($this->noOfRecordPerPage);
            }

            return Helper::successResponse($userAssistant, 'Successfully Get Record.');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }
}
