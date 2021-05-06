<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Countries;
use Spatie\Permission\Models\Role;
use Validator;
use Illuminate\Support\Facades\URL;

class AdminController extends Controller
{
    //
    function alladmin(){

        $role = Role::where("type", "admin")->get();
        $role_name = [];
        foreach ($role as $k => $v) {
            array_push($role_name, $v->name);
        }
        $data = User::role($role_name)->paginate(15);

        $parse = [
            "menu" => "super_users",
            "sub_menu" => "",
            "title" => "All Super Users",
            'data' => $data,
        ];
        
        return view('admin.all', $parse);
    }
    function add(){
        $parse = [
            "menu" => "super_users",
            "sub_menu" => "",
            "title" => "Add Super Users",
            'data' => [],
        ];
        
        return view('admin.add', $parse);
    }

    function saveUser(Request $req){
        $input = $req->all();

        $validator = Validator::make($req->all(), [
            'email' => 'required|email|unique:users,email',
            "first_name" => 'required',
            "last_name" => 'required',
            "password" => 'required',
        ]);
        if ($validator->fails()) {
            return json_encode(array("error" => $validator->errors()->first()));
        }

        $userData['user_name'] = time();
        $userData['first_name'] = $input['first_name'];
        $userData['last_name'] = $input['last_name'];
        $userData['email'] = $input['email'];
        $userData['password'] = bcrypt($input['password']);
        $userData['status'] = $input['status'];

        $regUsers = User::create($userData);
        $regUsers->assignRole("admin");

        return json_encode(array("success" => "Record Updated Successfully", "redirect" => URL::to('admin/superusers/all'), 'fieldsEmpty' => 'yes'));
    }
    function edit(Request $req){
        $id = $req->id;
        $data = User::where("id", $id)->first();
        $parse = [
            "menu" => "super_users",
            "sub_menu" => "",
            "title" => "Edit Super Users",
            'data' => $data,
        ];
        
        return view('admin.edit', $parse);
    }
    function updateUser(Request $req){
        $input = $req->all();

        $validator = Validator::make($req->all(), [
            'email' => 'required|email|unique:users,email',
            "first_name" => 'required',
            "last_name" => 'required',
        ]);
        if ($validator->fails()) {
            return json_encode(array("error" => $validator->errors()->first()));
        }

        $userData['first_name'] = $input['first_name'];
        $userData['last_name'] = $input['last_name'];
        $userData['email'] = $input['email'];
        
        if (isset($input['password']) && @$input['password'] != '') {
            $userData['password'] = bcrypt($input['password']);
        }
        
        $userData['status'] = $input['status'];
        $user = User::find($userData['id']);
        $user->update($userData);
        $user->syncRoles('admin');

        return json_encode(array("success" => "Record Updated Successfully", "redirect" => URL::to('admin/user/all_user'), 'fieldsEmpty' => 'yes'));
        
    }
}
