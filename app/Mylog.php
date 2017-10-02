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

    public static function getSceneIds($userid,$titleid,$sceneid){
        return self::where('user_id',$userid)->where('title_id',$titleid)->where('scene_id',$sceneid)->lists('id');
    }

    public static function getFavoredCount($userid,$titleid,$sceneid){
        $sceneids = self::getSceneIds($userid,$titleid,$sceneid);
        return $this->favoredBy()->whereIn('scene_id',$sceneids)->groupBy('user_id')->count();
    }

}
