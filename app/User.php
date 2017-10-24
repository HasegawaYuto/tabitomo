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
    protected $guarded = ['id','remember_token','created_at','updated_at'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    ///
    //
    public function title(){
        return $this->hasMany(Mylogdetailtitle::class);
    }
    public function scene(){
        return $this->hasManyThrough(Mylogdetailscene::class,Mylogdetailtitle::class,'user_id','title_id');
    }
    
    public function comments(){
        return $this->belongsToMany(Mylogdetailscene::class,'comments','user_id','scene_id')->withPivot('comment','comment_id')->withTimestamps();
    }
    public function commentTo($sceneid,$comment){
        $this->comments()->attach($sceneid,['comment'=>$comment]);
        return true;
    }
    
    public function favor(){
        return $this->belongsToMany(Mylogdetailscene::class,'mylog_user','user_id','scene_id')->withTimestamps();
    }
    public function is_favoritesScene($sceneid){
        return $this->favor()->where('scene_id',$sceneid)->exists();
    }
    public function favoringScene($sceneid){
        $exists = $this->is_favoritesScene($sceneid);
        if($exists){
            return false;
        }else{
            $this->favor()->attach($sceneid);
            return true;
        }
    }
    public function unfavoringScene($sceneid){
        $exists = $this->is_favoritesScene($sceneid);
        if(!$exists){
            return false;
        }else{
            $this->favor()->detach($sceneid);
            return true;
        }
    }
    public function is_favoritesTitle($titleid){
        $sceneids = Mylogdetailscene::where('title_id',$titleid)->lists('scene_id');
        $arr=[];
        foreach($sceneids as $sceneid){
            if(!$this->is_favoritesScene($sceneid)){
                $arr[]=$sceneid;
            }
        }
        return empty($arr);
    }
    public function favoringTitle($titleid){
        $exists = $this->is_favoritesTitle($titleid);
        if($exists){
            return false;
        }else{
            $sceneids = Mylogdetailscene::where('title_id',$titleid)->lists('scene_id');
            foreach($sceneids as $sceneid){
                if(!$this->is_favoritesScene($sceneid)){
                    $this->favor()->attach($sceneid);
                }
            }
            return true;
        }
    }
    public function unfavoringTitle($titleid){
        $exists = $this->is_favoritesTitle($titleid);
        if(!$exists){
            return false;
        }else{
            $sceneids = Mylogdetailscene::where('title_id',$titleid)->lists('scene_id');
            foreach($sceneids as $sceneid){
                if($this->is_favoritesScene($sceneid)){
                    $this->favor()->detach($sceneid);
                }
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

    public function follow(){
        return $this->belongsToMany(User::class,'follows','user_id','follow_id')->withTimestamps();
    }
    public function follower(){
        return $this->belongsToMany(User::class,'follows','follow_id','user_id')->withTimestamps();
    }
    public function is_following($id){
        return $this->follow()->where('follow_id',$id)->exists();
    }
    public function is_followed($id){
        return $this->follower()->where('user_id',$id)->exists();
    }
    public function following($id){
        $exists = $this->is_following($id);
        $itsme = $this->id!=$id;
        if(!$exists && $itsme){
            $this->follow()->attach($id);
            return true;
        }else{
            return false;
        }
    }
    public function unfollowing($id){
        $exists = $this->is_following($id);
        $itsme = $this->id!=$id;
        if($exists && $itsme){
            $this->follow()->detach($id);
            return true;
        }else{
            return false;
        }
    }

    public function sendTo(){
        return $this->belongsToMany(User::class,'messages','user_id','send_id')
                    ->withPivot('message','status')
                    ->withTimestamps();
    }
    public function sendFrom(){
        return $this->belongsToMany(User::class,'messages','send_id','user_id')
                    ->withPivot('message','status')
                    ->withTimestamps();
    }
    public function sendMessage($id,$message){
        $this->sendTo()->attach($id,['message'=>$message]);
        return true;
    }

    public function getMessages($id){
        $sendtoids = $this->sendTo()->where('send_id',$id)->lists('messages.id');
        $sendfromids = $this->sendFrom()->where('user_id',$id)->lists('messages.id');
        $messageids = $sendtoids->merge($sendfromids);
        return \DB::table('messages')->whereIn('id',$messageids);
    }
    
    public function newMessageHas(){
        $bool = \DB::table('messages')->where('send_id',$this->id)->where('status','0')->exists();
        if($bool){
            return true;
        }else{
            return false;
        }
    }
}
