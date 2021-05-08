<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\User;
use App\User\UserAssistant;
use Illuminate\Console\Application;
use Illuminate\Foundation\Console\EnvironmentCommand;
use Validator;

class AssistantController extends Controller
{
    private $noOfRecordPerPage = 10;
    private $paginate = false;

    // makeAssistant
    public function makeAssistant(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "team_id" => "required|exists:teams,id",
            "user_id" => "required|exists:users,id",
            "manager_id" => "required|exists:users,id",
            'league_id' => 'required|exists:tournaments,id',
            'division_id' => 'required|exists:divisions,id',
        ]);
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422, $validator->errors()->all());
        }

        try {
            $userAssistantData = [
                "user_id" => $input['user_id'],
                "manager_id" => $input['manager_id'],
                "league_id" => $input['league_id'],
                "division_id" => $input['division_id'],
                "team_id" => $input['team_id'],
            ];

            $userAssistantDataUpdate = $userAssistantData;
            $userAssistantDataUpdate['status'] = 'active';

            $userAssistant = UserAssistant::updateOrCreate(
                $userAssistantData,
                $userAssistantDataUpdate
            );

            $user = User::find($input['user_id']);
            $user->syncRoles('assistant');

            $data = array(
                'userAssistant' => $userAssistant
            );

            return Helper::successResponse($data, 'Successfully Update Record.');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

    // removeAssistant
    public function removeAssistant(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "id" => "required|exists:user_assistants,id",
        ]);
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422, $validator->errors()->all());
        }

        try {

            $userAssistant = UserAssistant::find($input['id']);

            $user = User::find($userAssistant->user_id);
            $user->syncRoles('users');

            $userAssistant->update(['status' => 'deactive']);

            $data = array(
                'userAssistant' => $userAssistant
            );

            return Helper::successResponse($data, 'Successfully Update Record.');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }
    
    // getAssistantManager
    public function getAssistantManager(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "user_id" => "required|exists:users,id|exists:user_assistants,user_id",
            'league_id' => 'required|exists:tournaments,id',
            'division_id' => 'required|exists:divisions,id',
        ]);
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422, $validator->errors()->all());
        }

        try {

            $userAssistant = UserAssistant::with(['getUser', 'getManager'])
            ->where('user_id',$input['user_id'])
            ->where('league_id',$input['league_id'])
            ->where('division_id',$input['division_id'])
            ->where('status','active')
            ->first();

            $data = array(
                'userAssistant' => @$userAssistant
            );

            return Helper::successResponse($data, 'Successfully Fetch Record.');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }
}
