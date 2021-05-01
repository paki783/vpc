<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Achievement\Achievement;
use App\Achievement\AssignAward;
use App\Achievement\AssignMedal;
use App\Attachment;
use App\Division;
use App\User;
use Validator;


class MedalController extends Controller
{
    //
    function all_medals(Request $req){
        
        $data = Achievement::where('status', "medal")->with([
            "getPictureMedal",  
        ]);
        if($req->is_api == 1 && !empty($req->search_now)){
            $data->where("achievement_name", "like", "%".$req->name."%");
        }
        if(!empty($req->searhitems)){
            $string = $req->searhitems;
            $data->where("id", $string)
            ->orwhere("achievement_name", "like", "%".$string."%");
        }
        $data = $data->paginate(15);
        $parse = [
            "menu" => "medals",
            "sub_menu" => "cmedals",
            "title" => "All Medals",
            'data' => $data,
        ];
        //dd($data);
        if($req->is_api == 1){
            return response()->json($parse, 200);
        }
        return view('medals/all_medals', $parse);
    }
    function add_medals(){
        
        $parse = [
            "menu" => "medals",
            "sub_menu" => "cmedals",
            "title" => "Add Medal",
            'data' => [],
        ];
        return view('medals/add_medal', $parse);
    }
    function saveMedal(Request $req){
        $id = $req->id;
        if($id == 0){
            $validator = Validator::make($req->all(), [
                'award_name' => 'required',
                'award_logo' => 'image',
            ]);
            if ($validator->fails()) {
                return Helper::errorResponse(422, $validator->errors()->first(),$validator->errors());
            }else{
                $acid = Achievement::create([
                    "achievement_name" => $req->award_name,
                    "status" => "medal"
                ])->id;

                if($req->hasFile('award_logo')){
                    $img = $req->file('award_logo')->store('/', 'public');
                    $img = URL("public/storage/".$img);

                    Attachment::create([
                        "type_id" => $acid,
                        "type" => "medal",
                        "photo" => $img,
                        "video_url" => "",
                        "model_name" => "medal",
                    ]);
                }
                $webmsg = [
                    "class" => "success",
                    "message" => "Medal added successfully",
                ];
                return redirect()->back()->with($webmsg);
            }
        }else{
            $validator = Validator::make($req->all(), [
                'award_name' => 'required',
            ]);
            if ($validator->fails()) {
                return Helper::errorResponse(422, $validator->errors()->first(),$validator->errors());
            }else{
                Achievement::where("id", $id)->update([
                    "achievement_name" => $req->award_name,
                ]);
                if($req->hasFile('award_logo')){
                    $img = $req->file('award_logo')->store('/', 'public');
                    $img = URL("public/storage/".$img);
                    Attachment::where([
                        "type_id" => $id,
                        "type" => "medal",
                        "model_name" => "medal",
                    ])->delete();
                    Attachment::create([
                        "type_id" => $acid,
                        "type" => "medal",
                        "photo" => $img,
                        "video_url" => "",
                        "model_name" => "medal",
                    ]);
                }
                $webmsg = [
                        "class" => "success",
                    "message" => "Medal updated successfully",
                ];
                return redirect()->back()->with($webmsg);
            }
        }
    }
    function delete(Request $req){
        $id = $req->id;
        Attachment::where([
            "type_id" => $id,
            "type" => "medal",
            "model_name" => "medal",
        ])->delete();
        Achievement::where([
            'status' => "medal",
            "id" => $id,
        ])->delete();
        $webmsg = [
            "class" => "success",
            "message" => "Medal deleted successfully",
        ];
        return redirect()->back()->with($webmsg);
    }
    function edit(Request $req){
        
        $id = $req->id;
        $data = Achievement::where('id', $id)->with([
            "getPictureMedal",  
        ])->first();

        $parse = [
            "menu" => "medals",
            "sub_menu" => "cmedals",
            "title" => "Edit Medal",
            'data' => $data,
        ];
        return view('medals/edit_medal', $parse);
    }
    function all_assign_medal(Request $req){
        
        $data = AssignMedal::with([
            "getTeam",
            "getMedal",
            "getLeague",
            "getDivision",
            "getallUser"
        ])->paginate(15);

        $parse = [
            "menu" => "medals",
            "sub_menu" => "assign_medals",
            "title" => "All Assign Medals",
            'data' => $data,
        ];
        return view('medals/assign_all', $parse);
    }
    function add_assign_medals(Request $req){
        
        $parse = [
            "menu" => "medals",
            "sub_menu" => "assign_medals",
            "title" => "Add Assign Medal",
            "user" => User::all(),
            'data' => [],
        ];
        return view('medals/add_assign_medals', $parse);
    }
    function saveAssignMedal(Request $req){
        // dd($req->all());
        $id = $req->id;
        $validator = Validator::make($req->all(), [
            'league_id' => 'required',
            'division_id' => 'required',
            'team_id' => 'required',
            'medal_id' => 'required',
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return Helper::errorResponse(422, $validator->errors()->first(),$validator->errors());
        }else{
            // AssignMedal::where("team_id", $req->team_id)->delete();
            // foreach($req->user_id as $id){
            // }
            if($req->id == 0){
                AssignMedal::create([
                    'league_id' => $req->league_id,
                    'division_id' => $req->division_id,
                    'team_id' => $req->team_id,
                    'medal_id' => $req->medal_id,
                    'user_id' => $req->user_id,
                ]);

                $webmsg = [
                    "class" => "success",
                    "message" => "Medal assign to the team successfully",
                ];
            }else{
                $assignMedal = AssignMedal::find($req->id);
                $assignMedal->update([
                    'league_id' => $req->league_id,
                    'division_id' => $req->division_id,
                    'team_id' => $req->team_id,
                    'medal_id' => $req->medal_id,
                    'user_id' => $req->user_id,
                ]);

                $webmsg = [
                    "class" => "success",
                    "message" => "Medal assign updated successfully to the team",
                ];
            }
            return redirect()->back()->with($webmsg);
        }
    }
    function edit_assign_medals(Request $req){
        $id = $req->id;
        $data = AssignMedal::with([
            "getTeam",
            "getMedal",
            "getLeague",
        ])->find($id);

        $data->divisions = Division::where('league_id', $data->league_id)->get();

        $data->users = AssignMedal::where([
            "team_id" => $data->team_id,
        ])->with([
            "getallUser"
        ])->get();
        // dd($data);
        $parse = [
            "menu" => "medals",
            "sub_menu" => "assign_medals",
            "title" => "Add Assign Medal",
            'data' => $data,
        ];
        return view('medals/edit_assign_medals', $parse);
    }

    function delete_assign_medals(Request $req){
        $id = $req->id;
        AssignMedal::find($id)->delete();

        $webmsg = [
            "class" => "success",
            "message" => "Assign Medal deleted successfully",
        ];
        return redirect()->back()->with($webmsg);
    }
    
    function getmedalbyuser(Request $req){
        $data = AssignMedal::with([
            "getTeam",
            "getMedal",
        ])->groupBy(['team_id', "medal_id"])->where("user_id", $req->user_id)->first();
        if(!empty($data)){
            $data->users = AssignMedal::where([
                "team_id" => $data->team_id,
            ])->with([
                "getallUser",
                "getMedal",
                "getMedia"
            ])->get();
            $res = [
                "status" => "success",
                "message" => "success",
                "data" => $data,
            ];
        }else{
            $res = [
                "status" => "error",
                "message" => "no medal awarded to this user",
                "data" => [],
            ];
        }

        return response()->json($res, 200);
    }
    function getmedalbyteam(Request $req){
        $data = AssignMedal::with([
            "getTeam",
            "getMedal",
        ])->groupBy(['team_id', "medal_id"])->where("user_id", $req->team_id)->first();

        $data->users = AssignMedal::where([
            "team_id" => $data->team_id,
        ])->with([
            "getallUser"
        ])->get();

        $res = [
            "status" => "success",
            "message" => "success",
            "data" => $data,
        ];
        return response()->json($res, 200);
    }
}
