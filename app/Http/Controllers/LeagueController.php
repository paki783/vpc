<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use Validator;
use App\VPCSystems;
use App\Tournament;
use App\Seasons;
use App\Mode;
use App\TournamentMode;
use App\Favourite;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use App\Achievement\AssignAward;
use App\Division;
use App\Leaderboard\LeaderboardLeague;
use App\Match;
use App\Attachment;

class LeagueController extends Controller
{
    public function guard()
    {
        return Auth::guard('api');
    }
    public function all_league(Request $req)
    {
        if (!empty($req->search_now) and $req->is_api == 1) {
            $api = Tournament::where("tournament_type", "league")->
            where('name', "like", "%".$req->name."%")->get();
            $res = [
                "status" => "success",
                "message" => "success",
                "data" => $api,
            ];
            return response()->json($res, 200);
        }
        if (!empty($req->searhitems)) {
            $string = $req->searhitems;

            $data = Tournament::where("tournament_type", "league")->with([
                "getVPCSystem",
                "getTournamentMode" => function ($query) {
                    $query->with('getMode');
                },
                "getSeasons",
                "getDivision",
            ])
            ->where("id", $string)
            ->orwhere("name", "like", "%".$string."%")
            ->orwhereHas("getVPCSystem", function ($q) use ($string) {
                $q->where("syste_name", "like", "%".$string."%");
            })
            ->latest();
        } else {
            $data = Tournament::where("tournament_type", "league")->with([
                "getVPCSystem",
                "getTournamentMode" => function ($query) {
                    $query->with('getMode');
                },
                "getSeasons",
                "getDivision",
            ])->latest();
        }
        $data = $data->paginate(15);
        //dd($data);
        $parse = [
            "menu" => "league",
            "sub_menu" => "aleague",
            "title" => "All League",
            "regions" => Helper::regions(),
            'data' => $data,
        ];
        if ($req->is_api == 1) {
            return Helper::successResponse($parse, 'Successfully get league');
        } else {
            return view('leagues.all', $parse);
        }
    }
    public function add_league()
    {
        $data = [];
        $parse = [
            "menu" => "league",
            "sub_menu" => "aleague",
            "title" => "Add League",
            'data' => $data,
            "VPCSystems" => VPCSystems::latest()->get(),
            "modes" => Mode::latest()->get(),
        ];
        
        return view('leagues.add', $parse);
    }
    public function saveTournament(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'vpc_systemid' => 'required',
            'modeid' => 'required',
            'seasons' => 'required',
            'tournament_type' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } else {
            $ins = [
                "name" => $req->name,
                "description" => @$req->description,
                "vpc_systemid" => $req->vpc_systemid,
                "tournament_type" => $req->tournament_type,
            ];
            if ($req->id == 0) {
                $ins["logo"] = $ins["banner"] = "";
                if ($req->hasFile('logo')) {
                    $img = $req->file('logo')->store('/', 'public');
                    $img = URL("public/storage/".$img);
                    $ins["logo"] = $img;
                }
                if ($req->hasFile('banner')) {
                    $img = $req->file('banner')->store('/', 'public');
                    $img = URL("public/storage/".$img);
                    $ins["banner"] = $img;
                }

                $id = Tournament::create($ins)->id;

                if (!empty($req->seasons)) {
                    Seasons::where("tournament_id", $id)->delete();
                    foreach ($req->seasons as $s) {
                        Seasons::create([
                            "tournament_id" => $id,
                            "season" => $s,
                        ]);
                    }
                }
                if (!empty($req->modeid)) {
                    TournamentMode::where("tournament_id", $id)->delete();
                    foreach ($req->modeid as $s) {
                        TournamentMode::create([
                            "tournament_id" => $id,
                            "modeid" => $s,
                        ]);
                    }
                }

                $webmsg = [
                    "class" => "success",
                    "message" => "League added successfully",
                ];
            } else {
                if ($req->hasFile('logo')) {
                    $img = $req->file('logo')->store('/', 'public');
                    $img = URL("public/storage/".$img);
                    $ins["logo"] = $img;
                }
                if ($req->hasFile('banner')) {
                    $img = $req->file('banner')->store('/', 'public');
                    $img = URL("public/storage/".$img);
                    $ins["banner"] = $img;
                }

                Tournament::where("id", $req->id)->update($ins);
                if (!empty($req->seasons)) {
                    Seasons::where("tournament_id", $req->id)->delete();
                    foreach ($req->seasons as $s) {
                        Seasons::create([
                            "tournament_id" => $req->id,
                            "season" => $s,
                        ]);
                    }
                }
                if (!empty($req->modeid)) {
                    TournamentMode::where("tournament_id", $req->id)->delete();
                    foreach ($req->modeid as $s) {
                        TournamentMode::create([
                            "tournament_id" => $req->id,
                            "modeid" => $s,
                        ]);
                    }
                }
                $id = $req->id;

                $webmsg = [
                    "class" => "success",
                    "message" => "League updated successfully",
                ];
            }
            if ($req->hasFile('rules')) {
                $img = $req->file('rules')->store('/', 'public');
                $img = URL("public/storage/".$img);
                Attachment::where([
                    "type" => "league_rules",
                    "type_id" => $id,
                ])->delete();
                Attachment::create([
                    "type" => "league_rules",
                    "type_id" => $id,
                    "photo" => $img,
                    "video_url" => "",
                    "model_name" => "league_rules",
                ]);
            }
            return redirect()->back()->with($webmsg);
        }
    }
    public function edit(Request $req)
    {
        $id = $req->id;
        $data = Tournament::where("id", $id)->with([
            "getVPCSystem",
            "getTournamentMode",
            "getSeasons",
            "getLeagueRules",
        ])->first();
        //dd($data);
        $tournamentModeids = $SeasonsIds = [];
        if (!empty($data)) {
            if (!empty($data->getTournamentMode)) {
                foreach ($data->getTournamentMode as $mode) {
                    $tournamentModeids[] = $mode->modeid;
                }
            }
            if (!empty($data->getSeasons)) {
                foreach ($data->getSeasons as $season) {
                    $SeasonsIds[] = $season->season;
                }
            }
        }
        $parse = [
            "menu" => "league",
            "sub_menu" => "aleague",
            "title" => "Edit League",
            'data' => $data,
            "VPCSystems" => VPCSystems::latest()->get(),
            "modes" => Mode::latest()->get(),
            "tournamentModeids" => $tournamentModeids,
            "SeasonsIds" => $SeasonsIds,
        ];
        if ($req->is_api == 1) {
            return Helper::successResponse($parse, 'Successfully get league');
        } else {
            return view('tournament.edit_tournament', $parse);
        }
    }
    public function delete(Request $req)
    {
        $id = $req->id;
        $tournament_id = Tournament::where("id", $id)->first();
        Tournament::where("id", $id)->delete();
        Favourite::where([
            "type_id" => $tournament_id,
            "type" => "league"
        ])->delete();
        $webmsg = [
            "class" => "success",
            "message" => "League deleted successfully",
        ];
        return redirect()->back()->with($webmsg);
    }
    public function api_leagueall(Request $req)
    {
        //mode, plateform, region
        $modefilter = [];
        $vpcsystemid = [];
        if (!empty($req->mode_id)) {
            $mode_id = TournamentMode::where("modeid", $req->mode_id)->get();
            if (!empty($mode_id)) {
                foreach ($mode_id as $id) {
                    array_push($modefilter, $id->tournament_id);
                }
            }
        }
        if (!empty($req->regions)) {
            $regions = VPCSystems::where('region', $req->regions)->get();
            if (!empty($regions)) {
                foreach ($regions as $r) {
                    array_push($vpcsystemid, $r->id);
                }
            }
        }

        $data = Tournament::where("tournament_type", "league")->with([
            "getVPCSystem",
            "getTournamentMode" => function ($query) {
                $query->with('getMode');
            },
            "getSeasons",
            "getDivision",
        ]);
        if (!empty($modefilter)) {
            $data = $data->orWhereIn('id', $modefilter);
        }
        if (!empty($vpcsystemid)) {
            $data = $data->whereIn('vpc_systemid', $vpcsystemid);
        }
        if($req->game_id != 0 || !empty($req->game_id)){
            $game_id = $req->game_id;
            $data = $data->whereHas('getVPCSystem', function ($query) use($game_id) {
                $query->where("game", $game_id);
            });
        }
        $data = $data->latest()->paginate(15);
        $user = $this->guard()->user();
        if (!empty($user)) {
            if (!empty($data)) {
                foreach ($data as $k => $v) {
                    $data[$k]->isFavourite = Favourite::where([
                        "user_id" => $user->id,
                        "type_id" => $v->id
                    ])
                    ->where("type", "league")
                    ->orWhere("type", "tournament")
                    ->count();
                }
            }
        }
        $parse = [
            "menu" => "league",
            "sub_menu" => "aleague",
            "title" => "All League",
            "regions" => Helper::regions(),
            'data' => $data,
        ];
        return Helper::successResponse($parse, 'Successfully get league');
    }
}
