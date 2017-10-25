<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Pref;
use App\Location;
use App\Profile,App\User;
use App;
use Carbon\Carbon;

class UserOptionController extends Controller
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
    public function edit(Request $request , $id)
    {
        $profile = User::find($id);//Profile::firstOrNew(['user_id'=> $id]);
        //$profile['user_id']=$id;
        if(\Input::hasFile('avatar')){
            if (!isset($_FILES['avatar']['error']) || !is_int($_FILES['avatar']['error'])) {
                return false;
            }else{
              $typearray = [
                'gif' => 'image/gif',
                'jpg' => 'image/jpeg',
                'png' => 'image/png'
              ];
              if(array_search(mime_content_type($_FILES['avatar']['tmp_name']),$typearray)){
                  $file = \Input::file('avatar');
                  $filename = '/tmp/avatar' . $id . '.' . $file->getClientOriginalExtension();
                  $image = \Image::make($file->getRealPath())->resize(300, null, function ($constraint) {
                        $constraint->aspectRatio();
                      })->orientate()->save($filename);
                  $profile['data']=file_get_contents($filename);
                  $profile['mime']=$file->getMimeType();
                  //$profile['path']=
              }
            }
        }
        if(\Input::get('nickname')){
            $profile['nickname']=$request->nickname;
        }
        $BDsetcheck = \Input::get('year') && \Input::get('month') && \Input::get('day');
        $BDvalcheck = $request->year != "0000" && $request->month != "00" && $request->day != "00";
        if($BDsetcheck && $BDvalcheck){
            $birthday = $request->year . '-' . $request->month . '-' . $request->day;
            $profile['birthday']=$birthday;
            //$carbonForAge = Carbon::parse($birthday);
            //$profile['age']=$carbonForAge->age;
        }
        if(\Input::get('sex')){
            $profile['sex']=$request->sex;
        }
        $Areasetcheck = \Input::get('pref_id') && \Input::get('city_id');
        $Areavalcheck = \Input::get('pref_id') != "00" && \Input::get('city_id') != "00000";
        if($Areasetcheck && $Areavalcheck){
            $pref = Pref::where('pref_id',$request->pref_id)->first();
            $city = Location::where('city_id',$request->city_id)->first();
            $profile['area']=$pref->pref_name . $city->city_name;
        }
        $profile->save();
        if(isset($filename)){
          if (\File::exists($filename)) {
                \File::delete($filename);
            }
        }
        return redirect()->back();
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
}
