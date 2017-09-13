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
      $user = App\Profile::where('user_id',$id)->first();
      $locations = Location::all();
      $prefs = Pref::all();
      $data['id']=$id;
      $data['thisyear']=Carbon::now()->year;
      $data['locations']=$locations;
      foreach($prefs as $pref){
          $prefData["00"]="--都道府県--";
          $prefData[$pref->pref_id]=$pref->pref_name;
      }
      $data['prefs']=$prefData;
      if(!isset($user->birthday)){
          $data['birthday'] = '未設定';
          $data['birthdayOfYear'] = null;
          $data['birthdayOfMonth'] = null;
          $data['birthdayOfDay'] = null;
      }else{
          $birthday = new Carbon($user->birthday);
          if($user->age <=1 ){
              $data['birthday'] = '未設定';
          }else{
              $data['birthday'] = $birthday->format('Y年m月d日') . '生';
          }
          $data['birthdayOfYear'] = $birthday->year;
          $data['birthdayOfMonth'] = $birthday->month;
          $data['birthdayOfDay'] = $birthday->day;
      }
      if(!isset($user->nickname)){
          $data['nickname'] = '未設定';
          $data['nicknameDefault'] = '';
      }else{
          $data['nickname'] = $user->nickname;
          $data['nicknameDefault'] = $user->nickname;
      }
      if(!isset($user->age)){
          $data['age'] = '';
      }else{
          if($user->age==0){
              $data['age'] = '';
          }else{
              $data['age'] = '【' . $user->age . '歳' . '】';
          }
      }
      if(!isset($user->sex)){
          $data['sex'] = '未設定';
      }else{
          $data['sex'] = $user->sex;
      }
      if(!isset($user->area)){
          $data['area'] = '未設定';
      }else{
          $data['area'] = $user->area;
      }
      if(!isset($user->data) || !isset($user->mime)){
          $data['data'] = '未設定';
          $data['mime'] = '未設定';
      }else{
          $data['data'] = base64_encode($user->data);
          $data['mime'] = $user->mime;
      }
      return view('bodys.user_menu.profile',$data);
    }

///////////////////////////////////////////////////////////////

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
