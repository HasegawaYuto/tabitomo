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
      $user = User::find($id);
      $locations = Location::all();
      $prefs = Pref::all();
      foreach($prefs as $pref){
          $prefData["00"]="--都道府県--";
          $prefData[$pref->pref_id]=$pref->pref_name;
      }
      return view('bodys.user_menu.profile',['id'=>$id,'user'=>$user,'thisyear'=>Carbon::now()->year,'prefs'=>$prefData,'locations'=>$locations,]);
    }
    public function showUserMessages($id){
      return view('bodys.user_menu.messages',['id'=>$id,]);
    }
    public function showUserItems($id){
      return view('bodys.user_menu.items',['id'=>$id,]);
    }
    public function showUserFavorites($id){
      return view('bodys.user_menu.favorites',['id'=>$id,]);
    }
    public function showUserMatching($id){
      return view('bodys.user_menu.matching',['id'=>$id,]);
    }

    public function createItems(Request $request,$id){
      if(\Input::get('fin')){
          return redirect('user/'. $id .'/mylog');
      }elseif(\Input::get('con')){
          //return redirect()->back();
          $scene_id = $request->scene_id+1;
          return view('bodys.user_menu.items',[
                'id'=>$id,
                'activetab'=>'2',
                'title_id'=>$request->title_id,
                'scene_id'=>$scene_id]);
          //return redirect()->back()->withInput($data);
      }
    }
    public function showTitle($id,$title_id){
      return view('bodys.user_menu.show_title',['id'=>$id,'title_id'=>$title_id]);
    }
}
