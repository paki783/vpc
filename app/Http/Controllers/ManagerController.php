<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User\UserAssistant;
use Illuminate\Support\Facades\Crypt;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Validator;

class ManagerController extends Controller
{
    //
    function all(Request $req){
        $user_id = $req->id;

        $data = UserAssistant::where("manager_id", $user_id)->with([
            "getUser",
            "getLeague",
            "getDivision",
            "getTeam"
        ])->latest()->paginate(15);
        $parse = [
            "menu" => "user",
            "sub_menu" => "",
            "title" => "Manager User",
            'data' => $data,
            "manager_id" => $user_id,
        ];
        return view('manager.all', $parse);
    }
    function delete(Request $req){
        $id = Crypt::decryptString($req->id);

        UserAssistant::where("id", $id)->delete();

        $webmsg = [
            "class" => "success",
            "message" => "User Deleted from manager assign list",
        ];
        return redirect()->back()->with($webmsg);
    }
    function add(Request $req){
        $manager_id = $req->manager_id;

        /*$managers = UserAssistant::where([
            'manager_id' => $manager_id,
            "status" => "active",
        ])->get();
        
        $user_ids = [];
        
        if(!empty($managers)){
            foreach($managers as $m){
                array_push($user_ids, $m->user_id);
            }
        }
        $data = User::where([
            "status" => "enabled"
        ])->role("users")->get();*/

        $manager = User::where("id", $manager_id)->first();

        $parse = [
            "menu" => "user",
            "sub_menu" => "",
            "title" => "Manager User",
            "manager_id" => $manager_id,
            "data" => [],
            "manager" => $manager,
        ];
        return view('manager.add', $parse);
    }

    function assignUser(Request $req){
        $validator = Validator::make($req->all(), [
            'manager_id' => 'required|exists:users,id',
            'user_id' => 'required|exists:users,id',
            'league_id' => 'required|exists:tournaments,id',
            'division_id' => 'required|exists:divisions,id',
            'team_id' => 'required|exists:teams,id',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        UserAssistant::create([
            "manager_id" => $req->manager_id,
            "user_id" => $req->user_id,
            "league_id" => $req->league_id,
            "division_id" => $req->division_id,
            "team_id" => $req->team_id,
            "status" => "active"
        ]);

        $user = User::find($req->user_id);
        $user->syncRoles('assistant');

        $webmsg = [
            "class" => "success",
            "message" => "User assign to the manager",
        ];
        return redirect()->back()->with($webmsg);
    }
    function edit(Request $req){
        $id = $req->id;
        $validator = Validator::make($req->all(), [
            'id' => 'required|exists:user_assistants,id',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        $data = UserAssistant::where("id", $id)->with([
            "getUser",
            "getManager",
            "getLeague",
            "getDivision",
            "getTeam"
        ])->first();
        $parse = [
            "menu" => "user",
            "sub_menu" => "",
            "title" => "Edit Manager User",
            "data" => $data,
        ];
        return view('manager.edit', $parse);
    }
    function UpdateAssignUser(Request $req){
        $id = $req->id;

        $validator = Validator::make($req->all(), [
            'id' => 'required|exists:user_assistants,id',
            'manager_id' => 'required|exists:users,id',
            'user_id' => 'required|exists:users,id',
            'league_id' => 'required|exists:tournaments,id',
            'division_id' => 'required|exists:divisions,id',
            'team_id' => 'required|exists:teams,id',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        UserAssistant::where("id", $id)->update([
            "manager_id" => $req->manager_id,
            "user_id" => $req->user_id,
            "league_id" => $req->league_id,
            "division_id" => $req->division_id,
            "team_id" => $req->team_id,
            "status" => "active"
        ]);

        $user = User::find($req->user_id);
        $user->syncRoles('assistant');

        $webmsg = [
            "class" => "success",
            "message" => "User assign fields updated",
        ];
        return redirect()->back()->with($webmsg);
    }
}
