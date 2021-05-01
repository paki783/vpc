<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use Validator;
use App\VPCSystems;
use App\Tournament;
use App\TeamAssign;
use App\Seasons;
use App\Attachment;
use App\Statistic;
use App\Position;
use App\PlayerPosition;
use App\PlayerStatistic;
use App\Contract;
use App\Match\MatchScore;
use App\Mode;
use App\TournamentMode;
use App\Tournament\TournamentTeam;
use App\Tournament\TournamentGroup;
use App\Tournament\TournamentGroupTeam;
use App\Tournament\TournamentBracket;
use App\Match;
use App\Favourite;
use Carbon\Carbon;

class TournamentController extends Controller
{
    public function all_tournament(Request $req)
    {
        
        if (!empty($req->search_now) and $req->is_api == 1) {
            $api = Tournament::where("tournament_type", "tournament")->
                    where('name', "like", "%".$req->name."%")->get();
            return response()->json($api, 200);
        }
        if (!empty($req->searhitems)) {
            $string = $req->searhitems;
            $data = Tournament::where("tournament_type", "tournament")
            ->where("id", $string)
            ->orwhere('name', "like", "%".$string."%")
            ->latest();
        } else {
            $data = Tournament::where("tournament_type", "tournament")
            ->withCount('getTournamentTeams')
            ->latest();
            if (!empty($req->name)) {
                $data = $data->where('name', "like", "%".$req->name."%");
            }
        }
        $data = $data->paginate(15);
        //dd($data);
        $parse = [
            "menu" => "tournament",
            "sub_menu" => "mtournament",
            "title" => "All Tournament",
            'data' => $data,
        ];
        if ($req->is_api == 1) {
            return Helper::successResponse($parse, 'Successfully get tournament');
        } else {
            return view('tournament.all', $parse);
        }
    }
    public function add_tournament()
    {
        
        $data = [];
        $parse = [
            "menu" => "tournament",
            "sub_menu" => "",
            "title" => "Add Tournament",
            'data' => $data,
        ];
        
        return view('tournament.add_tournament', $parse);
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
                if (!empty($req->team_id)) {
                    TournamentTeam::where("tournament_id", $id)->delete();
                    foreach ($req->team_id as $s) {
                        TournamentTeam::create([
                            "tournament_id" => $id,
                            "team_id" => $s,
                        ]);
                    }
                }

                $webmsg = [
                    "class" => "success",
                    "message" => ucwords($req->tournament_type)." added successfully",
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

                if (!empty($req->team_id)) {
                    TournamentTeam::where("tournament_id", $req->id)->delete();
                    foreach ($req->team_id as $s) {
                        TournamentTeam::create([
                            "tournament_id" => $req->id,
                            "team_id" => $s,
                        ]);
                    }
                }
                $id = $req->id;
                $webmsg = [
                    "class" => "success",
                    "message" => ucwords($req->tournament_type)." updated successfully",
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
            "getTournamentMode" => function ($q) {
                $q->with('getMode');
            },
            "getSeasons",
            "getTournamentTeams" => function ($q) {
                $q->with('getTeam');
            },
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
            "menu" => "tournament",
            "sub_menu" => "mtournament",
            "title" => "Edit Tournament",
            'data' => $data,
            "SeasonsIds" => $SeasonsIds,
        ];
        
        return view('tournament.edit_tournament', $parse);
    }
    public function delete(Request $req)
    {
        $id = $req->id;
        $tournament_id = Tournament::where("id", $id)->first();
        Tournament::where("id", $id)->delete();
        Favourite::where([
            "type_id" => $tournament_id,
            "type" => "tournament"
        ])->delete();
        $webmsg = [
            "class" => "success",
            "message" => "Tournament deleted successfully",
        ];
        return redirect()->back()->with($webmsg);
    }
    public function setgroup(Request $req)
    {
        
        $tournament_id = $req->id;

        $data = Tournament::where("id", $tournament_id)->with([
            "getTournamentTeams" => function ($q) {
                $q->with('getTeam');
            },
            "getTournamentGroupbyTeam" => function ($q) {
                $q->with("getGroupsTeam");
            },
        ])->first();
        $teams = $data->getTournamentTeams;
        //dd($teams);
        $parse = [
            "menu" => "tournament",
            "sub_menu" => "mtournament",
            "title" => "Set Group for ".$data->name,
            'data' => $data,
            "teams" => $teams,
            "tournament_id" => $tournament_id,
        ];
        
        return view('tournament.setGroup', $parse);
    }
    public function saveSetGroup(Request $req)
    {
        
        $groups = $req->team;
        if (!empty($groups)) {
            $count = 1;
            TournamentGroup::where([
                "tournament_id" => $req->tournament_id,
            ])->delete();
            TournamentGroupTeam::where([
                "tournament_id" => $req->tournament_id,
            ])->delete();
            foreach ($groups as $gr) {
                $ins = [
                    "group_name" => 'Group '.$count,
                    "tournament_id" => $req->tournament_id,
                ];
                $groupid = TournamentGroup::create($ins)->id;
                foreach ($gr as $g) {
                    TournamentGroupTeam::create([
                        'group_id' => $groupid,
                        "team_id" => $g,
                        "tournament_id" => $req->tournament_id,
                    ]);
                }
                $count++;
            }
            $webmsg = [
                "class" => "success",
                "message" => "Tournament groups added successfully",
            ];
        } else {
            $webmsg = [
                "class" => "danger",
                "message" => "no teams and groups added",
            ];
        }
        return redirect()->back()->with($webmsg);
    }
    public function getTournamentTeams(Request $req)
    {
        $tournament_id = $req->tournament_id;

        $data = Tournament::where("id", $tournament_id)->with([
            "getTournamentTeams" => function ($q) {
                $q->with('getTeam');
            },
            "getTournamentGroupbyTeam" => function ($q) {
                $q->with("getGroupsTeam");
            },
        ])->first();

        $res = [
            "status" => "success",
            "message" => "success",
            "data" => $data,
        ];

        return response()->json($res, 200);
    }
    public function genGroup(Request $req)
    {
        
        $tournament_id = $req->id;

        $data = Tournament::where("id", $tournament_id)->first();

        $parse = [
            "menu" => "tournament",
            "sub_menu" => "mtournament",
            "title" => "Set Group for ".$data->name,
            'data' => $data,
            "tournament_id" => $tournament_id,
        ];

        return view('tournament.genGroup', $parse);
    }
    public function saveGenGroup(Request $req)
    {
        
        $league_id = $req->tournament_id;
        $days_interval = (empty($req->days_interval) || $req->days_interval == 0) ? 1 : $req->days_interval;
        $no_reverse = (empty($req->no_reverse)) ? 0 : $req->no_reverse;

        $matches_per_day = (empty($req->matches_per_day) || $req->matches_per_day == 0) ? 1 : $req->matches_per_day;
        $matches_time_interval = $req->matches_time_interval;
        
        $org_date = $start_date = date("Y-m-d h:i a", strtotime($req->single_datepicker));
        $datepicker = Carbon::parse($req->single_datepicker);

        $getTeams = Tournament::where("id", $league_id)->with([
            "getTournamentGroupbyTeam" => function ($q) {
                $q->with("getGroupsTeam");
            },
        ])->first();

        $match = [];

        if (!empty($getTeams)) {
            $teamsweaper = 1;
            if (!empty($getTeams->getTournamentGroupbyTeam)) {
                foreach ($getTeams->getTournamentGroupbyTeam as $groups) {
                    if (!empty($groups->getGroupsTeam)) {
                        $getTeamsArray = [];
                        foreach ($groups->getGroupsTeam as $v) {
                            $getTeamsArray[] = $v->team_id;
                        }
                        $teams = schedule($getTeamsArray);
                        
                        $countValue = 1;
                        $countTime = 1;
                        $matches_per_dayCount = 1;
                        $days = 0;
                        $times = 0;
                        $rounds = (($count = count($getTeamsArray)) % 2 === 0 ? $count - 1 : $count) * 2;
                        $start_date = date("Y-m-d h:i a", strtotime($req->single_datepicker));
                        $datepicker = Carbon::parse($req->single_datepicker);

                        if (!empty($teams)) {
                            if ($matches_per_day > 1) {
                                // multiround;
                                if ($no_reverse == 0) {
                                    $teams = schedule($getTeamsArray, $rounds);
                                }
                                
                                foreach ($teams as $value) {
                                    foreach ($value as $v) {
                                        array_push($match, [
                                            "league_id" => $league_id,
                                            "division_id" => $groups->id,
                                            "team_one_id" => $v[0],
                                            "team_two_id" => $v[1],
                                            'match_start_date' => @$start_date,
                                            'match_end_date' => @$start_date,
                                            'match_type' => "tournament",
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
                                $teamsweaper++;
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
                                            "division_id" => $groups->id,
                                            "team_one_id" => $v[0],
                                            "team_two_id" => $v[1],
                                            'match_start_date' => @$start_date,
                                            'match_end_date' => @$start_date,
                                            'match_type' => "tournament",
                                            'match_start_timestamp' => strtotime($start_date),
                                            'match_end_timestamp' => strtotime($start_date)
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                if (empty($match)) {
                    $webmsg = [
                        "class" => "danger",
                        "message" => "no teams found in that league, goto team assign",
                    ];
                    return redirect()->back()->with($webmsg)->withInput();
                } else {
                    ;
                    Match::insert($match);
                    $webmsg = [
                        "class" => "success",
                        "message" => count($match)." matches created succssfully",
                    ];
                    return redirect()->back()->with($webmsg);
                }
            } else {
                $webmsg = [
                    "class" => "danger",
                    "message" => "no group set in the teams",
                ];
            }
        } else {
            $webmsg = [
                "class" => "danger",
                "message" => "error while getting the tournament information",
            ];
        }
        return redirect()->back()->with($webmsg);
    }
    public function all_tournament_matches(Request $req)
    {
        
        $id = $req->id;
        
        $data = Match::with([
            "getTeamOne",
            "getTeamTwo",
            "getLeague",
            "getGroupName" => function ($q) use ($id) {
                $q->where('tournament_id', $id)->with("groupName");
            }
        ])->where("match_type", "tournament")
        ->where("league_id", $id)
        ->paginate(15);

        $parse = [
            "menu" => "tournament",
            "sub_menu" => "",
            "title" => "All Tournament Matches",
            "data" => $data,
        ];
        // dd($data->toArray());
        return view('tournament.group_matches', $parse);
    }

    public function edit_group_tournament_matches(Request $req)
    {
        $id = $req->id;
        
        

        $league = TournamentTeam::with([
            "getTournament"
        ])
        ->where("tournament_id", $req->league_id)->first();

        $data = Match::where("id", $req->match_id)->where("match_type", "tournament")->with([
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
            ])->where("id", $league->getTournament->vpc_systemid)->first();
        }

        $statistic = $position = $contract = [];

        if (!empty($vpcsystem)) {
            $statistic = Statistic::where("game_id", $vpcsystem->game)->get();
            $position = Position::where("game_id", $vpcsystem->game)->get();
        }

        if (!empty($data->getTeamOne)) {
            $teamstatisticdata = Contract::where([
                "team_id" => $data->getTeamOne->id,
                "vpc_system_id" => $league->getTournament->vpc_systemid,
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
                "vpc_system_id" => $league->getTournament->vpc_systemid,
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
            "menu" => "tournament",
            "sub_menu" => "",
            "title" => "Tournament Match Details",
            'league' => $league,
            "data" => $data,
            "statistic" => $statistic,
            "position" => $position,
        ];

        // dd($data->toArray());

        if ($req->is_api == 1) {
            return Helper::successResponse($parse, 'Successfully get match');
        } else {
            return view('tournament.edit_group_tournament_match', $parse);
        }
    }

    public function updateGroupTournamentScore(Request $req)
    {
        $single_datepicker = Carbon::parse($req->single_datepicker);
        $start_date = $single_datepicker->format('Y-m-d h:i a');

        $match = Match::where("id", $req->match_id)->where("match_type", "tournament")->update([
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
            "message" => "Tournament Match score updated succssfully",
        ];
        return redirect()->back()->with($webmsg);
    }

    public function setBracket(Request $req)
    {
        
        $tournament_id = $req->id;
        $data = Tournament::where("id", $tournament_id)->with([
            "getTournamentTeams" => function ($q) {
                $q->with('getTeam');
            },
        ])->first();
        $parse = [
            "menu" => "tournament",
            "sub_menu" => "",
            "title" => "Set Bracket",
            "data" => $data,
            "tournament_id" => $tournament_id,
        ];
        return view('tournament.setBracket', $parse);
    }
    public function createMatchBracket(Request $req)
    {
        
        $tournament_id = $req->tournament_id;
        $validator = Validator::make($req->all(), [
            'round_name' => 'required',
            'single_datepicker' => 'required',
            'team_id' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } else {
            $tournamentBracket = TournamentBracket::where("tournament_id", $tournament_id)->first();
            if (isset($tournamentBracket) && count($tournamentBracket) > 0) {
                $webmsg = [
                    "class" => "danger",
                    "message" => "Already Created Tournament Bracket.",
                ];
                return redirect()->back()->with($webmsg)->withInput();

            }

            $team_idCount = count($req->team_id);
            if (@$req->team_id[0] != '') {
                if ($team_idCount%2==1) {
                    return redirect()->back()->withErrors(['Team Selected in Even Formate.'])->withInput();
                }
            }
            $team_shuffle = (empty($req->team_shuffle)) ? 0 : $req->team_shuffle;
            $is_shuffle = false;
            if ($team_shuffle == 1) {
                $is_shuffle = true;
            }

            // dd($req->all());
            
            $teams = schedule($req->team_id, 1, $is_shuffle);
            $datepicker = Carbon::parse($req->single_datepicker);
            $match = [];
            $TournamentBracket = [];
            $rounds = (int)Helper::dividedByCount($team_idCount, 2);
            $round_stag = $team_idCount/2;
            // dd($teams);
            $matchRecordId = [];
            if (!empty($teams)) {
                foreach ($teams as $value) {
                    $roundStage = $team_idCount;
                    $TournamentBracket = TournamentBracket::create([
                        "tournament_id" => $tournament_id,
                        "round_name" => $req->round_name,
                        "round" => $rounds,
                        "round_stage" => "1/".$round_stag,
                        "start_date" => $datepicker,
                        "start_date_timestamp" => date("Y-m-d h:i:a", strtotime($datepicker)),
                    ]);

                    foreach ($value as $v) {
                        $match = [
                            "league_id" => $tournament_id,
                            "division_id" => $TournamentBracket->id,
                            'team_one_id' => $v[0],
                            'team_two_id' => $v[1],
                            'match_start_date' => $datepicker,
                            'match_end_date' => $datepicker,
                            'match_type' => 'bracket',
                            'match_start_timestamp' => strtotime($datepicker),
                            'match_end_timestamp' => strtotime($datepicker),
                        ];
                        $matchCreate = Match::create($match);
                        $matchRecordId[] = $matchCreate->id; 
                    }
                }

                // Insert Matches
                // Match::insert($match);

                $webmsg = [
                    "class" => "success",
                    "message" => "Matches created succssfully",
                ];

            } else {
                $webmsg = [
                    "class" => "danger",
                    "message" => "no teams found in that league, goto team assign",
                ];
                return redirect()->back()->with($webmsg)->withInput();
            }

            $match = [];
            if ($rounds > 1) {
                $roundStageCount = 2;
                $roundsCount = 1;
                for ($i=1; $i < ($rounds); $i++) {
                    $matchRecordIdExp = array_chunk($matchRecordId,2);
                    $matchRecordId = [];

                    $round_stag /= 2;
                    $TournamentBracket = TournamentBracket::create([
                        "tournament_id" => $tournament_id,
                        "round_name" => $req->round_name,
                        "round" => ($rounds-$roundsCount),
                        "round_stage" => "1/".$round_stag,
                        "start_date" => $datepicker,
                        "start_date_timestamp" => date("Y-m-d h:i:a", strtotime($datepicker)),
                    ]);
                    $roundsCount++;
                    $roundStageCount *= 2;
                    // Make Matching
                    for ($j=1; $j <= ($round_stag); $j++) {
                        $match = [
                            "league_id" => $tournament_id,
                            "division_id" => $TournamentBracket->id,
                            'team_one_id' => null,
                            'team_two_id' => null,
                            'match_start_date' => $datepicker,
                            'match_end_date' => $datepicker,
                            'match_type' => 'bracket',
                            'bracket_team' => ($matchRecordIdExp[($j-1)] != null)?implode(',',$matchRecordIdExp[($j-1)]):null,
                            'match_start_timestamp' => strtotime($datepicker),
                            'match_end_timestamp' => strtotime($datepicker),
                        ];

                        $matchCreate = Match::create($match);
                        $matchRecordId[] = $matchCreate->id;

                    }
                }

                $webmsg = [
                    "class" => "success",
                    "message" => "Matches created succssfully",
                ];
            }

            // dd(123);
            // if (empty($match)) {
            //     $webmsg = [
            //         "class" => "danger",
            //         "message" => "no teams found in that league, goto team assign",
            //     ];
            //     return redirect()->back()->with($webmsg)->withInput();
            // } else {
            //     $webmsg = [
            //         "class" => "success",
            //         "message" => "Matches created succssfully",
            //     ];
            //     return redirect()->back()->with($webmsg);
            // }

            // $webmsg = [
            //     "class" => "success",
            //     "message" => count($match)." matches created succssfully",
            // ];

            // $webmsg = [ 
            //     "class" => "danger",
            //     "message" => "no teams found in that league, goto team assign",
            // ];

            return redirect()->back()->with($webmsg);
        }
    }
    public function getMatcheByBracket(Request $req)
    {
        $tournament_id = $req->id;
        $data = TournamentBracket::where([
            "tournament_id" => $tournament_id,
        ])
        ->with([
            "getTournamentGroupTeamHome" => function ($q) {
                $q->with('getTeam');
            },
            "getTournamentGroupTeamAway" => function ($q) {
                $q->with('getTeam');
            },
        ])
        ->paginate(15);
        $parse = [
            "menu" => "tournament",
            "sub_menu" => "",
            "title" => "All Tournament Bracket",
            "data" => $data,
            "tournament_id" => $tournament_id,
        ];
        if ($req->is_api == 1) {
            return response()->json($parse, 200);
        } else {
            return view('tournament.all_bracket', $parse);
        }
    }

    public function bracketMatchStagView(Request $req)
    {
        $data = [];
            
            $data = Match::with([
                "getBracketDivision",
                "getTournamentGroupTeamOne" => function ($q) {
                    $q->with('getTeam');
                },
                "getTournamentGroupTeamTwo" => function ($q) {
                    $q->with('getTeam');
                },
                "getLeague"
            ])->where("match_type", "bracket")
            ->where("league_id", $req->tournament)
            ->where("division_id", $req->id)
            ->orderBy('id', "ASC")
            ->get();

            $tournamentBracket = TournamentBracket::find($req->id);
            // dd($tournamentBracket);
            // dd($data);
        $parse = [
            "menu" => "tournament",
            "sub_menu" => "",
            "title" => "All Inner Tournament Bracket",
            "tournamentBracket" => $tournamentBracket,
            "data" => $data,
        ];
        
        if ($req->is_api == 1) {
            return Helper::successResponse($parse, 'Successfully get bracket');
        } else {
            return view('tournament.bracket.all_inner_bracket', $parse);
        }
    }

    public function bracketMatchEdit(Request $req)
    {
        $id = $req->id;
        
        

        $data = Match::where("id", $req->id)->where("match_type", "bracket")->with([
            "getBracketDivision",
            "getLeague"
        ])->first();
        
        $league = Tournament::where("id", $data->league_id)->with([
            "getTournamentTeams" => function ($q) {
                $q->with('getTeam');
            },
        ])->first();

        $parse = [
            "menu" => "league",
            "sub_menu" => "match",
            "title" => "Match Details",
            "data" => $data,
            "league" => $league,
        ];

        // dd($data);

        if ($req->is_api == 1) {
            return Helper::successResponse($parse, 'Successfully get match');
        } else {
            return view('tournament.bracket.edit_inner_bracket', $parse);
        }
    }

    public function bracketMatchUpdate(Request $req)
    {
        // dd($req->all());

        $single_datepicker = Carbon::parse($req->single_datepicker);
        $start_date = $single_datepicker->format('Y-m-d h:i a');

        $match = Match::find($req->match_id);
        $match->update([
            "team_one_id" => $req->home_team_id,
            "team_two_id" => $req->away_team_id,
            "home_score" => $req->home_score,
            "away_score" => $req->away_score,
            "match_status" => $req->match_status,
            'match_start_date' => @$start_date,
            'match_end_date' => @$start_date,
            'match_start_timestamp' => strtotime($start_date),
            'match_end_timestamp' => strtotime($start_date)
        ]);
        
        $team_win = '';
        if ($match->home_score > $match->away_score) {
            $team_win = $match->team_one_id;
        }else if ($match->home_score < $match->away_score) {
            $team_win = $match->team_two_id;
        }

        if($team_win != ""){
            
            $tournament_bracket =$match->division_id;
            $tournament_id = $match->league_id;
            $tournamentBracket = TournamentBracket::where('id',$tournament_bracket)->first();
            
            $round = $tournamentBracket->round;
            if ($round > 1) {

                $matchFound = Match::where('match_type', 'bracket')->whereRaw("find_in_set('".$match->id."',bracket_team)")->first();
                
                $matchFoundExp = explode(',',$matchFound->bracket_team);

                if(@$matchFoundExp[0] == $match->id){
                    $matchFound->update(['team_one_id' => $team_win]);
                }else if(@$matchFoundExp[1] == $match->id){
                    $matchFound->update(['team_two_id' => $team_win]);
                }
            }
        }


        $webmsg = [
            "class" => "success",
            "message" => "bracket match score updated succssfully",
        ];
        return redirect()->back()->with($webmsg);
    }

    public function deleteMatcheByBracket(Request $request)
    {
        $input = $request->all();
        $data = TournamentBracket::where([
            "tournament_id" => $input['id'],
        ])->delete();
        Match::where("league_id", $input['id'])
            ->where('match_type', 'bracket')
            ->delete();
        // TournamentBracket::where([
        //     "id" => $tournament_id,
        // ])->delete();
        $webmsg = [
            "class" => "success",
            "message" => "Successfully Remove Brackets.",
        ];
        return redirect()->back()->with($webmsg);
    }

    public function bracketMatchdelete()
    {
        $data = TournamentBracket::where([
            "id" => $tournament_id,
        ])->first();
        Match::where("team_one_id", $data->home_team)
            ->orwhere('team_two_id', $data->away_team)
            ->delete();
        TournamentBracket::where([
            "id" => $tournament_id,
        ])->delete();
        $webmsg = [
            "class" => "success",
            "message" => "Match removed from this bracked",
        ];
        return redirect()->back()->with($webmsg);
    }
}
