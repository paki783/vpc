<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Achievement\Achievement;
use App\Achievement\AssignAward;
use App\Helpers\Helper;
use App\Attachment;
use App\Division;
use Validator;

class AwardController extends Controller
{
    //
    public function all_awards(Request $req)
    {
        $data = Achievement::where('status', "award")->with([
            "getPicture",
        ]);
        if ($req->is_api == 1 && !empty($req->search_now)) {
            $data->where("achievement_name", "like", "%".$req->name."%");
        }
        if (!empty($req->searhitems)) {
            $string = $req->searhitems;
            $data = $data->where("achievement_name", "like", "%".$string."%")
            ->orwhere("id", $string);
        }
        $data = $data->paginate(15);
        $parse = [
            "menu" => "awards",
            "sub_menu" => "cawards",
            "title" => "All Trophy",
            'data' => $data,
        ];
        if ($req->is_api == 1) {
            return response()->json($parse, 200);
        }
        return view('awards/all_awards', $parse);
    }
    public function add_awards(Request $req)
    {
        $parse = [
            "menu" => "awards",
            "sub_menu" => "cawards",
            "title" => "Add Trophy",
            'data' => [],
        ];
        return view('awards/add_awards', $parse);
    }
    public function saveAward(Request $req)
    {
        $id = $req->id;
        if ($id == 0) {
            $validator = Validator::make($req->all(), [
                'award_name' => 'required',
                'award_logo' => 'image',
            ]);
            if ($validator->fails()) {
                return Helper::errorResponse(422, $validator->errors()->first(), $validator->errors());
            } else {
                $acid = Achievement::create([
                    "achievement_name" => $req->award_name,
                    "status" => "award"
                ])->id;

                if ($req->hasFile('award_logo')) {
                    $img = $req->file('award_logo')->store('/', 'public');
                    $img = URL("public/storage/".$img);

                    Attachment::create([
                        "type_id" => $acid,
                        "type" => "award",
                        "photo" => $img,
                        "video_url" => "",
                        "model_name" => "award",
                    ]);
                }
                $webmsg = [
                    "class" => "success",
                    "message" => "Trophy added successfully",
                ];
                return redirect()->back()->with($webmsg);
            }
        } else {
            $validator = Validator::make($req->all(), [
                'award_name' => 'required',
            ]);
            if ($validator->fails()) {
                return Helper::errorResponse(422, $validator->errors()->first(), $validator->errors());
            } else {
                Achievement::where("id", $id)->update([
                    "achievement_name" => $req->award_name,
                ]);
                if ($req->hasFile('award_logo')) {
                    $img = $req->file('award_logo')->store('/', 'public');
                    $img = URL("public/storage/".$img);
                    Attachment::where([
                        "type_id" => $id,
                        "type" => "award",
                        "model_name" => "award",
                    ])->delete();
                    Attachment::create([
                        "type_id" => $acid,
                        "type" => "award",
                        "photo" => $img,
                        "video_url" => "",
                        "model_name" => "award",
                    ]);
                }
                $webmsg = [
                        "class" => "success",
                    "message" => "Trophy updated successfully",
                ];
                return redirect()->back()->with($webmsg);
            }
        }
    }
    public function delete(Request $req)
    {
        $id = $req->id;
        Attachment::where([
            "type_id" => $id,
            "type" => "award",
            "model_name" => "award",
        ])->delete();
        Achievement::where([
            'status' => "award",
            "id" => $id,
        ])->delete();
        AssignAward::where('award_id', $id)->delete();

        $webmsg = [
            "class" => "success",
            "message" => "Trophy deleted successfully",
        ];
        return redirect()->back()->with($webmsg);
    }
    public function edit(Request $req)
    {
        $id = $req->id;
        $data = Achievement::where('id', $id)->with([
            "getPicture",
        ])->first();

        $parse = [
            "menu" => "awards",
            "sub_menu" => "cawards",
            "title" => "Edit Trophy",
            'data' => $data,
        ];
        return view('awards/edit_awards', $parse);
    }
    public function all_assign(Request $req)
    {
        $data = AssignAward::with([
            "getLeague",
            "getDivision",
            "getTeams",
            "getAward",
        ])->groupBy(['league_id', "award_id"])->paginate(15);

        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $data[$key]->teams = AssignAward::where([
                    "league_id" => $value->league_id,
                ])->with([
                    "getTeams"
                ])->get();
            }
        }
        $parse = [
            "menu" => "awards",
            "sub_menu" => "assign_awards",
            "title" => "All Assign Trophies",
            'data' => $data,
        ];
        return view('awards/assign_all', $parse);
    }
    public function add_assign_awards(Request $req)
    {
        $parse = [
            "menu" => "awards",
            "sub_menu" => "assign_awards",
            "title" => "Assign Trophy",
            'data' => [],
        ];
        return view('awards/assign_add', $parse);
    }
    public function save_AssignAward(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'league_id' => 'required',
            'division_id' => 'required',
            'team_id' => 'required',
            'awards' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } else {
            if ($req->id == 0) {
                AssignAward::create([
                    'league_id' => $req->league_id,
                    'division_id' => $req->division_id,
                    'team_id' => $req->team_id,
                    'award_id' => $req->awards,
                ]);
            } else {
                AssignAward::find($req->id)->update([
                    'league_id' => $req->league_id,
                    'division_id' => $req->division_id,
                    'team_id' => $req->team_id,
                    'award_id' => $req->awards,
                ]);
            }

            $webmsg = [
                "class" => "success",
                "message" => "Trophy assign to the team successfully",
            ];
            return redirect()->back()->with($webmsg);
        }
    }
    public function delete_assign_awards(Request $req)
    {
        $id = $req->id;

        AssignAward::where("id", $id)->delete();

        $webmsg = [
            "class" => "success",
            "message" => "Trophy deleted from this league successfully",
        ];
        return redirect()->back()->with($webmsg);
    }
    public function edit_assign_awards(Request $req)
    {
        $id = $req->id;
        $data = AssignAward::with([
            "getTeams",
            "getLeague",
            "getAward",
            "getDivision"
        ])
        ->where("id", $id)
        ->groupBy(['league_id', "award_id"])
        ->first();

        $division = Division::where('league_id', $data->getLeague->id)->get();

        $parse = [
            "menu" => "awards",
            "sub_menu" => "assign_awards",
            "title" => "Edit assign trophy",
            'division' => $division,
            'data' => $data,
        ];
        return view('awards/assign_edit', $parse);
    }
    public function getAwardbyteam(Request $req)
    {
        $team_id = $req->team_id;

        $data = AssignAward::with([
            "getLeague",
            "getAward",
            "getTeams",
        ])
        ->where("team_id", $team_id)
        ->groupBy(['league_id', "award_id"])
        ->get();

        $res = [
            "status" => "success",
            "message" => "success",
            "data" => $data,
        ];

        return response()->json($res, 200);
    }
    public function getAwardbyleague(Request $req)
    {
        $league_id = $req->league_id;
        $data = AssignAward::with([
            "getLeague",
            "getAward",
        ])
        ->where("league_id", $league_id)
        ->groupBy(['league_id', "award_id"])
        ->first();
        if (!empty($data)) {
            $data->teams = AssignAward::where([
                "league_id" => $league_id,
            ])->with([
                "getAllTeams"
            ])->get();
        }

        $res = [
            "status" => "success",
            "message" => "success",
            "data" => $data,
        ];

        return response()->json($res, 200);
    }
}
