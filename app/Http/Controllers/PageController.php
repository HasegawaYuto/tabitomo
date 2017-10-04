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
            ->where(function($query){
                if(\Auth::check()){
                    $query->where('publish','public')->orWhere('user_id',\Auth::user()->id);
                }else{
                    $query->where('publish','public');
                }
            })
            ->orderBy('updated_at','desc')
            ->groupBy('user_id','title_id','scene_id')
            ->paginate(24);
      $data['scenes'] = $scenes;
      $data['photos'] = Mylog::select('mime','data','scene_id','id','user_id','title_id')
                                ->whereNotNull('data')
                                ->where(function($query){
                                    if(\Auth::check()){
                                        $query->where('publish','public')->orWhere('user_id',\Auth::user()->id);
                                    }else{
                                        $query->where('publish','public');
                                    }
                                })
                                ->get();
      foreach($scenes as $key => $scene){
          $data['userComments'][$key] = Mylog::find($scene->id)->commented()->select('comments.user_id AS TheUserID','comments.comment','comments.comment_id')->groupBy('comments.comment_id','comments.comment')->orderBy('comments.created_at','asc')->get();
          foreach($data['userComments'][$key] as $kkey => $userComment){
              $data['commentUser'][$key][$kkey] = User::find($userComment->TheUserID)->profile;
          }
          $data['favuser'][$key] = Mylog::find($scene->id)->favoredBy()->groupBy('mylog_user.user_id')->count();
          $data['user'][$scene->user_id]=Profile::select('data','mime','nickname')->where('user_id',$scene->user_id)->first();
          $thumbID = User::find($scene->user_id)->scene($scene->title_id,$scene->scene_id)
                                  ->whereNotNull('data')
                                  ->select('id')
                                  ->where(function($query){
                                      if(\Auth::check()){
                                          $query->where('publish','public')->orWhere('user_id',\Auth::user()->id);
                                      }else{
                                          $query->where('publish','public');
                                      }
                                    })
                                  ->orderByRaw("RAND()")->first();
          if(isset($thumbID)){
              $data['thumb'][$key] = Mylog::select('mime','data')->find($thumbID->id);
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
      $titles = $user->mylogs()
                      ->where(function($query)use($id){
                          if(\Auth::user()->id != $id){
                                $query->where('publish','public');
                          }else{
                              $query;
                          }
                      })
                      ->groupBy('title_id')
                      ->select('title_id','title','firstday','lastday','user_id')
                      ->orderBy('theday','desc')
                      ->paginate(10);
      foreach($titles as $key => $title){
          $data['scenes'][$key] = $user->title($title->title_id)
                                ->where(function($query)use($id){
                                    if(\Auth::user()->id!=$id){
                                        $query->where('publish','public');
                                    }else{
                                        $query;
                                    }
                                })
                                ->groupBy('scene_id')
                                ->select('scene')
                                ->orderBy('theday')
                                ->get();
          $thumbID = $user->title($title->title_id)->where('publish','public')
                            ->whereNotNull('data')
                            ->select('id')
                            ->orderByRaw("RAND()")
                            ->first();
          if(isset($thumbID)){
              $data['thumb'][$key] = Mylog::select('mime','data')->find($thumbID->id);
          }
      }
      $data['user']=$user->profile;
      $data['titles']=$titles;
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
                              ->select('title','firstday','lastday','title_id','user_id')
                              ->where(function($query)use($id){
                                  if(\Auth::user()->id != $id){
                                      $query->where('publish','public');
                                  }else{
                                      $query;
                                  }
                              })
                              ->first();
      $scenes = $user->title($title_id)
                      ->groupBy('scene_id')
                      ->orderBy('theday')
                      ->select('publish','scene','theday','lat','lng','score','comment','scene_id','title','user_id','title_id','firstday','lastday','theday','id')
                      ->where(function($query)use($id){
                          if(\Auth::user()->id != $id){
                              $query->where('publish','public');
                          }else{
                              $query;
                          }
                      })
                      ->paginate(5);
      $data['scenes'] = $scenes;
      $data['newsceneid'] = $user->title($title_id)->max('scene_id')+1;
      $data['scoreAve'] = $user->title($title_id)
                              ->where('publish','public')
                              ->orderBy('scene_id')
                              ->avg('score');
      $data['photos'] = $user->title($title_id)
                              ->whereNotNull('data')
                              ->select('mime','data','scene_id','id','user_id','title_id')
                              ->where(function($query)use($id){
                                  if(\Auth::user()->id != $id){
                                      $query->where('publish','public');
                                  }else{
                                      $query;
                                  }
                              })
                              ->get();
      foreach($scenes as $key => $scene){
          $data['favuser'][$key] = Mylog::find($scene->id)->favoredBy()->groupBy('mylog_user.user_id')->count();
          $thumbID = $user->scene($title_id,$scene->scene_id)
                                  ->whereNotNull('data')
                                  ->select('id')
                                  ->orderBy('theday')
                                  ->where(function($query)use($id){
                                      if(\Auth::user()->id != $id){
                                          $query->where('publish','public');
                                      }else{
                                          $query;
                                      }
                                  })
                                  ->orderByRaw("RAND()")
                                  ->first();
          if(isset($thumbID)){
              $data['thumb'][$key] = Mylog::select('mime','data')->find($thumbID->id);
          }
      }
      $data['user']=$user->profile;
      //$data['id']=$id;
      $data['thisyear']=Carbon::now()->year;
      return view('bodys.user_menu.show_title',$data);
    }
}
