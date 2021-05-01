<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Platform;
use App\Games;
use App\VpcPlatformAssign;
use App\VpcSystemUserAssign;

class VPCSystems extends Model
{
    //
    protected $guarded = [];
    protected $table = "vpcsystems";

    public function getVpcPlatformAssign()
    {
        return $this->hasMany(VpcPlatformAssign::class, "vpc_id", "id");
    }
    public function GetGame()
    {
        return $this->hasOne(Games::class, 'id', 'game');
    }

    public function GetVpcAssignUser()
    {
        return $this->hasMany(VpcSystemUserAssign::class, 'vpc_id', 'id');
    }
}
