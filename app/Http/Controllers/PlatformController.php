<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Platform;
use App\VPCSystems;

class PlatformController extends Controller
{
    //
    function all_plateforms(Request $req){
        
        if(!empty($req->searhitems)){
            $string = $req->searhitems;
            $data = Platform::latest()
            ->where("id", $string)
            ->orwhere("plateform_name", "like", "%".$string."%")
            ->paginate(15);
        }else{
            $data = Platform::latest()->paginate(15);
        }
        $parse = [
            "menu" => "plateforms",
            "sub_menu" => "",
            "title" => "All Platforms",
            'data' => $data,
        ];
        
        return view('plateforms.all_plateform', $parse);
    }
    function add_plateforms(){
        
        $data = [];
        $parse = [
            "menu" => "plateforms",
            "sub_menu" => "",
            "title" => "Add Platforms",
            'data' => $data,
        ];
        
        return view('plateforms.add_plateform', $parse);
    }
    function saveplateforms(Request $req){
        $id = $req->id;
        if($id == 0){
            $validator = Validator::make($req->all(), [
                'plateform_name' => 'required',
                'plateform_logo' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }else{
                if($req->hasFile('plateform_logo')){
                    $img = $req->file('plateform_logo')->store('/', 'public');
                    $img = URL("public/storage/".$img);
                }else{
                    $img = "";
                }
                $ins = [
                    "plateform_name" => $req->plateform_name,
                    "plateform_logo" => $img
                ];
                Platform::create($ins);

                $webmsg = [
                    "class" => "success",
                    "message" => "Plateform created succssfully",
                ];
                return redirect()->back()->with($webmsg);
            }
        }else{
            $validator = Validator::make($req->all(), [
                'plateform_name' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }else{
                 $ins = [
                    "plateform_name" => $req->plateform_name,
                ];
                if($req->hasFile('plateform_logo')){
                    $img = $req->file('plateform_logo')->store('/', 'public');
                    $img = URL("public/storage/".$img);
                    $ins["plateform_logo"] = $img;
                }
                Platform::where("id", $id)->update($ins);

                $webmsg = [
                    "class" => "success",
                    "message" => "Plateform updated succssfully",
                ];
                return redirect()->back()->with($webmsg);
            }
        }
    }
    function delete(Request $req){
        $id = $req->input("id");
        Platform::where("id", $id)->delete();
        VPCSystems::where("plateform", $id)->delete();
        $webmsg = [
            "class" => "success",
            "message" => "Plateform and VPC System deleted succssfully",
        ];
        return redirect()->back()->with($webmsg);
    }
    function edit(Request $req){
        $id = $req->input("id");
        
        $data = Platform::where("id", $id)->first();
        $parse = [
            "menu" => "plateforms",
            "sub_menu" => "",
            "title" => "Edit Platforms",
            'data' => $data,
        ];
        
        return view('plateforms.edit_plateform', $parse);
    }
}
