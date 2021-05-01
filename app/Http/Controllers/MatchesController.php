<?php

namespace App\Http\Controllers;

use Validator;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Tournament;
use App\TeamAssign;
use App\Match;
use App\Statistic;
use App\VPCSystems;
use App\Position;
use App\PlayerPosition;
use App\PlayerStatistic;
use App\Contract;
use App\Match\MatchScore;
use App\Team;
use App\DivisionTeam;
use Carbon\Carbon;

class MatchesController extends Controller
{
    //
    public function all_match(Request $req)
    {
        if (!empty($req->searhitems)) {
            $string = $req->searhitems;
            //team_name
            $data = Match::with([
                "getTeamOne",
                "getTeamTwo",
                "getLeague"
            ])->where("match_type", "match")
            ->where("id", $string)
            ->orwhereHas("getTeamOne", function ($q) use ($string) {
                $q->where("team_name", "like", "%".$string."%");
            })
            ->orwhereHas("getTeamTwo", function ($q) use ($string) {
                $q->where("team_name", "like", "%".$string."%");
            })
            ->orwhereHas("getLeague", function ($q) use ($string) {
                $q->where("name", "like", "%".$string."%");
            })
            ->paginate(15);
        } else {
            $data = Match::with([
                "getTeamOne",
                "getTeamTwo",
                "getLeague"
            ])->where("match_type", "match")
            ->orderBy('id', "ASC")
            ->whereHas("getLeague", function ($q) {
                $q->where("tournament_type", "league");
            })
            ->paginate(15);
            //dd($data);
        }
        $parse = [
            "menu" => "league",
            "sub_menu" => "match",
            "title" => "All Matches",
            "data" => $data,
        ];
        if ($req->is_api == 1) {
            return Helper::successResponse($parse, 'Successfully get league');
        } else {
            return view('match.all_match', $parse);
        }
    }
    public function add_match(Request $req)
    {
        //$leagues = Tournament::where("tournament_type", "league")->latest()->get();
        $leagues = TeamAssign::with([
            "getLeagues"
        ])->groupBy("league_id")->latest()->get();
        //dd($leagues);
        $parse = [
            "menu" => "league",
            "sub_menu" => "match",
            "title" => "Add Matches",
            "league" => $leagues,
        ];
        
        return view('match.add_match', $parse);
    }
    public function createMatch(Request $req)
    {
        $league_id = $req->league_id;
        $days_interval = (empty($req->days_interval) || $req->days_interval == 0) ? 1 : $req->days_interval;
        $no_reverse = (empty($req->no_reverse)) ? 0 : $req->no_reverse;
        $divion_id = $req->division_id;

        $matches_per_day = (empty($req->matches_per_day) || $req->matches_per_day == 0) ? 1 : $req->matches_per_day;
        $matches_time_interval = $req->matches_time_interval;

        $getTeams = DivisionTeam::where([
            "division_id" => $divion_id,
        ])->with([
            "getTeam",
            "getDivision",
        ])->get();
        //$getTeamsArray = $getTeams->toArray();
        $getTeamsArray = [];
        $org_date = $start_date = date("Y-m-d h:i a", strtotime($req->single_datepicker));
        $datepicker = Carbon::parse($req->single_datepicker);

        foreach ($getTeams as $team_one) {
            array_push($getTeamsArray, $team_one->team_id);
        }
        
        $teams = schedule($getTeamsArray);

        $match = [];
        $countValue = 1;
        $countTime = 1;
        $matches_per_dayCount = 1;
        $days = 0;
        $times = 0;
        $rounds = (($count = count($getTeamsArray)) % 2 === 0 ? $count - 1 : $count) * 2;

        if (!empty($teams)) {
            if ($matches_per_day > 1) {
                // multiround;
                // $teams = schedule($getTeamsArray,($rounds*($matches_per_day-1)));
                if ($no_reverse == 0) {
                    $teams = schedule($getTeamsArray, $rounds);
                }
                
                foreach ($teams as $value) {
                    foreach ($value as $v) {
                        array_push($match, [
                            "league_id" => $league_id,
                            "division_id" => $req->division_id,
                            "team_one_id" => $v[0],
                            "team_two_id" => $v[1],
                            'match_start_date' => @$start_date,
                            'match_end_date' => @$start_date,
                            'match_type' => 'match',
                            'match_start_timestamp' => strtotime($start_date),
                            'match_end_timestamp' => strtotime($start_date)
                        ]);
                    }
                    if ($matches_per_dayCount < $matches_per_day) {
                        $datepicker = Carbon::parse($req->single_datepicker);
                        $times = $times + $matches_time_interval;
                        $start_date = $datepicker->addDays($days)->addMinutes($times)->format('Y-m-d h:i a');
                    } else {
                        $matches_per_dayCount = 0;
                        $times = 0;
                        $datepicker = Carbon::parse($req->single_datepicker);
                        $days = $days + $days_interval;
                        $start_date = $datepicker->addDays($days)->format('Y-m-d h:i a');
                    }
                    $matches_per_dayCount++;
                }
            } else {
                foreach ($teams as $value) {
                    if ($countValue == 0) {
                        $start_date = $datepicker->addDays($days_interval)->format('Y-m-d h:i a');
                    } else {
                        $countValue =  0;
                    }

                    foreach ($value as $v) {
                        array_push($match, [
                            "league_id" => $league_id,
                            "division_id" => $req->division_id,
                            "team_one_id" => $v[0],
                            "team_two_id" => $v[1],
                            'match_start_date' => @$start_date,
                            'match_end_date' => @$start_date,
                            'match_type' => 'match',
                            'match_start_timestamp' => strtotime($start_date),
                            'match_end_timestamp' => strtotime($start_date)
                        ]);
                    }
                }
            }
        }

        // dd($match);

        if (empty($match)) {
            $webmsg = [
                "class" => "danger",
                "message" => "no teams found in that league, goto team assign",
            ];
            return redirect()->back()->with($webmsg)->withInput();
        } else {
            Match::insert($match);
            $webmsg = [
                "class" => "success",
                "message" => count($match)." matches created succssfully",
            ];
            return redirect()->back()->with($webmsg);
        }
    }
    public function delete(Request $req)
    {
        Match::where("id", $req->match_id)->delete();
        MatchScore::where("match_id", $req->match_id)->delete();
        PlayerStatistic::where("match_id", $req->match_id)->delete();
        $webmsg = [
            "class" => "success",
            "message" => "Match deleted succssfully",
        ];
        return redirect()->back()->with($webmsg);
    }

    public function delete_all_match(Request $req)
    {
        $ids = $req->ids;
        $explodeIds = explode(",", $ids);
        
        Match::whereIn("id", $explodeIds)->delete();
        MatchScore::whereIn("match_id", $explodeIds)->delete();
        PlayerStatistic::whereIn("match_id", $explodeIds)->delete();
        $webmsg = [
            "class" => "success",
            "message" => "Match deleted succssfully",
        ];
        return json_encode($webmsg);
    }
    public function edit(Request $req)
    {
        $id = $req->id;

        $league = TeamAssign::with([
            "getLeagues"
        ])->where("league_id", $req->league_id)->first();
        
        $data = Match::where("id", $req->match_id)->where("match_type", "match")->with([
            "getTeamOne",
            "getTeamTwo",
        ])->first();
        
        $vpcsystem = [];
        if (!empty($league)) {
            $vpcsystem = VPCSystems::with([
                "getVpcPlatformAssign" => function ($query) {
                    $query->with('getPlateform');
                },
                "GetGame",
            ])->where("id", $league->getLeagues->vpc_systemid)->first();
        }
        
        $statistic = $position = $contract = [];
        if (!empty($vpcsystem)) {
            $statistic = Statistic::where("game_id", $vpcsystem->game)->get();
            $position = Position::where("game_id", $vpcsystem->game)->get();
        }

        if (!empty($data->getTeamOne)) {
            $teamstatisticdata = Contract::where([
                "team_id" => $data->getTeamOne->id,
                "vpc_system_id" => $league->getLeagues->vpc_systemid,
            ])->with([
                "getUser"
            ])->get();
            if (!empty($teamstatisticdata)) {
                foreach ($teamstatisticdata as $statistic_key => $statistic_value) {
                    $teamstatisticdata[$statistic_key]->statistic = PlayerStatistic::where([
                        "match_id" => $req->match_id,
                        "team_id" => $data->getTeamOne->id,
                        "user_id" => $statistic_value->user_id,
                    ])->with([
                        "getStatistic",
                        "getPosition",
                    ])->get();
                }
            }
            $data->getTeamOne->statistic = $teamstatisticdata;

            $data->getTeamOne->score = MatchScore::where([
                "team_id" => $data->getTeamOne->id,
                "match_id" => $req->match_id,
            ])->first();
        }

        if (!empty($data->getTeamTwo)) {
            $teamstatisticdata = Contract::where([
                "team_id" => $data->getTeamTwo->id,
                "vpc_system_id" => $league->getLeagues->vpc_systemid,
            ])->with([
                "getUser"
            ])->get();
            if (!empty($teamstatisticdata)) {
                foreach ($teamstatisticdata as $statistic_key => $statistic_value) {
                    $teamstatisticdata[$statistic_key]->statistic = PlayerStatistic::where([
                        "match_id" => $req->match_id,
                        "team_id" => $data->getTeamTwo->id,
                        "user_id" => $statistic_value->user_id,
                    ])->with([
                        "getStatistic",
                        "getPosition",
                    ])->get();
                }
            }
            $data->getTeamTwo->statistic = $teamstatisticdata;

            $data->getTeamTwo->score = MatchScore::where([
                "team_id" => $data->getTeamTwo->id,
                "match_id" => $req->match_id,
            ])->first();
        }

        $parse = [
            "menu" => "league",
            "sub_menu" => "match",
            "title" => "Match Details",
            'league' => $league,
            "data" => $data,
            "statistic" => $statistic,
            "position" => $position,
        ];

        // dd($data);

        if ($req->is_api == 1) {
            return Helper::successResponse($parse, 'Successfully get match');
        } else {
            return view('match.edit_match', $parse);
        }
    }
    public function updateScore(Request $req)
    {
        $single_datepicker = Carbon::parse($req->single_datepicker);
        $start_date = $single_datepicker->format('Y-m-d h:i a');

        $match = Match::where("id", $req->match_id)->where("match_type", "match")->update([
            "home_score" => $req->home_score,
            "away_score" => $req->away_score,
            "match_status" => $req->match_status,
            'match_start_date' => @$start_date,
            'match_end_date' => @$start_date,
            'match_start_timestamp' => strtotime($start_date),
            'match_end_timestamp' => strtotime($start_date)
        ]);

        $webmsg = [
            "class" => "success",
            "message" => "match score updated succssfully",
        ];
        return redirect()->back()->with($webmsg);
    }
    public function uploadScore(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'team_id' => 'required',
            'match_id' => 'required',
            'home_score' => 'required',
            'away_score' => 'required',
        ]);

        if ($validator->fails()) {
            if ($req->is_api == 1) {
                return Helper::errorResponse(422, $validator->errors()->first(), $validator->errors());
            } else {
                return redirect()->back()->withErrors($validator->errors());
            }
        } else {
            $checkMatch = Match::where([
                "id" => $req->match_id,
            ])->where("match_type", "match")->first();
            if (!empty($checkMatch)) {
                if ($checkMatch->team_one_id == $req->team_id || $checkMatch->team_two_id == $req->team_id) {
                    $ins = [
                        "team_id" => $req->team_id,
                        "home_score" => $req->home_score,
                        "away_score" => $req->away_score,
                        "match_id" => $req->match_id,
                        "photo" => "",
                        "video_url" => empty($req->video_url) ? "" : $req->video_url,
                    ];
                    $checkScore = MatchScore::where([
                        "match_id" => $req->match_id,
                        "team_id" => $req->team_id,
                    ])->first();
                    if (empty($checkScore)) {
                        if ($req->hasFile('photo')) {
                            $img = $req->file('photo')->store('/', 'public');
                            $img = URL("public/storage/".$img);
                            $ins["photo"] = $img;
                        }
                        MatchScore::create($ins);
                        $this->updateMatchStatus($checkMatch->team_one_id, $checkMatch->team_two_id, $req->match_id);
                        $res = [
                            "status" => "success",
                            "message" => "match score updated successfully",
                            "data" => [],
                        ];
                        return response()->json($res, 200);
                    } else {
                        $res = [
                            "status" => "error",
                            "message" => "score already submitted for this team",
                            "data" => [],
                        ];
                        return response()->json($res, 200);
                    }
                } else {
                    $res = [
                        "status" => "error",
                        "message" => "team id not match with this match",
                        "data" => [],
                    ];
                    return response()->json($res, 200);
                }
            } else {
                $res = [
                    "status" => "error",
                    "message" => "no match found",
                    "data" => [],
                ];
                return response()->json($res, 200);
            }
        }
    }
    public function updateMatchStatus($team_one_id, $team_two_id, $matchid)
    {
        $data = MatchScore::where([
            "match_id" => $matchid,
        ])
        ->whereRaw("(`team_id` = $team_one_id or `team_id` = $team_two_id) and CONCAT(`home_score`,'-', `away_score`) = (select CONCAT(ms2.`home_score`,'-', ms2.`away_score`) FROM `match_scores` ms2 WHERE ms2.`match_id` = $matchid and ms2.`team_id` =  $team_one_id) and CONCAT(`home_score`,'-', `away_score`) = (select CONCAT(ms3.`home_score`,'-', ms3.`away_score`) FROM `match_scores` ms3 WHERE ms3.`match_id` = $matchid and ms3.`team_id` = $team_two_id )")
        ->get();
        $getMatchDetail = MatchScore::where([
            "match_id" => $matchid,
            "team_id" => $team_one_id,
        ])->orWhere('team_id', $team_two_id)->first();
        if (empty($data)) {
            Match::where("id", $matchid)->where("match_type", "match")->update([
                "match_status" => "completed",
                "home_score" => $getMatchDetail->home_score,
                "away_score" => $getMatchDetail->away_score,
            ]);
        } else {
            Match::where("id", $matchid)->where("match_type", "match")->update(["match_status" => "disputed"]);
        }
    }
    public function get_user_match(Request $req)
    {
        $userid = $req->input('user_id');
        $vpcids = $team_ids = [];
        $user_contract = Contract::where([
            'user_id' => $userid,
            "status" => "accepted",
        ])->get();

        $result = [];
        $result["team_contract"] = $user_contract;
        if (!empty($user_contract)) {
            foreach ($user_contract as $contract) {
                array_push($vpcids, $contract->vpc_system_id);
                array_push($team_ids, $contract->team_id);
            }
            

            $result["tournament_detail"] = $myTournament = Tournament::where([
                "tournament_type" => "league",
            ])
            ->whereIn('vpc_systemid', $vpcids)->with([
                "getVPCSystem"
            ])->get();
            
            if (!empty($myTournament)) {
                foreach ($myTournament as $t) {
                    //$playerStatistics = PlayerStatistic::whereIn("team_id", $team_ids)->where("user_id", $userid)->get();
                    $team_details = Match::where([
                        "league_id" => $t->id,
                    ])->where("match_type", "match")
                        ->with([
                            "getTeamOne",
                            "getTeamTwo",
                        ])
                        ->whereIn("team_one_id", $team_ids)
                        ->orWhereIn("team_two_id", $team_ids)
                        ->get();

                    if (!empty($team_details)) {
                        foreach ($team_details as $tk => $tv) {
                            $team_details[$tk]->getTeamOne->user_exists = Contract::where([
                                'user_id' => $userid,
                                "team_id" => $tv->getTeamOne->id,
                            ])->first();
                            $team_details[$tk]->getTeamTwo->user_exists = Contract::where([
                                'user_id' => $userid,
                                "team_id" => $tv->getTeamTwo->id,
                            ])->first();
                        }
                    }
                    $result["team_detail"] = $team_details;
                }
            }
        }
        $api = [
            "status" => "success",
            "message" => "success",
            "data" => $result,
        ];
        return response()->json($api, 200);
    }
    public function get_matches_by_team(Request $req)
    {
        $team_id = $req->team_id;

        $matches = Match::where("match_type", "match")->where("team_one_id", $team_id)
        ->orWhere("team_two_id", $team_id)
        ->with([
            "getTeamOne",
            "getTeamTwo",
            "getLeague"
        ]);
        
        $matches = $matches->latest()->get();

        $res = [
            "status" => "success",
            "message" => "success",
            "data" => $matches,
        ];

        return response()->json($res, 200);
    }
    public function scoreProof(Request $req)
    {
        $check = MatchScore::where("id", $req->proofid)->first();
        if (empty($check)) {
            $res = [
                "status" => "error",
                "message" => "No proof found",
                "data" => [],
            ];
        } else {
            $res = [
                "status" => "success",
                "message" => "success",
                "data" => $check,
            ];
        }
        return response()->json($res, 200);
    }
    public function change_match_status(Request $req)
    {
        $match_id = $req->match_id;
        $status = $req->status;

        Match::where("id", $match_id)->update([
            "match_status" => $status
        ]);

        $res = [
            "status" => "success",
            "message" => "match status updated successfully",
            "data" => [],
        ];

        return response()->json($res, 200);
    }
}
