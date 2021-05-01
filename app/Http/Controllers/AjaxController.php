<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mode;

class AjaxController extends Controller
{
    //
    function searchMode(Request $req){
        $api = Mode::where("mode_name", "like", "%".$req->q."%")->get();
        $res = [
            "status" => "success",
            "message" => "success",
            "data" => $api,
        ];
        return response()->json($res, 200);
    }
}
