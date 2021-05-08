<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User\UserAssistant;

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
        ];
        return view('manager.all', $parse);
    }
}
