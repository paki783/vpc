<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Platform;

class VpcPlatformAssign extends Model
{
    //
    protected $guarded = [];
    protected $table = "vpc_platform_assign";

    function getPlateform(){
        return $this->hasOne(Platform::class, "id", "platform_id");
    }
}
