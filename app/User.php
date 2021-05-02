<?php

namespace App;

use App\User\UserAssistant;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    // protected $guard_name = 'users';
    protected $appends = ['role_names','manager_access'];
    // protected $fillable = [
    //     'name', 'email', 'password',
    // ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getRoleNamesAttribute()
    {
        return $this->getRoleNames();
    }

    function managerAssistant(){
        return $this->hasMany(UserAssistant::class, "manager_id", "id");
    }

    function devices(){
        return $this->hasMany(Device::class);
    }
    
    function contract(){
        return $this->hasOne(Contract::class);
    }

    function contracts(){
        return $this->hasMany(Contract::class);
    }

    function teamManager(){
        return $this->hasMany(TeamManager::class, "user_id", "id");
    }
    public function getManagerAccessAttribute()
    {   
        if ($this->hasRole(['assistant', 'manager'])) {
            return 1;
        }
        return 0;
    }
    public function getPosition(){
        return $this->hasOne(Position::class, "id", "position_id");
    }
    public function getTeam(){
        return $this->hasOne(Team::class, "id", "selected_team");
    }
    public function getMode(){
        return $this->hasOne(Mode::class, "id", "mode_id");
    }
}
