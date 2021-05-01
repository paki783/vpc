<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\VPCSystems;
use App\Helpers\Helper;
use App\Platform;
use App\Games;
use App\VpcPlatformAssign;
use App\Countries;
use App\Contract;
use App\PlayerPosition;
use App\User;
use App\VpcSystemUserAssign;

class VPCSytemController extends Controller
{
    //
    public function all(Request $req)
    {
        
        if ($req->is_api == 1) {
            $api = [
                'status' => 'success',
                'message' => 'success',
                'data' => VPCSystems::where("syste_name", "like", "%".$req->q."%")->get(),
            ];
            return response()->json($api, 200);
        }
        if (!empty($req->searhitems)) {
            $string = $req->searhitems;
            $data = VPCSystems::with([
                "getVpcPlatformAssign" => function ($query) {
                    $query->with('getPlateform');
                },
                "GetGame",
            ])->where("id", $string)->orwhere("syste_name", "like", "%".$string."%");
        } else {
            $data = VPCSystems::with([
                "getVpcPlatformAssign" => function ($query) {
                    $query->with('getPlateform');
                },
                "GetGame",
            ]);
        }
        $data = $data->latest()->paginate(15);
        //dd($data[0]->getVpcPlatformAssign->getPlateform);
        $parse = [
            "menu" => "vpc_system",
            "sub_menu" => "",
            "title" => "All VPC System",
            'data' => $data,
        ];
        
        return view('vpc_system.all_vpc', $parse);
    }
    public function add_vpc_system(Request $req)
    {
        
        $data = [];
        $parse = [
            "menu" => "vpc_system",
            "sub_menu" => "",
            "title" => "Add VPC System",
            'data' => $data,
            "users" => User::get(),
            "regions" => Helper::regions(),
            "plateforms" => Platform::all(),
            "game" => Games::all(),
            "countries" => Countries::all(),
        ];
        
        return view('vpc_system.add_vpc', $parse);
    }
    public function saveVpcSystem(Request $req)
    {
        
        $id = $req->id;
        if ($id == 0) {
            $validator = Validator::make($req->all(), [
                'syste_name' => 'required',
                'region' => 'required',
                'plateform' => 'required',
                'game' => 'required',
                'country_id' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            } else {
                $ins = [
                    "syste_name" => $req->syste_name,
                    "country_id" => $req->country_id,
                    "region" => $req->region,
                    "game" => $req->game,
                ];
                $id = VPCSystems::create($ins)->id;
                if (!empty($req->plateform)) {
                    foreach ($req->plateform as $p) {
                        /*VpcPlatformAssign::updateOrCreate(
                            ['departure' => 'Oakland', 'destination' => 'San Diego'],
                            ['price' => 99]
                        );*/
                        VpcPlatformAssign::create([
                            'vpc_id' => $id,
                            "platform_id" => $p,
                        ]);
                    }
                }

                if (!empty($req->assign_user)) {
                    foreach ($req->assign_user as $v) {
                        VpcSystemUserAssign::create([
                            'vpc_id' => $id,
                            "user_id" => $v,
                        ]);
                    }
                }
                
                $webmsg = [
                    "class" => "success",
                    "message" => "VPC Sytem created succssfully",
                ];
                return redirect()->back()->with($webmsg);
            }
        } else {
            $validator = Validator::make($req->all(), [
                'syste_name' => 'required',
                'region' => 'required',
                'country_id' => 'required',
                'plateform' => 'required',
                'game' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            } else {
                $ins = [
                    "syste_name" => $req->syste_name,
                    "country_id" => $req->country_id,
                    "region" => $req->region,
                    "game" => $req->game,
                ];
                
                VPCSystems::where("id", $req->id)->update($ins);
                if (!empty($req->plateform)) {
                    VpcPlatformAssign::where('vpc_id', $req->id)->delete();
                    foreach ($req->plateform as $p) {
                        VpcPlatformAssign::create([
                            'vpc_id' => $req->id,
                            "platform_id" => $p,
                        ]);
                    }
                }

                if (!empty($req->assign_user)) {
                    VpcSystemUserAssign::where('vpc_id', $req->id)->delete();
                    foreach ($req->assign_user as $v) {
                        VpcSystemUserAssign::create([
                            'vpc_id' => $id,
                            "user_id" => $v,
                        ]);
                    }
                }

                $webmsg = [
                    "class" => "success",
                    "message" => "VPC Sytem updated succssfully",
                ];
                return redirect()->back()->with($webmsg);
            }
        }
    }
    public function delete(Request $req)
    {
        VPCSystems::where("id", $req->id)->delete();
        Contract::where("vpc_system_id", $req->id)->delete();
        PlayerPosition::where("vpc_system_id", $req->id)->delete();
        VpcSystemUserAssign::where("vpc_id", $req->id)->delete();

        $webmsg = [
            "class" => "success",
            "message" => "VPC Sytem deleted succssfully",
        ];
        return redirect()->back()->with($webmsg);
    }
    public function edit(Request $req)
    {
        
        $data = VPCSystems::where("id", $req->id)->with([
            "getVpcPlatformAssign" => function ($query) {
                $query->with('getPlateform');
            },
            "GetGame",
            "GetVpcAssignUser",
        ])->first();
        
        $selected_plateformsid = [];
        if (!empty($data->getVpcPlatformAssign)) {
            foreach ($data->getVpcPlatformAssign as $getP) {
                $selected_plateformsid[] = $getP->platform_id;
            }
        }

        $selected_assign_usersid = [];
        if (!empty($data->GetVpcAssignUser)) {
            foreach ($data->GetVpcAssignUser as $getUser) {
                $selected_assign_usersid[] = $getUser->user_id;
            }
        }

        $parse = [
            "menu" => "vpc_system",
            "sub_menu" => "",
            "title" => "Edit VPC System",
            'data' => $data,
            "users" => User::get(),
            "regions" => Helper::regions(),
            "plateforms" => Platform::all(),
            "game" => Games::all(),
            "selected_plateformsid" => $selected_plateformsid,
            "selected_assign_usersid" => $selected_assign_usersid,
            "countries" => Countries::all(),
        ];
        
        return view('vpc_system.edit_vpc', $parse);
    }
}
