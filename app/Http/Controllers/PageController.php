<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App;
use App\User;
use Carbon\Carbon;
use App\Location;
use App\Pref;
use App\Profile;

class PageController extends Controller
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

    public function newProfileCreate(){
        $user_id = \Auth::user()->id;
        $profile = Profile::where('user_id',$user_id)->first();
        if(!$profile){
            $profile =  new Profile;
            $profile->user_id = $user_id;
            $profile->save();
        }
        return redirect('/');
    }



    public function showGuides(){
      return view('bodys.show_guides');
    }

    public function showTravelers(){
      return view('bodys.show_travelers');
    }

    public function showItems(){
      return view('bodys.show_items');
    }

    public function showUserProfile($id){
      $user = Profile::where('user_id',$id)->first();
      $data['user']=$user;
      $data['id']=$id;

      //$locations = Location::all();
      $data['locations']=Location::all();

      $prefs = Pref::all();
      $prefData["00"]="--都道府県--";
      foreach($prefs as $pref){
          $prefData[$pref->pref_id]=$pref->pref_name;
      }
      $data['prefs']=$prefData;

      $data['thisyear']=Carbon::now()->year;

    return view('bodys.user_menu.profile',$data);
  }

///////////////////////////////////////////////////////////////

    public function showUserMessages($id){
      $user = Profile::where('user_id',$id)->first();
      $data['user']=$user;
      $data['id']=$id;
      return view('bodys.user_menu.messages',$data);
    }
    public function showUserItems($id){
      $user = Profile::where('user_id',$id)->first();
      $data['user']=$user;
      $data['id']=$id;
      return view('bodys.user_menu.items',$data);
    }
    public function showUserFavorites($id){
      $user = Profile::where('user_id',$id)->first();
      $data['user']=$user;
      $data['id']=$id;
      return view('bodys.user_menu.favorites',$data);
    }
    public function showUserMatching($id){
      $user = Profile::where('user_id',$id)->first();
      $data['user']=$user;
      $data['id']=$id;
      return view('bodys.user_menu.matching',$data);
    }

    public function createItems(Request $request,$id){
      $user = Profile::where('user_id',$id)->first();
      $data['user']=$user;
      $data['id']=$id;
      if(\Input::get('fin')){
          return redirect('user/'. $id .'/mylog');
      }elseif(\Input::get('con')){
          //return redirect()->back();
          //$scene_id = $request->scene_id+1;
          $data['activetab'] = '2';
          $data['title_id'] = $request->title_id;
          $data['scene_id'] = $request->scene_id+1;
          return view('bodys.user_menu.items',$data);
          //return redirect()->back()->withInput($data);
      }
    }
    public function showTitle($id,$title_id){
      $user = Profile::where('user_id',$id)->first();
      $data['user']=$user;
      $data['id']=$id;
      $data['title_id']=$title_id;
      return view('bodys.user_menu.show_title',$data);
    }
}
