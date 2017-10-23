<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mylogdetailscene extends Model
{
    protected $guarded = ['id','created_at','updated_at'];
    public function photo(){
        return $this->hasMany(Photo::class);
    }
    public function favoredBy()
    {
        return $this->belongsToMany(User::class,'mylog_user','scene_id','user_id')->withTimestamps();
    }
}
