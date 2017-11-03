<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mylogdetailtitle extends Model
{
    protected $guarded = ['id','created_at','updated_at'];
    public $incrementing = false;
    protected $primaryKey = 'title_id';
    public function scene(){
        return $this->hasMany(Mylogdetailscene::class);
    }
    public function photo(){
        return $this->hasManyThrough(Photo::class,Mylogdetailscene::class,'title_id','scene_id');
    }
}
