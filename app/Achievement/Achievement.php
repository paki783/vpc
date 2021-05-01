<?php

namespace App\Achievement;

use Illuminate\Database\Eloquent\Model;
use App\Attachment;

class Achievement extends Model
{
    //
    protected $guarded = [];

    function getPicture(){
        return $this->hasOne(Attachment::class, "type_id", "id")->where([
            "type" => "award",
            "model_name" => "award",
        ]);
    }

    function getPictureMedal(){
        return $this->hasOne(Attachment::class, "type_id", "id")->where([
            "type" => "medal",
            "model_name" => "medal",
        ]);
    }
}
