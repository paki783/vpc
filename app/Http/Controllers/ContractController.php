<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Contract;
use App\Helpers\Helper;
use App\VPCSystems;
use App\User;

class ContractController extends Controller
{
    //
    function all_contract(Request $req){
        if(!empty($req->searhitems)){
            $data = Contract::with([
                'getUser',
                'getManager',
                'getTeam',
                "getVPCSystem",
            ])->where("id", $req->searhitems);
            $data = $data->paginate(15);
        }else{
            $data = Contract::with([
                'getUser',
                'getManager',
                'getTeam',
                'getLeague'
            ]);
            $data = $data->paginate(15);
        }
        $parse = [
            "menu" => "contract",
            "sub_menu" => "",
            "title" => "All Contract",
            'data' => $data,
        ];
        
        return view('contract.all_contract', $parse);
    }
    function saveContract(Request $req){
        $validator = Validator::make($req->all(), [
            'user_id' => 'required',
            'league_id' => 'required|exists:tournaments,id',
            'division_id' => 'required|exists:divisions,id',
            'wage' => 'required',
            'total_matches' => 'required',
            'matches_played' => 'required',
            'release_clause' => 'required',
            'team_id' => 'required|exists:teams,id',
            'manager_id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            if($req->is_api == 1){
                return Helper::errorResponse(422, $validator->errors()->first(),$validator->errors());
            }
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }else{
            $check = Contract::where([
                "user_id" => $req->user_id,
            ])->first();
            if(empty($check)){
                Contract::create([
                    'user_id' => $req->user_id,
                    'league_id' => $req->league_id,
                    'division_id' => $req->division_id,
                    'wage' => $req->wage,
                    'total_matches' => $req->total_matches,
                    'release_clause' => $req->release_clause,
                    'matches_played' => $req->matches_played,
                    'team_id' => $req->team_id,
                    'manager_id' => $req->manager_id,
                ]);
                if($req->is_api == 1){
                    $parse = [
                        "status" => "success",
                        "message" => "contract offered to the user",
                        "data" => array(),
                    ];
                    return Helper::successResponse($parse, 'Successfully get profile');
                }else{
                    $webmsg = [
                        "class" => "success",
                        "message" => "contract added successfully",
                    ];
                    return redirect()->back()->with($webmsg);
                }
            }else{
                if($req->is_api == 1){
                    $parse = [
                        "status" => "error",
                        "message" => "user already have the contract.",
                        "data" => array(),
                    ];
                    return Helper::successResponse($parse, 'user already have the contract.');
                }else{
                    $webmsg = [
                        "class" => "danger",
                        "message" => "user already have the contract.",
                    ];
                    return redirect()->back()->with($webmsg);
                }
            }
        }
    }

    function Offeraction(Request $req){

        $validator = Validator::make($req->all(), [
            'contract_id' => 'required|exists:contracts,id',
        ]);
        if ($validator->fails()) {
            return Helper::errorResponse(422, $validator->errors()->first(),$validator->errors());
            
        }

        try {
            $contract_id = $req->contract_id;
            $action = $req->action;
            if ($action == "rejected") {
                Contract::where("id", $contract_id)->delete();
            }else{
                Contract::where("id", $contract_id)->update([
                    "status" => $action
                ]);
            }

            $parse = [
                "status" => "success",
                "message" => "success",
                "data" => array(),
            ];
            return Helper::successResponse($parse, 'success');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }    
    }

    function viewContract(Request $req){
        $contract_id = $req->contract_id;
        $data = Contract::where("id", $contract_id)->first();
        if($req->is_api == 1){
            $parse = [
                "status" => "success",
                "message" => "success",
                "data" => $data,
            ];
            return Helper::successResponse($parse, 'success');
        }
    }
    // user
    function getMyContract(Request $req){
        $user_id = $req->user_id;
        $contract = Contract::where('user_id', $user_id);
        $status = @$req->status;
        if (isset($status) && @$status != null) {
            $contract = $contract->where('status',$status);
        }
        $contract = $contract->with([
            'getUser',
            'getManager',
            'getTeam',
        ])
        ->paginate(15);

        if($req->is_api == 1){
            $res = [
                "status" => "success",
                "message" => "success",
                "data" => $contract,
            ];
            return Helper::successResponse($res, 'success');
        }
    }

    // Manager
    function getManagerContract(Request $req){
        $manager_id = $req->manager_id;
        $contract = Contract::where('manager_id', $manager_id);
        $status = @$req->status;
        if (isset($status) && @$status != null) {
            $contract = $contract->where('status',$status);
        }
        $contract = $contract->with([
            'getUser',
            'getManager',
            'getTeam',
        ])
        ->paginate(15);

        if($req->is_api == 1){
            $res = [
                "status" => "success",
                "message" => "success",
                "data" => $contract,
            ];
            return Helper::successResponse($res, 'success');
        }
    }

    function delete(Request $req){
        $id = $req->id;
        Contract::where('id', $id)->delete();
        $webmsg = [
            "class" => "success",
            "message" => "Contract deleted successfully",
        ];                
        return redirect()->back()->with($webmsg);
    }
    function add_contract(Request $req){
        /*$data = Contract::with([
            'getUser',
            'getManager',
            'getTeam',
            "getVPCSystem",
        ]);
        $data = $data->paginate(15);*/
        
        $data = [];
        $parse = [
            "menu" => "contract",
            "sub_menu" => "",
            "title" => "Add Contract",
            'data' => $data,
        ];
        
        return view('contract.add_contract', $parse);
    }
    function uncontractuser(Request $req){
        /*$getcontract = Contract::where('status', 'accepted')->get();
        $notIN = [];
        if(!empty($getcontract)){
            foreach($getcontract as $contract){
                array_push($notIN, $contract->user_id);
            }
        }*/
        $res = [
            "status" => "success",
            "data" => User::role('users')->get(),
            "message" => "success",
        ];
        return response()->json($res, 200);
    }
    function getContractByTeams(Request $req){
        $team_id = $req->team_id;
        $user_id = $req->user_id;
        $is_api = $req->is_api;

        if(!empty($req->print_all) and $req->is_api == 1){
            $api = Contract::where('team_id', $team_id)->with([
                'getUser' => function($q) { 
                    $q->with('getSelectdTeam');
                },
                'assistant' => function($q) { 
                    $q->where('status', "active");
                }
            ])
            ->whereNotIn('status', ['rejected', 'release', 'terminate'])
            ->latest()->get();
        }else{
            $api = Contract::where('team_id', $team_id)->with([
                'getUser' => function($q) { 
                    $q->with('getSelectdTeam');
                },
                'assistant' => function($q) { 
                    $q->where('status', "active");
                }
            ])
            ->whereNotIn('status', ['rejected', 'release', 'terminate'])
            ->latest()->paginate(15);
        }

        if($is_api == 1){
            $res = [
                "status" => "success",
                "message" => "success",
                "data" => $api,
            ];
            return response()->json($res, 200);
        }
    }
    function edit(Request $req){
        $data = Contract::where('id', $req->id)->with([
            "getUser",
            "getManager",
            "getTeam",
            "getVPCSystem",  
        ])->first();
        //dd($data);
        $parse = [
            "menu" => "contract",
            "sub_menu" => "",
            "title" => "Edit Contract",
            'data' => $data,
        ];
        
        return view('contract.edit_contract', $parse);
    }
}
