<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Attachment;

class Countries extends Model
{
    //
    protected $guarded = [];
    protected $table = "countries";
    protected $appends = ['country_flag'];
    
    function getCountryFlagAttribute(){
        //return $this->hasOne(Attachment::class, "type_id", "id")->where("type", "country")->first();
        $res = [];
        if ($this->getFlag()->exists()) {
            if(empty($this->getFlag()->first())){
                $res = (Object)array();
            }else{
                $res = $this->getFlag()->first();
            }
        }
        return $res;
    }
    function getFlag(){
        return $this->hasOne(Attachment::class, "type_id", "id")->where("type", "country");
    }
}
