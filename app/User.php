<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    ///
    //
    public function mylogs(){
        return $this->hasMany(Mylog::class);
    }
    public function title($titleid){
        return $this->hasMany(Mylog::class)->where('title_id',$titleid);
    }
    public function scene($titleid,$sceneid){
        return $this->hasMany(Mylog::class)->where('title_id',$titleid)->where('scene_id',$sceneid);
    }

    public function profile(){
        return $this->hasOne(Profile::class);
    }

    public function favors(){
        return $this->belongsToMany(Mylog::class,'mylog_user','user_id','scene_id')->withTimestamps();
    }
    public function comments(){
        return $this->belongsToMany(Mylog::class,'comments','user_id','scene_id')->withPivot('comment','comment_id')->withTimestamps();
    }
    public function commentTo($userid,$titleid,$sceneid,$comment){
        $sceneidLists = $this->find($userid)->scene($titleid,$sceneid)->lists('mylogs.id');
        $latestCommentId = $this->comments()->whereIn('comments.scene_id',$sceneidLists)->max('comment_id');
        if(!isset($latestCommentId)){
            $latestCommentId = 0;
        }
        foreach($sceneidLists as $sceneidList){
            $this->comments()->attach($sceneidList,['comments.comment'=>$comment,'comment_id'=>$latestCommentId+1]);
        }
        return true;
    }
    public function commentDel($userid,$titleid,$sceneid,$commentuserid,$commentid){
        //$sceneidLists = $this->find($userid)->scene($titleid,$sceneid)->lists('mylogs.id');
        //foreach($sceneidLists as $sceneidList){
        //$this->comments()->where('comments.scene_id',$sceneidList)->wherePivot('comment_id',$commentid)->detach();
        //}
        //return true;
    }

    public function is_favoritesScene($userid,$titleid,$sceneid){
        $sceneidLists = $this->find($userid)->scene($titleid,$sceneid)->lists('mylogs.id');
        return $this->favors()->whereIn('mylog_user.scene_id',$sceneidLists)->exists();
    }
    public function favoringScene($userid,$titleid,$sceneid){
        $exists = $this->is_favoritesScene($userid,$titleid,$sceneid);
        if($exists){
            return false;
        }else{
            $sceneidLists = $this->find($userid)->scene($titleid,$sceneid)->lists('mylogs.id');
            foreach($sceneidLists as $sceneidList){
                $this->favors()->attach($sceneidList);
            }
            return true;
        }
    }

    public function unfavoringScene($userid,$titleid,$sceneid){
        $exists = $this->is_favoritesScene($userid,$titleid,$sceneid);
        if(!$exists){
            return false;
        }else{
            $sceneidLists = $this->find($userid)->scene($titleid,$sceneid)->lists('mylogs.id');
            foreach($sceneidLists as $sceneidList){
                $this->favors()->detach($sceneidList);
            }
            return true;
        }
    }


    public function is_favoritesTitle($userid,$titleid){
        $titleidLists = $this->find($userid)->title($titleid)->lists('mylogs.id');
        return $this->favors()->whereIn('mylog_user.scene_id',$titleidLists)->exists();
    }
    public function favoringTitle($userid,$titleid,$sceneid){
        $exists = $this->is_favoritesTitle($userid,$titleid);
        if($exists){
            return false;
        }else{
            $titleidLists = $this->find($userid)->title($titleid)->lists('mylogs.id');
            foreach($titleidLists as $titleidList){
                $this->favors()->attach($titleidList);
            }
            return true;
        }
    }

    public function unfavoringTitle($userid,$titleid){
        $exists = $this->is_favoritesTitle($userid,$titleid);
        if(!$exists){
            return false;
        }else{
            $titleidLists = $this->find($userid)->title($titleid)->lists('mylogs.id');
            foreach($titleidLists as $titleidList){
                $this->favors()->detach($titleidList);
            }
            return true;
        }
    }

    public function guestguide(){
        return $this->hasMany(Guestguide::class);
    }
    public function recruites(){
        return $this->belongsToMany(Guestguide::class,'recruitments','user_id','recruitment_id')->withTimestamps();
    }
    public function is_recruite($itemid){
        return $this->recruites()->where('recruitment_id',$itemid)->exists();
    }
    public function candidating($itemid){
        $exists = $this->is_recruite($itemid);
        if(!$exists){
            $this->recruites()->attach($itemid);
            return true;
        }else{
            return false;
        }
    }
    public function uncandidating($itemid){
        $exists = $this->is_recruite($itemid);
        if($exists){
            $this->recruites()->detach($itemid);
            return true;
        }else{
            return false;
        }
    }
}
