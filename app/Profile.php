<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';
    protected $fillable = ['user_id','nickname','sex','birthday','age','area','path','mime','data'];
    protected $softDelete=true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
