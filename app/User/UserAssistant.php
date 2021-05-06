<?php

namespace App\User;

use Illuminate\Database\Eloquent\Model;
use App\User;

class UserAssistant extends Model
{
    //
    protected $guarded = [];

    function getUser() {
        return $this->hasOne(User::class, "id", "user_id");
    }

    function getManager() {
        return $this->hasOne(User::class, "id", "manager_id");
    }

}
