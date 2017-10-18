<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\User;

class SocialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function getFacebookAuth() {
        return Socialite::driver('facebook')->redirect();
    }
    
    public function getFacebookCallback() {
        try {
            $fuser = Socialite::driver('facebook')->user();
        } catch (\Exception $e) {
            return redirect("/login");
        }
        if ($fuser) {
            //dd($fuser);
            $loginuser=User::firstOrCreate(['email'=>$fuser->getEmail()]);
            \Auth::login($loginuser);
            /*
            if(!isset($loginuser->sex)){
                if($fuser->$getUser()->gender=='Male'){
                    $loginuser['sex'] = '男性';
                }elseif($fuser->$getUser()->gender=='Female'){
                    $loginuser['sex'] = '女性';
                }else{
                    $loginuser['sex'] = 'その他';
                }
            }
            */
            if(!isset($loginuser->nickname)){
                $loginuser['nickname']=$fuser->getNickname();
            }
            $loginuser['facebook_id']=$fuser->getId();
            $loginuser['name']=$fuser->getName();
            $loginuser['snsImagePath']=$fuser->getAvatar();
            $loginuser->save();
            return redirect("/");
        } else {
            return 'something went wrong';
        }
    }
    
    public function getGoogleAuth() {
        //return redirect("/");
        return Socialite::driver('google')->redirect();
    }
 
    public function getGoogleCallback() {
        try {
            $guser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect("/login");
        }
        if ($guser) {
            //dd($guser);
            $loginuser=User::firstOrCreate(['email'=>$guser->getEmail()]);
            \Auth::login($loginuser);
            if(!isset($loginuser->nickname)){
                $loginuser['nickname']=$fuser->getNickname();
            }
            $loginuser['google_id']=$guser->getId();
            $loginuser['name']=$guser->getName();
            $loginuser['snsImagePath']=$guser->getAvatar();
            $loginuser->save();
            return redirect("/");
        } else {
            return 'something went wrong';
        }
    }
    
    public function getTwitterAuth() {
        return Socialite::driver('twitter')->redirect();
    }
 
    public function getTwitterCallback() {
        try {
            $tuser = Socialite::driver('twitter')->user();
        } catch (\Exception $e) {
            return redirect("/");
        }
        if ($tuser) {
            dd($tuser);
            /*
            $loginuser=User::firstOrCreate(['email'=>$tuser->getEmail()]);
            \Auth::login($loginuser);
            //if(!isset($loginuser->sex)){
            //    if($fuser->getGender()=='Male'){
            //        $loginuser['sex'] = '男性';
            //    }elseif($fuser->getGender()=='Female'){
            //        $loginuser['sex'] = '女性';
            //    }else{
            //        $loginuser['sex'] = 'その他';
            //    }
            //}
            if(!isset($loginuser->nickname)){
                $loginuser['nickname']=$tuser['nickname'];
            }
            $loginuser['twitter_id']=$tuser->getId();
            $loginuser['name']=$tuser->getName();
            $loginuser['snsImagePath']=$tuser->getAvatar();
            $loginuser->save();
            return redirect("/");
            */
        } else {
            return 'something went wrong';
        }
    }
       
}