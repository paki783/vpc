<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Division;
use App\VPCSystems;
use Validator;
use App\TeamAssign;
use App\Match;
use App\DivisionTeam;

class DivisionController extends Controller
{
    //
    public function all_division(Request $req)
    {
        if (!empty($req->search_now) and $req->is_api == 1) {
            $api = Division::where("divisions_name", "like", "%".$req->divisions_name."%")->get();
            return response()->json($api, 200);
        }
        if (!empty($req->searhitems)) {
            $string = $req->searhitems;

            $data = Division::latest()->with([
                "getLeagues",
                "getDivisionTeams" => function ($q) {
                    $q->with("getTeam");
                },
            ])
            ->where("id", $string)
            ->orwhere("divisions_name", "like", "%".$string."%")
            ->orwhereHas("getLeagues", function ($q) use ($string) {
                $q->where("name", "like", "%".$string."%");
            });
        } else {
            $data = Division::latest()->with([
                "getLeagues",
                "getDivisionTeams" => function ($q) {
                    $q->with("getTeam");
                },
            ]);
        }
        $data = $data->paginate(15);
        $parse = [
            "menu" => "division",
            "sub_menu" => "",
            "title" => "All Division",
            'data' => $data,
        ];
        
        return view('division.all_division', $parse);
    }
    public function add_division(Request $req)
    {
        $parse = [
            "menu" => "division",
            "sub_menu" => "",
            "title" => "Add Division",
        ];
        
        return view('division.add_division', $parse);
    }
    public function saveDivision(Request $req)
    {
        $id = $req->id;
        if ($id == 0) {
            $validator = Validator::make($req->all(), [
                'divisions_name' => 'required',
                'league_id' => 'required',
                'team_id' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            } else {
                $ins = [
                    "divisions_name" => $req->divisions_name,
                    "league_id" => $req->league_id,
                ];
                if ($req->hasFile('picture')) {
                    $img = $req->file('picture')->store('/', 'public');
                    $ins["picture"] = URL("public/storage/".$img);
                }
                $id = Division::create()->id;
                if (!empty($req->team_id)) {
                    DivisionTeam::where("division_id", $id)->delete();
                    foreach ($req->team_id as $teams) {
                        DivisionTeam::create([
                            "division_id" => $id,
                            "team_id" => $teams
                        ]);
                    }
                }
                $webmsg = [
                    "class" => "success",
                    "message" => "Division added successfully",
                ];
                return redirect()->back()->with($webmsg);
            }
        } else {
            $validator = Validator::make($req->all(), [
                'divisions_name' => 'required',
                'league_id' => 'required',
                'team_id' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            $team_idCount = count($req->team_id);
            if (@$req->team_id[0] != '') {
                if ($team_idCount%2==1) {
                    return redirect()->back()->withErrors(['Team Selected in Even Formate.'])->withInput();
                }
            }

            $ins = [
                "divisions_name" => $req->divisions_name,
                "league_id" => $req->league_id,
            ];
            if ($req->hasFile('picture')) {
                $img = $req->file('picture')->store('/', 'public');
                $ins["picture"] = URL("public/storage/".$img);
            }
            Division::where("id", $id)->update($ins);
            if (!empty($req->team_id)) {
                DivisionTeam::where("division_id", $id)->delete();
                foreach ($req->team_id as $teams) {
                    DivisionTeam::create([
                        "division_id" => $id,
                        "team_id" => $teams
                    ]);
                }
            }
            $webmsg = [
                "class" => "success",
                "message" => "Division updated successfully",
            ];
            return redirect()->back()->with($webmsg);
        }
    }
    public function delete(Request $req)
    {
        $id = $req->id;
        Division::where("id", $id)->delete();
        Match::where("division_id", $id)->delete();

        $webmsg = [
            "class" => "success",
            "message" => "Division deleted successfully",
        ];
        return redirect()->back()->with($webmsg);
    }
    public function edit(Request $req)
    {
        $id = $req->id;
        $data = Division::where("id", $id)->with([
            "getDivisionTeams" => function ($q) {
                $q->with("getTeam");
            },
        ])->first();
        //dd($data);
        $parse = [
            "menu" => "division",
            "sub_menu" => "",
            "title" => "Edit Division",
            "data" => $data,
        ];
        
        return view('division.edit_division', $parse);
    }
    public function getDivisionbyleague(Request $req)
    {
        $data = Division::where("league_id", $req->league_id)->get();
        $res = [
            "status" => "success",
            "message" => "success",
            "data" => $data,
        ];
        return response()->json($res, 200);
    }
}
