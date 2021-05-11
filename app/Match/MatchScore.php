<?php

namespace App\Match;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class MatchScore extends Model
{
    //
    protected $guarded = [];

    protected $appends = ['image_path'];

    public function getImagePathAttribute()
    {
        $res = URL::to('storage/app/public/match');
        return $res;
    }

}
