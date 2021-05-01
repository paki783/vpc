<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $role = Role::where("type", "admin")->get();
        $role_name = [];
        foreach ($role as $k => $v) {
            $role_name[] = $v->name;
        }
        
        if (Auth::check() && Auth::user()->hasRole($role_name)) {
            return $next($request);
        } else {
            if (Auth::check() && Auth::user()->hasRole($role_name)) {
                $no_login_redirect = "admin/dashboard";
                // Session::flush();
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower(@$_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    return json_encode(array("error" => "Your Login Session Has Expired.", "redirect" => URL::to($no_login_redirect)));
                } else {
                    return redirect($no_login_redirect);
                }
            }else{
                $no_login_redirect = "admin/";
                return redirect($no_login_redirect);
            }
        }
    }
}
