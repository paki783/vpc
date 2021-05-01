<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use App\User;
use Validator;

class DashboardController extends Controller
{
    function home(){

        // Auth::user()->givePermissionTo('dashboard');

        $parse = [
            "menu" => "dashboard",
            "sub_menu" => "",
        ];
        return view('dashboard', $parse);
    }
}