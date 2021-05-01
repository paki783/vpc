<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use Validator;
use App\Leagues;

class LeaguesController extends Controller
{
    //
    public function all()
    {
        $data = Leagues::paginate(15);
        $parse = [
            "menu" => "leagues",
            "sub_menu" => "all_league",
            "title" => "VPC System",
            'data' => $data,
        ];
        
        return view('leagues.all', $parse);
    }
    public function add()
    {
        $data = [];
        $parse = [
            "menu" => "leagues",
            "sub_menu" => "all_league",
            "title" => "VPC System",
            'data' => $data,
            "regions" => Helper::regions(),
        ];
        
        return view('leagues.add', $parse);
    }
    public function saveleague(Request $req)
    {
        $id = $req->id;
        $validator = Validator::make($req->all(), [
            'league_name' => 'required|unique:leagues,league_name',
            'league_desc' => 'required',
            'league_region' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } else {
            if ($id == 0) {
                if ($req->hasFile('league_logo')) {
                    $img = $req->file('league_logo')->store('/', 'public');
                    $img = URL("public/storage/".$img);
                } else {
                    $img = "";
                }
                $ins = [
                    'league_name' => $req->input('league_name'),
                    'league_description' => $req->input('league_desc'),
                    'league_logo' => $img,
                    "league_region" => $req->input("league_region")
                ];
                Leagues::create($ins);
                $webmsg = [
                    "class" => "success",
                    "message" => "league created succssfully",
                ];
                return redirect()->back()->with($webmsg);
            } else {
                $ins = [
                    'league_name' => $req->input('league_name'),
                    'league_description' => $req->input('league_desc'),
                    "league_region" => $req->input("league_region")
                ];
                if ($req->hasFile('league_logo')) {
                    $img = $req->file('league_logo')->store('/', 'public');
                    $img = URL("public/storage/".$img);
                    $ins['league_logo'] = $img;
                }
                Leagues::where("id", $id)->update($ins);
                $webmsg = [
                    "class" => "success",
                    "message" => "league updated succssfully",
                ];
                return redirect()->back()->with($webmsg);
            }
        }
    }
    public function league_edit(Request $req)
    {
        $data = Leagues::where("id", $req->id)->first();
        $parse = [
            "menu" => "leagues",
            "sub_menu" => "all_league",
            "title" => "Edit Leagues",
            'data' => $data,
            "regions" => Helper::regions(),
        ];
        
        return view('leagues.edit', $parse);
    }
    public function delete(Request $req)
    {
        Leagues::where("id", $req->id)->delete();
        $webmsg = [
            "class" => "success",
            "message" => "league deleted succssfully",
        ];
        return redirect()->back()->with($webmsg);
    }
}
