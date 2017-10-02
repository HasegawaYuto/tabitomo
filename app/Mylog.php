<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mylog extends Model
{
    //
    protected $guarded = ['id','created_at','updated_at'];
    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favoredBy()
    {
        return $this->belongsToMany(User::class,'mylog_user','scene_id','user_id')->withTimestamps();
    }

}
