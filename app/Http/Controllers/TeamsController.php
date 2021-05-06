<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Team;
use App\User;
use App\TeamManager;
use JWTAuth;
use Validator;
use App\Division;
use App\Tournament;
use App\TeamAssign;
use App\Match;
use App\Countries;
use App\Contract;
use App\Favourite;
use App\Tournament\TournamentTeam;
use Illuminate\Support\Facades\Auth;
use App\Achievement\AssignAward;
use App\Achievement\AssignMedal;
use App\Match\MatchScore;
use App\PlayerStatistic;
use App\DivisionTeam;

class TeamsController extends Controller
{
    //
    public function guard()
    {
        return Auth::guard('api');
    }
    public function all(Request $req)
    {
        
        if ($req->is_api == 1) {
            $api = [
                'status' => "success",
                "message" => "success",
                "data" => Team::where('team_name', 'like', '%'.$req->q."%")->with([
                    "getCountry",
                ])->get(),
            ];
            return response()->json($api, 200);
        }
        if (!empty($req->searhitems)) {
            $data = Team::with([
                "getTeamManager" => function ($query) {
                    $query->with('getUser');
                },
            ])->where('team_name', 'like', '%'.$req->searhitems."%")
            ->orWhere('id', $req->searhitems)
            ->latest()->paginate(15);
        } else {
            $data = Team::with([
                "getTeamManager" => function ($query) {
                    $query->with('getUser');
                },
            ])->latest();

            if (!empty($req->team_id)) {
                $data = $data->where('id', $req->team_id);
            }
            $data = $data->latest()->paginate(15);
        }

        $parse = [
            "menu" => "teams",
            "sub_menu" => "all_teams",
            "title" => "All Teams",
            'data' => $data,
        ];
        return view("teams.all", $parse);
    }
    //
    public function add_team()
    {
        
        $data = [];
        $parse = [
            "menu" => "teams",
            "sub_menu" => "all_teams",
            "title" => "Add Teams",
            'data' => $data,
            "users" => User::role('manager')->get(),
            "countries" => Countries::all(),
        ];
        return view("teams.add_team", $parse);
    }
    public function save_teams(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'team_name' => 'required',
            'team_abr' => 'required',
            'manager' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } else {
            $id = $req->id;
            $ins = [
                "team_name" => $req->team_name,
                "team_abbrivation" => $req->team_abr,
                "country_id" => $req->country_id,
            ];
            if ($id == 0) {
                $ins["team_logo"] = $ins["team_banner"] = "";
                if ($req->hasFile('team_logo')) {
                    $img = $req->file('team_logo')->store('/', 'public');
                    $img = URL("public/storage/".$img);
                    $ins["team_logo"] = $img;
                }
                if ($req->hasFile('team_banner')) {
                    $img = $req->file('team_banner')->store('/', 'public');
                    $img = URL("public/storage/".$img);
                    $ins["team_banner"] = $img;
                }
                $id = Team::create($ins)->id;
                if (!empty($req->manager)) {
                    TeamManager::where("team_id", $id)->delete();
                    foreach ($req->manager as $manager) {
                        TeamManager::create([
                            "team_id" => $id,
                            "user_id" => $manager,
                        ]);
                    }
                }
                $webmsg = [
                    "class" => "success",
                    "message" => "team added successfully",
                ];
                return redirect()->back()->with($webmsg);
            } else {
                if ($req->hasFile('team_logo')) {
                    $img = $req->file('team_logo')->store('/', 'public');
                    $img = URL("public/storage/".$img);
                    $ins["team_logo"] = $img;
                }
                if ($req->hasFile('team_banner')) {
                    $img = $req->file('team_banner')->store('/', 'public');
                    $img = URL("public/storage/".$img);
                    $ins["team_banner"] = $img;
                }
                Team::where("id", $id)->update($ins);
                if (!empty($req->manager)) {
                    TeamManager::where("team_id", $id)->delete();
                    foreach ($req->manager as $manager) {
                        TeamManager::create([
                            "team_id" => $id,
                            "user_id" => $manager,
                        ]);
                    }
                }
                $webmsg = [
                    "class" => "success",
                    "message" => "team updated successfully",
                ];
                return redirect()->back()->with($webmsg);
            }
        }
    }
    public function delete(Request $req)
    {
        $id = $req->id;
        Team::where("id", $id)->delete();
        User::where("selected_team", $id)->update([
            "selected_team" => 0,
        ]);
        Favourite::where([
            "type" => "team",
            "type_id" => $id,
        ])->delete();

        $webmsg = [
            "class" => "success",
            "message" => "team deleted successfully",
        ];
        return redirect()->back()->with($webmsg);
    }
    public function edit(Request $req)
    {
        $id = $req->id;
        
        
        $data = Team::with([
            "getTeamManager"
        ])->where("id", $id)->first();

        $selectedIDS = [];
        if (!empty($data)) {
            if (!empty($data->getTeamManager)) {
                foreach ($data->getTeamManager as $manager) {
                    $selectedIDS[] = $manager->user_id;
                }
            }
        }
        $parse = [
            "menu" => "teams",
            "sub_menu" => "all_teams",
            "title" => "Edit Teams",
            'data' => $data,
            "users" => User::role("manager")->get(),
            "selectedIDS" => $selectedIDS,
            "countries" => Countries::all(),
        ];
        if ($req->is_api == 1) {
            return response()->json($parse, 200);
        }
        return view("teams.edit_team", $parse);
    }
    public function assign_teams(Request $req)
    {
        
        if (!empty($req->searhitems)) {
            $string = $req->searhitems;
            $data = TeamAssign::with([
                "getLeagues",
                "getDivision",
            ])
            ->whereHas('getLeagues', function ($q) use ($string) {
                $q->where("id", $string)
                ->orwhere("name", "like", "%".$string."%");
            })
            ->latest()->groupBy("league_id");

            if (!empty($req->league_id) and $req->league_id != 0) {
                $data = $data->where("league_id", $req->league_id);
            }
            $data = $data->paginate(15);
        } else {
            $data = TeamAssign::with([
                "getLeagues",
                "getDivision",
                "getAllTeams"
            ])->latest()->groupBy(["league_id", "division_id"]);

            if (!empty($req->league_id) and $req->league_id != 0) {
                $data = $data->where("league_id", $req->league_id);
            }
            $data = $data->paginate(15);
        }
        //dd($data);
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $data[$k]->teams = TeamAssign::with([
                    "getTeams"
                ])->where([
                    "league_id" => $v->league_id,
                    "division_id" => $v->division_id,
                ])->get();
            }
        }
        //dd($data);
        $parse = [
            "menu" => "teams",
            "sub_menu" => "assign_teams",
            "title" => "All Asign Teams",
            'data' => $data,
        ];
        if ($req->is_api == 1) {
            return response($parse, 200);
        }
        return view("teams.team_assign.all_assign", $parse);
    }
    public function add_assign_team(Request $req)
    {
        
        $parse = [
            "menu" => "teams",
            "sub_menu" => "assign_teams",
            "title" => "Add Assign Teams",
            'data' => [],
        ];
        //dd($parse["leagues"]);

        return view("teams.team_assign.add_assign", $parse);
    }
    public function save_assign_team(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'league_id' => 'required',
            'division_id' => 'required',
            'team_id' => 'required',
            'current_Season' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } else {
            if ($req->id == 0) {
                if (!empty($req->team_id)) {
                    foreach ($req->team_id as $team_id) {
                        $ins = [
                            'league_id' => $req->league_id,
                            'division_id' => $req->division_id,
                            'team_id' => $team_id,
                            'current_Season' => $req->current_Season,
                        ];
                        TeamAssign::create($ins);
                    }
                }
                $webmsg = [
                    "class" => "success",
                    "message" => "team assign to the league successfully",
                ];
            } else {
                TeamAssign::where([
                    "league_id" => $req->league_id,
                    "division_id" => $req->division_id,
                ])->delete();
                if (!empty($req->team_id)) {
                    foreach ($req->team_id as $team_id) {
                        $ins = [
                            'league_id' => $req->league_id,
                            'division_id' => $req->division_id,
                            'team_id' => $team_id,
                            'current_Season' => $req->current_Season,
                        ];
                        TeamAssign::create($ins);
                    }
                }
                $webmsg = [
                    "class" => "success",
                    "message" => "team assign updated to the league successfully",
                ];
            }
            return redirect()->back()->with($webmsg);
        }
    }
    public function delete_assign_team(Request $req)
    {
        TeamAssign::where([
            "league_id" => $req->league_id,
            "division_id" => $req->divion_id,
        ])->delete();
        echo $req->division_id;
        $webmsg = [
            "class" => "success",
            "message" => "data deleted successfully",
        ];
        return redirect()->back()->with($webmsg);
    }
    public function edit_assign_team(Request $req)
    {
        $data = Division::latest()->with([
            "getLeagues",
            "getDivisionTeams" => function ($q) {
                $q->with("getTeam");
            },
        ])->where([
            "league_id" => $req->league_id,
            "id" => $req->divion_id,
        ])->first();
        $teams = TeamAssign::where([
            "league_id" => $req->league_id,
            "division_id" => $req->divion_id,
        ])->get();
        $selectedIDS = [];
        if (!empty($teams)) {
            foreach ($teams as $t) {
                array_push($selectedIDS, $t->team_id);
            }
        }
        $data->seasons = TeamAssign::with([
            "getSeasons",
        ])->where("league_id", $req->league_id)->first();
        $teams = [];
        $parse = [
            "menu" => "teams",
            "sub_menu" => "assign_teams",
            "title" => "Edit Assign Teams",
            'data' => $data,
            "teams" => $teams,
            "selectedIDS" => $selectedIDS,
        ];
        //dd($parse);
        if ($req->is_api == 1) {
            return Helper::successResponse($parse, 'Successfully get league');
        } else {
            return view("teams.team_assign.edit_assign", $parse);
        }
    }
    public function get_teamBy_league(Request $req)
    {
        $teams = TeamAssign::with([
            "getTeams"
        ])->where("league_id", $req->league_id)->get();
        
        $res = [
            "status" => "success",
            "message" => "success",
            "data" => $teams,
        ];

        return response()->json($res, 200);
    }
    public function get_matchesBy_manager(Request $req)
    {
        $team_id = $req->team_id;
        $manager_id = $req->manager_id;
        $type = $req->type;
        $getMatch = Match::where([
            "match_status" => $type,
            "team_one_id" => $team_id
        ])
        ->orWhere("team_two_id", $team_id)
        ->with([
            "getTeamOne",
            "getTeamTwo",
        ])->get();
        
        if (!empty($getMatch)) {
            foreach ($getMatch as $key => $value) {
                $getMatch[$key]->getTeamOne->manager = TeamManager::where("team_id", $value->getTeamOne->id)->first();
                $getMatch[$key]->getTeamTwo->manager = TeamManager::where("team_id", $value->getTeamOne->id)->first();
            }
        }

        $res = [
            "status" => "success",
            "message" => "success",
            "data" => $getMatch,
        ];
        return response()->json($res, 200);
    }
    public function get_player_team(Request $req)
    {
        $team_id = $req->team_id;
        $loggedin_userID = $this->guard()->user()->id;
        $teamcontract = Contract::where([
            "team_id" => $team_id,
            "status" => "accepted",
        ])->with([
            "getUser"
        ])->get();

        $playerIDS = [];
        
        if (!empty($teamcontract)) {
            foreach ($teamcontract as $contract) {
                array_push($playerIDS, $contract->user_id);
            }
        }

        if (!empty($playerIDS)) {
            $teams = Team::whereIn("id", $playerIDS)
            ->latest()->get();
            
            $data = [
                "teams" => $teams,
                "players" => $teamcontract,
            ];
            $res = [
                "status" => "success",
                "message" => "success",
                "data" => $data,
            ];
        } else {
            $res = [
                "status" => "error",
                "message" => "no player found for this team",
                "data" => [],
            ];
        }
        return  response()->json($res, 200);
    }
    public function getPlayersByTeam(Request $req)
    {
        try {
            $user = $this->guard()->user();
            $data = Contract::where([
                "user_id" => $user->id
            ])->with([
                "getTeam"
            ])->paginate(15);
            return Helper::successResponse($data, 'Successfully get profile');
        } catch (\Exception $e) {
            return Helper::errorResponse(401, $e->getMessage());
        }
    }
}
