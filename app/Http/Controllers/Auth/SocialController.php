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
            $fuser = Socialite::driver('facebook')->fields([
                    'name', 
                    'email', 
                    'gender', 
                    'id',
                    'avatar',
                ])->user();
        } catch (\Exception $e) {
            return redirect("/login");
        }
        if ($fuser) {
            $email = $fuser->getEmail();
            $name = $fuser['name'];
            //$nickname = $fuser['nickname'];
            //$avatar = $fuser['avatar'];
            $id = $fuser['id'];
            $loginuser=User::firstOrCreate(['email'=>$email],[
                        'name'=>$name,
                        //'nickname'=>$nickname,
                        //'snsImagePath'=>$avatar,
                        'facebook_id'=>$id
                        ]);
            \Auth::login($loginuser);
            return redirect("/");
        } else {
            return 'something went wrong';
        }
    }
}
