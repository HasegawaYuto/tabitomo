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
    public function mylogs()
    {
        return $this->hasMany(Mylog::class);
    }
    public function title($titleid)
    {
        return $this->hasMany(Mylog::class)->where('title_id',$titleid);
    }
    public function scene($titleid,$sceneid)
    {
        return $this->hasMany(Mylog::class)->where('title_id',$titleid)->where('scene_id',$sceneid);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function favors()
    {
        return $this->belongsToMany(Mylog::class,'mylog_user','user_id','scene_id')->withTimestamps();
    }
    public function is_favoritesScene($userid,$titleid,$sceneid){
        $sceneidLists = $this->find($userid)->scene($titleid,$sceneid)->lists('id');
        return $this->favors()->whereIn('mylog_user.scene_id',$sceneidLists)->exists();
    }
    public function favoringScene($userid,$titleid,$sceneid){
        $exists = $this->is_favoritesScene($userid,$titleid,$sceneid);
        if($exists){
            return false;
        }else{
            $sceneidLists = $this->find($userid)->scene($titleid,$sceneid)->lists('id');
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
            $sceneidLists = $this->find($userid)->scene($titleid,$sceneid)->lists('id');
            foreach($sceneidLists as $sceneidList){
                $this->favors()->detach($sceneidList);
            }
            return true;
        }
    }


    public function is_favoritesTitle($userid,$titleid){
        $titleidLists = $this->find($userid)->title($titleid)->lists('id');
        return $this->favors()->whereIn('mylog_user.scene_id',$titleidLists)->exists();
    }
    public function favoringTitle($userid,$titleid,$sceneid){
        $exists = $this->is_favoritesTitle($userid,$titleid);
        if($exists){
            return false;
        }else{
            $titleidLists = $this->find($userid)->title($titleid)->lists('id');
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
            $titleidLists = $this->find($userid)->title($titleid)->lists('id');
            foreach($titleidLists as $titleidList){
                $this->favors()->detach($titleidList);
            }
            return true;
        }
    }
}
