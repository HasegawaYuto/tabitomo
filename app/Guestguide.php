<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guestguide extends Model
{

    protected $guarded = ['id','created_at','updated_at'];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function recruited(){
        return $this->belongsToMany(User::class,'recruitments','recruitment_id','user_id')->withTimestamps();
    }
}
