<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use JWTAuth;
use stdClass;
use Validator;
use App\Match;
use App\Slider;
use App\News;
use App\Tournament;
use App\Achievement\AssignAward;
use App\TeamManager;
use App\VPCSystems;
use App\Contract;
use App\Countries;
use App\Team;
use Spatie\Permission\Models\Role;
use App\User\UserAssistant;
use Illuminate\Support\Facades\Crypt;
use Session;
use Mail;

class UserController extends Controller
{
    //
    public function guard()
    {
        return Auth::guard('api');
    }
    public function login(Request $request)
    {
        // echo bcrypt('abc123'); die;
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return Helper::errorResponse(422, $validator->errors()->first(), $validator->errors());
        }
        $credentials = request(['email', 'password']);
        try {
            if ($token = $this->guard()->attempt($credentials)) {
                $role = Role::where("type", "user")->get();
                $role_name = [];
                foreach ($role as $k => $v) {
                    $role_name[] = $v->name;
                }
                if($this->guard()->user()->hasRole($role_name)) {
                    $user = $this->guard()->user();
                    $success['token'] = $token;
                    $success['users'] = $user;
                    
                    // Device
                    if($request->has('token') && $request->has('type') && $request->has('uuid')){
                        $user->devices()->updateOrCreate(
                            [
                                'type'=> $request->type,
                                'uuid'=> $request->uuid,
                            ],
                            [
                                'type'=> $request->type,
                                'uuid'=> $request->uuid,
                                'token'=> $request->token,
                            ]
                        );
                    }
                    return Helper::successResponse($success, 'logged in');
                } else {
                    return Helper::errorResponse(401, 'Role Doesn\'t exist or missing access rights to application.');
                }
            } else {
                return Helper::errorResponse(401, 'Wrong credentials or missing access rights to application.');
            }
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        try {
            $user = $this->guard()->user();
            $success['users'] = $user;
            return Helper::successResponse($success, 'Successfully get profile');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $data = new stdClass;
        auth()->logout();
        Session::flush();
        if ($request->has('is_api')) {
            return Helper::successResponse($data, 'Successfully logged out');
        }else{
            return redirect('admin');
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    public function register(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            "user_name" => 'required|unique:users,user_name',
            "first_name" => 'required',
            "last_name" => 'required',
        ]);
        if ($validator->fails()) {
            return Helper::errorResponse(422, $validator->errors()->first(), $validator->errors());
        } else {
            
            $userData = [
                'user_name' => $req->user_name,
                'first_name' => $req->first_name,
                'last_name' => $req->last_name,
                'email' => $req->email,
                'password' => bcrypt($req->password),
                "facebook_link" => @$req->facebook_link,
                "twitter_link" => @$req->twitter_link,
                "playstationtag" => @$req->playstationtag,
                "xboxtag" => @$req->xboxtag,
                "streamid" => @$req->streamid,
                "bio" => @$req->bio,
            ];
            
            if ($req->hasFile('profile_image')) {
                $img = $req->file('profile_image')->store('/', 'public');
                $profile_img = URL("public/storage/".$img);
                $userData['pending_profile_image'] = $profile_img;
                $userData['pending_profile_status'] = 1;
            }

            $regUsers = User::create($userData);
            $regUsers->assignRole("users");

            return Helper::successResponse(array(), 'User Registered successfully');
        }
    }
    public function admin()
    {
        if (Auth::check()){
            return redirect("admin/dashboard");
        }
        // echo bcrypt("admin123");
        return view('user.login');
    }
    public function admin_auth(Request $req)
    {
        if (Auth::check()){
            return redirect("admin/dashboard");
        }
        
        $validator = Validator::make($req->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        } else {
            $credentials = request(['email', 'password']);
            // dd(bcrypt($credentials['password']));
            if (Auth::attempt($credentials)) {
                $role = Role::where("type", "admin")->get();
                $role_name = [];
                foreach ($role as $k => $v) {
                    $role_name[] = $v->name;
                }
                
                if(Auth::user()->hasRole($role_name)) {

                    $user = Auth::user();
                    session(["userdata" => $user]);
                    return redirect("/admin/dashboard");

                } else {
                    $webmsg = [
                            "class" => "danger",
                            "message" => "This email account does not have permission to login in admin pannel",
                        ];
                    return redirect()->back()->with($webmsg);
                }
            } else {
                $webmsg = [
                        "class" => "danger",
                        "message" => "email / password is incorrect",
                    ];
                return redirect()->back()->with($webmsg);
            }
        }
    }
    public function userHome(Request $req)
    {
        $data = [
            "slider" => Slider::all(),
            "news" => News::limit(10)->latest()->get(),
            "leagues" => Tournament::where("tournament_type", "league")->with([
                "getVPCSystem",
                "getTournamentMode" => function ($query) {
                    $query->with('getMode');
                },
                "getSeasons"
            ])->latest()->limit(10)->get(),
            "tournament" => Tournament::where("tournament_type", "tournament")->with([
                "getVPCSystem",
                "getTournamentMode" => function ($query) {
                    $query->with('getMode');
                },
                "getSeasons"
            ])->latest()->limit(10)->get(),
        ];
        $res = [
            "status" => "success",
            "message" => "success",
            "data" => $data,
        ];
        return response()->json($res, 200);
    }
    public function all_user(Request $req)
    {
        
    
        if (!empty($req->user_name)) {
            $data = User::where("user_name", 'like', '%'. $req->user_name ."%")->orWhere("id", $req->user_name);
        } else {
            $data = User::role(["users", "manager"]);
        }

        $data = $data->with([
                'managerAssistant' => function($q){
                    $q->with("getUser");
                }
            ])
            ->paginate(15);
        $parse = [
            "menu" => "user",
            "sub_menu" => "",
            "title" => "All User",
            'data' => $data,
        ];
        
        return view('user.all', $parse);
    }

    public function userProfilepending(Request $req)
    {
        
    
        $data = User::where("pending_profile_status",1)->paginate(15);
        
        $parse = [
            "menu" => "user-profile-pending",
            "sub_menu" => "",
            "title" => "All User Pending Profile",
            'data' => $data,
        ];
        
        return view('user.profile_pending', $parse);
    }

    public function edit(Request $req)
    {
        $userid = $req->id;
        
        $data = User::where("id", $userid)->with([
            "getPosition",
            "getTeam",
            "getMode",
        ])->first();

        $parse = [
            "menu" => "user",
            "sub_menu" => "",
            "title" => "Edit User",
            'data' => $data,
            "countries" => Countries::all(),
        ];
        
        return view('user.edit', $parse);
    }

    public function updateUser(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            "user_name" => 'required|unique:users,user_name,' . $input['id'] . ',id',
            "first_name" => 'required',
            "last_name" => 'required',
            "email" => "required|email|unique:users,email," . $input['id'] . ",id",
            "confirm_password" => "required_with:password|same:password",
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } else {
            $userData['id'] = $input['id'];
            $userData['user_name'] = $input['user_name'];
            $userData['first_name'] = $input['first_name'];
            $userData['last_name'] = $input['last_name'];
            $userData['email'] = $input['email'];
            if (isset($input['password']) && @$input['password'] != '') {
                $userData['password'] = bcrypt($input['password']);
            }
            if ($request->hasFile('profile_image')) {
                $img = $request->file('profile_image')->store('/', 'public');
                $img = URL("public/storage/".$img);
                $userData["profile_image"] = $img;
            }

            $user = User::find($userData['id']);
            $user->update($userData);
            $webmsg = [
                "class" => "success",
                "message" => "User Updated Successfully",
            ];
            
            return redirect()->back()->with($webmsg);
        }
    }

    public function details(Request $req)
    {
        $userid = Crypt::decryptString($req->id);
        
        $data = User::where("id", $userid)->first();
        $parse = [
            "menu" => "user",
            "sub_menu" => "",
            "title" => "All User",
            'data' => $data,
        ];
        
        return view('user.details', $parse);
    }

    // public function promote(Request $req)
    // {
    //     $userid = Crypt::decryptString($req->id);
    //     $promote = $req->prmote;
    //     if ($promote == "true") {
    //         User::find($userid)->update(["promote" => "manager"]);
    //         $webmsg = [
    //             "class" => "success",
    //             "message" => "user promoted",
    //         ];
    //     }
    //     if ($promote == "false") {
    //         User::find($userid)->update(["promote" => "user"]);
    //         $webmsg = [
    //             "class" => "danger",
    //             "message" => "user demoted",
    //         ];
    //     }
    //     return redirect()->back()->with($webmsg);
    // }

    public function search_user(Request $req)
    {
        $user_type = empty($req->user_type) ? "users" : $req->user_type;
        $users = User::role($user_type)->with([
                'contract' => function($q){
                    $q->orderBy('id', 'DESC');
                }
            ]);
        if (!empty($req->user_name)) {
            $users = $users->where("user_name", $req->user_name);
        }
        $users = $users->paginate(15);
        if ($req->is_api == 1) {
            if (empty($users)) {
                $res = [
                    "status" => "error",
                    "message" => "no user found",
                    "data" => array(),
                ];
                return Helper::successResponse($res, 'error');
            } else {
                $res = [
                    "status" => "success",
                    "message" => "success",
                    "data" => $users,
                ];
                return Helper::successResponse($res, 'success');
            }
        }
    }
    public function SaveAssistance(Request $req)
    {
        $manager_id = $req->manager_id;
        $user_id = $req->user_id;

        $check = UserAssistant::where([
            "manager_id" => $manager_id,
            "user_id" => $user_id
        ])->first();
        if (empty($check)) {
            UserAssistant::create([
                "manager_id" => $manager_id,
                "user_id" => $user_id
            ]);

            $res = [
                "status" => 'success',
                "message" => "success",
                "data" => [],
            ];
        } else {
            $res = [
                "status" => "error",
                "message" => "manager already assigned to that user",
                "data" => []
            ];
        }
        return response()->json($res, 200);
    }
    function removeAssitant(Request $req){
        $validator = Validator::make($input, [
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return Helper::errorResponse(422, $validator->errors()->first(), $validator->errors());
        }
        $user_id = $req->user_id;

        UserAssistant::where([
            "user_id" => $user_id
        ])->delete();

        return Helper::successResponse([], 'assistant removed successfully');
    }
    public function getManagerDetails(Request $req)
    {
        $manager_id = $req->manager_id;

        $data = User::role("manager")->where([
            "id" => $manager_id,
        ])
        ->with([
            "teamManager" => function($q){
                $q->with('team');
            }
        ])
        ->first();
        if (empty($data)) {
            $res = [
                "status" => "error",
                "message" => "no manager found agains this id or admin demoted this user",
                "data" => [],
            ];
        } else {
            $teams = TeamManager::where("user_id", $data->id)->first();
            $matchIn = Match::where("team_one_id", $teams->team_id)
            ->orWhere("team_two_id", $teams->team_id)
            ->latest()->first();

            if (empty($matchIn)) {
                $data->league = null;
                $data->count_tropies = 0;
                $data->count_contract = 0;
            } else {
                $data->league = Tournament::where("id", $matchIn->league_id)->first();
                $data->count_tropies = AssignAward::where([
                    "league_id" => $data->league->id,
                    "team_id" => $teams->team_id,
                ])->count();


                $data->count_contract = Contract::where([
                    "manager_id" => $data->id,
                    "team_id" => $teams->team_id,
                ])->count();
            }

            if (empty($data->league)) {
                $data->league_system = null;
            } else {
                $data->league_system = VPCSystems::where("id", $data->league->vpc_systemid)->first();
            }

            $res = [
                "status" => "success",
                "message" => "success",
                "data" => $data,
            ];
        }
        return response()->json($res, 200);
    }
    public function getplayerdetails(Request $req)
    {
        $user_id = $req->user_id;
        $data = User::where([
            "id" => $user_id,
        ])
        ->first();

        if (empty($data)) {
            $res = [
                "status" => "error",
                "message" => "no user found",
                "data" => [],
            ];
            return response()->json($res, 200);
        } else {
            $team_contract = Contract::where("user_id", $data->id)
            ->orWhere("manager_id", $data->id)->first();

            $data->user_contract = $team_contract;
            if (!empty($team_contract)) {
                $matchIn = Match::where("team_one_id", $team_contract->team_id)
                ->orWhere("team_two_id", $team_contract->team_id)
                ->latest()->first();

                if (!empty($matchIn)) {
                    $data->league = Tournament::where("id", $matchIn->id)->first();
                    $data->league_system = VPCSystems::where("id", $data->league->vpc_systemid)->first();
                    $data->count_tropies = AssignAward::where([
                        "league_id" => $data->league->id,
                        "team_id" => $team_contract->team_id,
                    ])->count();
                    $data->count_contract = Contract::where([
                        "user_id" => $data->id,
                        "team_id" => $team_contract->team_id,
                    ])->count();
                } else {
                    $data->league = [];
                    $data->league_system = [];
                    $data->count_tropies = 0;
                    $data->count_contract = 0;
                }
            } else {
                $data->league = [];

                $data->league_system = [];

                $data->count_tropies = 0;
                
                $data->count_contract = 0;
            }

            $res = [
                "status" => "success",
                "message" => "success",
                "data" => $data,
            ];

            return response()->json($res, 200);
        }
    }
    public function api_updateprofile(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'country_id' => 'required',
            'position_id' => 'required',
            'playstationtag' => 'required',
            'xboxtag' => 'required',
            'streamid' => 'required',
            'selected_team' => 'required',
            "user_id" => "required",
        ]);
        if ($validator->fails()) {
            return Helper::errorResponse(422, $validator->errors()->first(), $validator->errors());
        } else {
            $ins = [
                "country_id" => $req->country_id,
                "position_id" => $req->position_id,
                "playstationtag" => $req->playstationtag,
                "xboxtag" => $req->xboxtag,
                "streamid" => $req->streamid,
                "selected_team" => $req->selected_team,
            ];
            if ($req->hasFile('profile_image')) {
                $img = $req->file('profile_image')->store('/', 'public');
                $profile_img = URL("public/storage/".$img);
                $userData['pending_profile_image'] = $profile_img;
                $userData['pending_profile_status'] = 1;
            }
            User::where("id", $req->user_id)->update($ins);
            $res = [
                "status" => "success",
                "message" => "profile updated successfully",
                "data" => [],
            ];

            return response()->json($res, 200);
        }
    }
    function forgetPassword(Request $req){
        $validator = Validator::make($req->all(), [
            'user_email' => 'required',
        ]);
        if ($validator->fails()) {
            return Helper::errorResponse(422, $validator->errors()->first(), $validator->errors());
        }
        try {
            $user = User::where('email', $req->user_email)->first();

            if(empty($user)){
                return Helper::errorResponse(422, "error", "no email found");
            }
            Mail::send('mail.forgetpassword', [
                "name" => $user->user_name,
                "user_id" => Crypt::encryptString($user->id),
            ], function ($m) use ($user){
                $m->from('ali@anglotestserver.website', 'VPC');
                $m->to($user->email)->subject('VPC Password Reset');
            });

            return Helper::successResponse([], 'Password has been sent to your email address');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getMessage(), $e->getCode());
        }
    }
    function resetPassword(Request $req){
        //$id = Crypt::decryptString($req->id);
        $id = $req->id;
        $parse = [
            "user_id" => $id,
            "form" => true,
        ];
        return view('user.forgetpassword', $parse);
    }
    function UpdatePassword(Request $req){
        $validator = Validator::make($req->all(), [
            'reset_password' => 'required',
        ]);
        if ($validator->fails()) {
            return Helper::errorResponse(422, $validator->errors()->first(), $validator->errors());
        }
        $id = Crypt::decryptString($req->id);
        User::where('id', $id)->update([
            'password' => bcrypt($req->reset_password),
        ]);
        $parse = [
            "user_id" => $id,
            "form" => false,
        ];
        return view('user.forgetpassword', $parse);
    }
}
