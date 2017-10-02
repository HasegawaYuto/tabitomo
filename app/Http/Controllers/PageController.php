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
use App\Profile,App\Mylog;
use Illuminate\Support\Facades\Log;

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
        $profile = Profile::firstOrNew(['user_id'=> $user_id]);
        $profile->user_id = $user_id;
        $profile->save();
        return redirect('/');
    }



    public function showGuides(){
      return view('bodys.show_guides');
    }

    public function showTravelers(){
      return view('bodys.show_travelers');
    }

    public function showItems(){
      $scenes = Mylog::select('scene','lat','lng','user_id','title_id','title','scene_id','score','comment','theday','publish','firstday','lastday','theday','id')
                      ->orderBy('updated_at','desc')
                      ->groupBy('user_id','title_id','scene_id');
      if(\Auth::check()){
          $scenes = $scenes->where(function($query){
              $query->where('publish','public')->orWhere('user_id',\Auth::user()->id);
          });
      }else{
          $scenes = $scenes->where('publish','public');
      }
      $scenes = $scenes->paginate(24);
                      //->get();
      $data['scenes'] = $scenes;
      $data['photos'] = Mylog::select('mime','data','scene_id','id','user_id','title_id')
                                ->whereNotNull('data');
      if(\Auth::check()){
          $data['photos'] = $data['photos']->where(function($query){
              $query->where('publish','public')->orWhere('user_id',\Auth::user()->id);
          });
      }else{
          $data['photos'] = $data['photos']->where('publish','public');
      }
      $data['photos'] = $data['photos']->get();
      foreach($scenes as $key => $scene){
          //$newmylog = new Mylog;
          $data['favcount'][$key] = Mylog::getFavoredCount($scene->user_id,$scene->title_id,$scene->scene_id);
          $data['user'][$scene->user_id]=Profile::select('data','mime','nickname')->find($scene->user_id);
          $arr=[];
          $thumbIDs = User::find($scene->user_id)->scene($scene->title_id,$scene->scene_id)
                                  ->whereNotNull('data')
                                  ->select('id');
                                  //->orderBy('theday');
          if(\Auth::check()){
              $thumbIDs = $thumbIDs->where(function($query){
                  $query->where('publish','public')->orWhere('user_id',\Auth::user()->id);
              });
          }else{
              $thumbIDs = $thumbIDs->where('publish','public');
          }
          $thumbIDs = $thumbIDs->get();
          if(isset($thumbIDs)){
              foreach($thumbIDs as $thumbID){
                  $arr[] = $thumbID->id;
              }
              if(isset($arr[0])){
                  $thumbIDrand = array_rand($arr);
                  $data['thumb'][$key] = Mylog::select('mime','data')->find($arr[$thumbIDrand]);
              }
          }
      }
      return view('bodys.show_items',$data);
    }

    public function showUserProfile($id){
      $user = User::find($id);
      $data['user']=$user->profile;

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
      $user = User::find($id);
      $mylogsByTitle = $user->mylogs()
                      ->groupBy('title_id')
                      ->select('title_id','title','firstday','lastday','user_id')
                      ->orderBy('theday','desc');
      if(\Auth::user()->id != $id){
            $mylogsByTitle = $mylogsByTitle->where('publish','public');
      }
      $mylogsByTitle = $mylogsByTitle->paginate(10);

      foreach($mylogsByTitle as $key => $mylogByTitle){
          $arr = [];
          $data['logtitle'][$key] = $user->title($mylogByTitle->title_id)
                                ->groupBy('scene_id')
                                ->select('scene')
                                ->orderBy('theday');
          if(\Auth::user()->id!=$id){
              $data['logtitle'][$key] = $data['logtitle'][$key]->where('publish','public');
          }
          $data['logtitle'][$key]=$data['logtitle'][$key]->get();
          $thumbIDs = $user->title($mylogByTitle->title_id)->where('publish','public')
                            ->whereNotNull('data')
                            ->select('id')
                            ->get();
          //$thumbID = array_rand($thumbIDs[$key],1);
          if(isset($thumbIDs)){
              foreach($thumbIDs as $thumbID){
                  $arr[]=$thumbID->id;
              }
              if(isset($arr[0])){
                  $thumbIDrand = array_rand($arr);
                  $data['thumb'][$key] = Mylog::select('mime','data')->find($arr[$thumbIDrand]);
              }
          }
      }
      $data['user']=$user->profile;
      $data['mylogs']=$mylogsByTitle;
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


    public function showTitle($id,$title_id){
      $user = User::find($id);
      $data['title'] = $user->title($title_id)
                              ->select('title','firstday','lastday','title_id','user_id');
      if(\Auth::user()->id != $id){
          $data['title'] = $data['title']->where('publish','public');
      }
      $data['title'] = $data['title']->first();
      $scenes = $user->title($title_id)
                      ->groupBy('scene_id')
                      ->orderBy('theday')
                      ->select('publish','scene','theday','lat','lng','score','comment','scene_id','title','user_id','title_id','firstday','lastday','theday');
      if(\Auth::user()->id != $id){
          $scenes = $scenes->where('publish','public');
      }
      $scenes = $scenes->paginate(5);
      $data['scenes'] = $scenes;
      $data['newsceneid'] = $user->title($title_id)->max('scene_id')+1;
      $data['scoreAve'] = $user->title($title_id)
                              ->where('publish','public')
                              ->orderBy('scene_id')
                              ->avg('score');
      $data['photos'] = $user->title($title_id)
                              ->whereNotNull('data')
                              ->select('mime','data','scene_id','id','user_id','title_id');
      if(\Auth::user()->id != $id){
          $data['photos'] = $data['photos']->where('publish','public');
      }
      $data['photos'] = $data['photos']->get();
      foreach($scenes as $key => $scene){
          $arr=[];
          $thumbIDs = $user->scene($title_id,$scene->scene_id)
                                  ->whereNotNull('data')
                                  ->select('id')
                                  ->orderBy('theday');
          if(\Auth::user()->id != $id){
              $thumbIDs = $thumbIDs->where('publish','public');
          }
          $thumbIDs = $thumbIDs->get();
          if(isset($thumbIDs)){
              foreach($thumbIDs as $thumbID){
                  $arr[] = $thumbID->id;
              }
              if(isset($arr[0])){
                  $thumbIDrand = array_rand($arr);
                  $data['thumb'][$key] = Mylog::select('mime','data')->find($arr[$thumbIDrand]);
              }
          }
      }
      $data['user']=$user->profile;
      //$data['id']=$id;
      $data['thisyear']=Carbon::now()->year;
      return view('bodys.user_menu.show_title',$data);
    }
}
