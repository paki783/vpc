<?php

namespace App\Http\Controllers\Api\V1;

use App\Contract;
use App\Device;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Package;
use App\Rule;
use App\Team;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Match;
use App\Games;
use App\TeamAssign;
use App\User\UserAssistant;
use Illuminate\Support\Facades\Session;
use Validator;

class FeatureController extends Controller
{
    private $noOfRecordPerPage = 10;
    private $paginate = false;

    public function updateProfile(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "country_id" => "required",
            "selected_team" => "required",
            "position_id" => "required",
            "playstationtag" => "required",
            "xboxtag" => "required",
            "origin_account" => "required",
            "streamid" => "required",
        ]);
        
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422, $validator->errors()->all());
        }

        try {
            $userData = [
                "country_id" => $input['country_id'],
                "selected_team" => $input['selected_team'],
                "position_id" => $input['position_id'],
                "playstationtag" => $input['playstationtag'],
                "xboxtag" => $input['xboxtag'],
                "origin_account" => $input['origin_account'],
                "streamid" => $input['streamid'],
            ];

            if ($request->hasFile('profile_image')) {
                $img = $request->file('profile_image')->store('/', 'public');
                $profile_img = URL("public/storage/".$img);
                $userData['pending_profile_image'] = $profile_img;
                $userData['pending_profile_status'] = 1;
            }

            $user = User::find(Auth::user()->id);
            $user->update($userData);

            $data = array(
                'user' => $user
            );
            return Helper::successResponse($data, 'Successfully Update Record.');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

    public function packageList(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "type" => "required",
        ]);
        
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422, $validator->errors()->all());
        }

        try {
            $package = Package::where('type', $input['type'])->orderBy('sort_by', 'ASC')->get();

            $data = array(
                'package' => $package
            );

            return Helper::successResponse($data, 'Successfully Get Record.');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

    public function ruleGet(Request $request)
    {
        try {
            $rule = Rule::find(1);

            $data = array(
                'rule' => $rule
            );

            return Helper::successResponse($data, 'Successfully Get Record.');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

    public function teamGet(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "team_id" => "required|exists:teams,id",
            "user_id" => "required|exists:users,id",
            'league_id' => 'required|exists:tournaments,id',
            'division_id' => 'required|exists:divisions,id',
        ]);
        
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422, $validator->errors()->all());
        }

        try {
            $team = Team::
            with([
                "getTeamManager" => function ($q){
                    $q->with(["getAssistant" => function ($q2){
                        $q2->where("status", "active");
                    }]);
                }
            ])
            ->find($input['team_id']);

            $is_assistant = UserAssistant::where('status', 'active')
            ->where('team_id', $input['team_id'])
            ->where('user_id', $input['user_id'])
            ->where('league_id', $input['league_id'])
            ->where('division_id', $input['division_id'])
            ->first();

            $data = array(
                'team' => $team,
                'is_assistant' => $is_assistant
            );

            return Helper::successResponse($data, 'Successfully Get Record.');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

    public function userGet(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "user_id" => "required|exists:users,id",
        ]);
        
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422, $validator->errors()->all());
        }

        try {
            $user = User::find($input['user_id']);

            $data = array(
                'user' => $user
            );

            return Helper::successResponse($data, 'Successfully Get Record.');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

    public function contractList(Request $request)
    {
        $input = $request->only('search_value', 'search_by', 'page', 'pagination', 'perPage', 'contract_id');
        try {
            $contract = Contract::with([
                'getUser',
                'getManager',
                'getTeam',
                'getVPCSystem'
            ]);
            // search
            // if ((isset($input['search_by']) && $input['search_by'] != "") && (isset($input['search_value']) && $input['search_value'] != "")) {
            //     $contract = $contract->where($input['search_by'], $input['search_value']);
            // }

            // pagination and find
            if (isset($input['pagination']) && $input['pagination'] != "") {
                $this->paginate = true;
                if (isset($input['perPage']) && $input['perPage'] != "") {
                    $contract = $contract->paginate($input['perPage']);
                } else {
                    $contract = $contract->paginate($this->noOfRecordPerPage);
                }
            } else {
                $contract_id = $input['contract_id'];
                $contract = $contract->find($contract_id);
            }
            // data
            return Helper::successResponse($contract, 'Successfully Get Record.', $this->paginate);
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }
    
    public function matchList(Request $request)
    {
        $input = $request->only('search_value', 'search_by', 'page', 'pagination', 'perPage', 'match_id', 'league_id', 'division_id');
        try {
            $match =  Match::with([
                "getTeamOne",
                "getTeamTwo",
                "getLeague",
                "getDivision"
            ])->where("match_type", "match")
            ->orderBy('id', "ASC")
            ->whereHas("getLeague", function ($q) {
                $q->where("tournament_type", "league");
            });

            // search
            if ((isset($input['league_id']) && $input['league_id'] != "") && (isset($input['division_id']) && $input['division_id'] != "")) {
                $match = $match->where("league_id", $input['league_id'])->where("division_id", $input['division_id']);
            }

            // match_start_date
            if ((isset($input['start_date']) && $input['start_date'] != "")) {
                $match = $match->whereDate('match_start_date', '>=', $input['start_date']);
            } elseif ((isset($input['end_date']) && $input['end_date'] != "")) {
                $match = $match->whereDate('match_start_date', '<=', $input['end_date']);
            } elseif ((isset($input['start_date']) && $input['start_date'] != "") && (isset($input['end_date']) && $input['end_date'] != "")) {
                $match = $match->whereBetween(DB::raw('DATE(match_start_date)'), [$input['start_date'], $input['end_date']]);
            }

            // pagination and find
            if (isset($input['pagination']) && $input['pagination'] != "") {
                $this->paginate = true;
                if (isset($input['perPage']) && $input['perPage'] != "") {
                    $match = $match->paginate($input['perPage']);
                } else {
                    $match = $match->paginate($this->noOfRecordPerPage);
                }
            } else {
                $match_id = $input['match_id'];
                $match = $match->find($match_id);
            }
            // data
            return Helper::successResponse($match, 'Successfully Get Record.', $this->paginate);
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

    public function getTeamNoContact(Request $request)
    {
        $input = $request->only('search_value', 'search_by', 'page', 'pagination', 'perPage', 'team_id');
        try {
            $team = Team::whereRaw("id NOT IN (SELECT `team_id` FROM `contracts` where (`status` = 'offered' OR `status` = 'accepted'))");

            // // pagination and find
            if (isset($input['pagination']) && $input['pagination'] != "") {
                $this->paginate = true;
                if (isset($input['perPage']) && $input['perPage'] != "") {
                    $team = $team->paginate($input['perPage']);
                } else {
                    $team = $team->paginate($this->noOfRecordPerPage);
                }
            } else {
                $team_id = $input['team_id'];
                $team = $team->find($team_id);
            }
            
            // data
            return Helper::successResponse($team, 'Successfully Get Record.', $this->paginate);
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

    public function getUserNoContact(Request $request)
    {
        $input = $request->only('search_value', 'search_by', 'page', 'pagination', 'perPage', 'team_id');
        try {
            $team = User::whereRaw("id NOT IN (SELECT `user_id` FROM `contracts` where (`status` = 'offered' OR `status` = 'accepted'))");

            // // pagination and find
            if (isset($input['pagination']) && $input['pagination'] != "") {
                $this->paginate = true;
                if (isset($input['perPage']) && $input['perPage'] != "") {
                    $team = $team->paginate($input['perPage']);
                } else {
                    $team = $team->paginate($this->noOfRecordPerPage);
                }
            } else {
                $team_id = $input['team_id'];
                $team = $team->find($team_id);
            }
            
            // data
            return Helper::successResponse($team, 'Successfully Get Record.', $this->paginate);
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

    public function getAllTeamByLeagueAndDivisionList(Request $request)
    {
        $input = $request->only('search_value', 'search_by', 'page', 'pagination', 'perPage', 'teamAssign_id', 'league_id', 'division_id');
        try {
            $teamAssign =  TeamAssign::with([
                "getLeagues",
                "getDivision",
                "getTeams",
                "getTeamManager" => function ($q) {
                    $q->with('getUser');
                }
            ])->whereHas('getTeams');
            // search
            if ((isset($input['league_id']) && $input['league_id'] != "") && (isset($input['division_id']) && $input['division_id'] != "")) {
                $teamAssign = $teamAssign->where("league_id", $input['league_id'])->where("division_id", $input['division_id']);
            }

            // pagination and find
            if (isset($input['pagination']) && $input['pagination'] != "") {
                $this->paginate = true;
                if (isset($input['perPage']) && $input['perPage'] != "") {
                    $teamAssign = $teamAssign->paginate($input['perPage']);
                } else {
                    $teamAssign = $teamAssign->paginate($this->noOfRecordPerPage);
                }
            } else {
                $teamAssign_id = $input['teamAssign_id'];
                $teamAssign = $teamAssign->find($teamAssign_id);
            }
            // data
            return Helper::successResponse($teamAssign, 'Successfully Get Record.', $this->paginate);
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

    public function gameList(Request $request)
    {
        $input = $request->only('search_value', 'search_by', 'page', 'pagination', 'perPage', 'game_id');
        try {
            $games =  Games::orderBy('id', "ASC");

            // pagination and find
            if (isset($input['pagination']) && $input['pagination'] != "") {
                $this->paginate = true;
                if (isset($input['perPage']) && $input['perPage'] != "") {
                    $games = $games->paginate($input['perPage']);
                } else {
                    $games = $games->paginate($this->noOfRecordPerPage);
                }
            } else {
                $games_id = $input['games_id'];
                $games = $games->find($games_id);
            }
            // data
            return Helper::successResponse($games, 'Successfully Get Record.', $this->paginate);
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }
    
    public function logout(Request $request)
    {
        $input = $request->all();
        try {
            $device = Device::where([
                'user_id' => $input['user_id'],
                'uuid' => $input['uuid'],
                'type' => $input['type'],
            ])
            ->delete();

            auth()->logout();
            Session::flush();

            return Helper::successResponse([], 'Successfully Logout.');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }
}
