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
use App\Mylogdetailtitle,App\Mylogdetailscene,App\Guestguide,App\Photo;
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

    public function showItemsSearch(Request $request){
      $request->flash();
      $keywordNotExists = $request->keywords=="";
      $termNotWildCard = $request->year1=="0000"&&$request->month1=="00"&&$request->day1=="00";
      $termNotBetween = $request->year2=="0000"&&$request->month2=="01"&&($request->day2=="01" xor $request->day2=="00")&&$request->year3=="9999"&&$request->month3=="12"&&($request->day3=="31" xor $request->day3=="00");
      $areaNot = $request->lat=="0"&&$request->lng=="0"&&$request->radius=="0";
      $genreNot = $request->genre[0]=="";
      if($keywordNotExists && $termNotWildCard && $termNotBetween && $areaNot && $genreNot){
        return redirect('/');
      }
      $scenes = Mylogdetailscene::where(function($query){
                if(\Auth::check()){
                    if(\DB::table('follows')->where('follow_id',\Auth::user()->id)->exists()){
                        $userids=\DB::table('follows')->where('follow_id',\Auth::user()->id)->lists('user_id');
                        $titleids = Mylogdetailtitle::where('user_id',$userids)->lists('title_id');
                        $query->whereIn('title_id',$titleids)
                              ->where('publish','<>','private')
                              ->orWhere('scene_id','~',\Auth::user()->id.'-*-*');
                    }else{
                        $query->where('publish','public')->orWhere('scene_id','~',\Auth::user()->id.'-*-*');
                    }
                }else{
                    $query->where('publish','public');
                }
            });
      if($request->keywords!=""){
          $keywords = explode(' ',$request->keywords);
          foreach($keywords as $keyword){
            $scenes = $scenes->where(function($query)use($keyword){
                                    $titleids = Mylogdetailtitle::where('title','like','%'.$keyword.'%')->lists('title_id');
                                    $query->whereIn('title_id',$titleids)
                                          ->orWhere('scene','like','%'.$keyword.'%')
                                          ->orWhere('comment','like','%'.$keyword.'%');
                              });
          }
      }
      if($request->year1!='0000'){
          $scenes=$scenes->whereYear('theday','=',$request->year1);
      }
      if($request->month1!='00'){
          $scenes=$scenes->whereMonth('theday','=',$request->month1);
      }
      if($request->day1!='00'){
          $scenes=$scenes->whereDay('theday','=',$request->day1);
      }
      if($request->day3!='00'&&$request->month3!='00'&&$request->year3!='0000'&&$request->year2!='0000'&&$request->month2!='00'&&$request->day2!='00'){
          $startday = $request->year2.'-'.$request->month2.'-'.$request->day2;
          $lastday = $request->year3.'-'.$request->month3.'-'.$request->day3;
          $scenes=$scenes->whereBetween('theday',[$startday,$lastday]);
      }elseif($request->year2!='0000'&&$request->month2!='00'&&$request->day2!='00'){
          $startday = $request->year2.'-'.$request->month2.'-'.$request->day2;
          $scenes=$scenes->where('theday','>=',$startday);
      }elseif($request->day3!='00'&&$request->month3!='00'&&$request->year3!='0000'){
          $lastday = $request->year3.'-'.$request->month3.'-'.$request->day3;
          $scenes=$scenes->where('theday','<=',$lastday);
      }
      if(!$areaNot){
$scenes = $scenes
->whereRaw('6371000*acos(cos(radians(?))*cos(radians(lat))*cos(radians(lng)-radians(?))+sin(radians(?))*sin(radians(lat)))<?',[$request->lat,$request->lng,$request->lat,$request->radius]);
      }
      if(!$genreNot){
          //$genres = implode("",$request->genre);
          $scenes = $scenes->where('genre','ERGEXP','[AB]*');
          //foreach($request->genre as $genre){
          //    $scenes = $scenes->where('genre','like',$genre.'%');
          //}
      }
      $scenes=$scenes->orderBy('updated_at','desc')
                    ->paginate(24);
      $data['scenes'] = $scenes;
      /*
      $sceneids=[];
      foreach($scenes as $scene){
          $sceneids[] = $scene->scene_id;
      }
      */
      $sceneids = $scenes->lists('scene_id');
      //if(Photo::where('scene_id',$scene->scene_id)->whereNotNull('data')->exists()){
      $data['photos'] = Photo::whereIn('scene_id',$sceneids)
                              ->whereNotNull('path')
                              ->get();
      //}
      foreach($scenes as $key => $scene){
          $data['user'][]=User::find(Mylogdetailtitle::where('title_id',$scene->title_id)->first()->user_id);
          $comments = \DB::table('comments')->where('scene_id',$scene->scene_id)
                                            ->orderBy('created_at','asc')
                                            ->get();
          $data['comments'][] = $comments;
          $userids = \DB::table('mylog_user')->where('scene_id',$scene->scene_id)->lists('user_id');
          $data['favuser'][] = User::whereIn('id',$userids)->get();
          if(Photo::where('scene_id',$scene->scene_id)->whereNotNull('path')->exists()){
          $thumbs = Photo::where('scene_id',$scene->scene_id)
                                  ->whereNotNull('path')
                                  ->get();
          $data['thumb'][]=$thumbs->random();
          }
          $data['titles'][]=Mylogdetailtitle::where('title_id',$scene->title_id)
                                            ->first();
      }
      return view('bodys.show_items',$data);
    }

    public function showGuides(){
      $today = Carbon::today();
      $chdate = $today->year . '-' . str_pad($today->month, 2, 0, STR_PAD_LEFT) . '-' . str_pad($today->day, 2, 0, STR_PAD_LEFT);
      $data['recruitments']=Guestguide::where('type','guide')
                                      ->where('user_id','<>',\Auth::user()->id)
                                      ->where(function($query)use($chdate){
                                          $query->where('limitdate','>',$chdate);
                                      })
                                      ->orderBy('created_at','desc')
                                      ->paginate(30);
      foreach($data['recruitments'] as $key => $recruitment){
          $data['recruituser'][$key] = User::find($recruitment->user_id);
      }
      return view('bodys.show_guides',$data);
    }

    public function searchGuides(Request $request){
      $request->flash();
      $keywordNotExists = $request->keywords=="";
      $termNotWildCard = $request->year1=="0000"&&$request->month1=="00"&&$request->day1=="00";
      $termNotBetween = $request->year2=="0000"&&$request->month2=="01"&&($request->day2=="01" xor $request->day2=="00")&&$request->year3=="9999"&&$request->month3=="12"&&($request->day3=="31" xor $request->day3=="00");
      $areaNot = $request->lat=="0"&&$request->lng=="0"&&$request->radius=="0";
      $personalNot = !isset($request->sex)&&$request->age==0;
      if($keywordNotExists && $termNotWildCard && $termNotBetween && $areaNot && $personalNot){
        return redirect('/guides');
      }
      $today = Carbon::today();
      $chdate = $today->year . '-' . str_pad($today->month, 2, 0, STR_PAD_LEFT) . '-' . str_pad($today->day, 2, 0, STR_PAD_LEFT);
      $recruitments=Guestguide::where('type','guide')
                          ->where('user_id','<>',\Auth::user()->id)
                          ->where(function($query)use($chdate){
                              $query->where('limitdate','>',$chdate);
                          });
      if($request->keywords!=""){
          $keywords = explode(' ',$request->keywords);
          foreach($keywords as $keyword){
            $recruitments = $recruitments->where('contents','like','%'.$keyword.'%');
          }
      }
      if($request->year1!='0000'){
          $recruitments=$recruitments->whereYear('limitdate','=',$request->year1);
      }
      if($request->month1!='00'){
          $recruitments=$recruitments->whereMonth('limitdate','=',$request->month1);
      }
      if($request->day1!='00'){
          $recruitments=$recruitments->whereDay('limitdate','=',$request->day1);
      }
      if($request->day3!='00'&&$request->month3!='00'&&$request->year3!='0000'&&$request->year2!='0000'&&$request->month2!='00'&&$request->day2!='00'){
          $startday = $request->year2.'-'.$request->month2.'-'.$request->day2;
          $lastday = $request->year3.'-'.$request->month3.'-'.$request->day3;
          $recruitments=$recruitments->whereBetween('limitdate',[$startday,$lastday]);
      }elseif($request->year2!='0000'&&$request->month2!='00'&&$request->day2!='00'){
          $startday = $request->year2.'-'.$request->month2.'-'.$request->day2;
          $recruitments=$recruitments->where('limitdate','>=',$startday);
      }elseif($request->day3!='00'&&$request->month3!='00'&&$request->year3!='0000'){
          $lastday = $request->year3.'-'.$request->month3.'-'.$request->day3;
          $recruitments=$recruitments->where('limitdate','<=',$lastday);
      }
      if(!$areaNot){
          $recruitments = $recruitments
->whereRaw('6371000*acos(cos(radians(?))*cos(radians(lat))*cos(radians(lng)-radians(?))+sin(radians(?))*sin(radians(lat)))<?',[$request->lat,$request->lng,$request->lat,$request->radius]);
      }
      if($request->sex!=""){
          $ids = User::where('sex',$request->sex)->whereNotNull('sex')->lists('id');
          $recruitments=$recruitments->whereIn('user_id',$ids);
      }
      if($request->age!=0){
          $age = $request->age;
          $sup = Carbon::today()->subYear($request->age)->format('Y-m-d');
          $inf = Carbon::today()->subYear($request->age+1)->format('Y-m-d');
          if($request->agetype=='e'){
              $userids = User::where('birthday','<=',$sup)->where('birthday','>',$inf)->lists('id');
          }elseif($request->agetype=='i'){
              $userids = User::where('birthday','<=',$sup)->lists('id');
          }else{
              $userids = User::where('birthday','>',$inf)->lists('id');
          }
          $recruitments=$recruitments->whereIn('user_id',$userids);
      }
      $recruitments = $recruitments->orderBy('created_at','desc')
                       ->paginate(30);
      $data['recruitments']=$recruitments;
      return view('bodys.show_guides',$data);
    }

    public function showTravelers(){
      $today = Carbon::today();
      $chdate = $today->year . '-' . str_pad($today->month, 2, 0, STR_PAD_LEFT) . '-' . $today->day;
      $data['recruitments']=Guestguide::where('type','guest')
                          ->where('user_id','<>',\Auth::user()->id)
                          ->where(function($query)use($chdate){
                              $query->where('limitdate','>',$chdate);
                          })
                          ->orderBy('created_at','desc')
                          ->paginate(30);
      foreach($data['recruitments'] as $key => $recruitment){
          $data['recruituser'][$key] = User::find($recruitment->user_id);
      }
      return view('bodys.show_travelers',$data);
    }

    public function searchTravelers(Request $request){
      $request->flash();
      $keywordNotExists = $request->keywords=="";
      $termNotWildCard = $request->year1=="0000"&&$request->month1=="00"&&$request->day1=="00";
      $termNotBetween = $request->year2=="0000"&&$request->month2=="01"&&($request->day2=="01" xor $request->day2=="00")&&$request->year3=="9999"&&$request->month3=="12"&&($request->day3=="31" xor $request->day3=="00");
      $areaNot = $request->lat=="0"&&$request->lng=="0"&&$request->radius=="0";
      $personalNot = !isset($request->sex)&&$request->age==0;
      if($keywordNotExists && $termNotWildCard && $termNotBetween && $areaNot && $personalNot){
        return redirect('/guests');
      }
      $today = Carbon::today();
      $chdate = $today->year . '-' . str_pad($today->month, 2, 0, STR_PAD_LEFT) . '-' . str_pad($today->day, 2, 0, STR_PAD_LEFT);
      $recruitments=Guestguide::where('type','guest')
                          ->where('user_id','<>',\Auth::user()->id)
                          ->where(function($query)use($chdate){
                              $query->where('limitdate','>',$chdate);
                          });
      if($request->keywords!=""){
          $keywords = explode(' ',$request->keywords);
          foreach($keywords as $keyword){
            $recruitments = $recruitments->where('contents','like','%'.$keyword.'%');
          }
      }
      if($request->year1!='0000'){
          $recruitments=$recruitments->whereYear('limitdate','=',$request->year1);
      }
      if($request->month1!='00'){
          $recruitments=$recruitments->whereMonth('limitdate','=',$request->month1);
      }
      if($request->day1!='00'){
          $recruitments=$recruitments->whereDay('limitdate','=',$request->day1);
      }
      if($request->day3!='00'&&$request->month3!='00'&&$request->year3!='0000'&&$request->year2!='0000'&&$request->month2!='00'&&$request->day2!='00'){
          $startday = $request->year2.'-'.$request->month2.'-'.$request->day2;
          $lastday = $request->year3.'-'.$request->month3.'-'.$request->day3;
          $recruitments=$recruitments->whereBetween('limitdate',[$startday,$lastday]);
      }elseif($request->year2!='0000'&&$request->month2!='00'&&$request->day2!='00'){
          $startday = $request->year2.'-'.$request->month2.'-'.$request->day2;
          $recruitments=$recruitments->where('limitdate','>=',$startday);
      }elseif($request->day3!='00'&&$request->month3!='00'&&$request->year3!='0000'){
          $lastday = $request->year3.'-'.$request->month3.'-'.$request->day3;
          $recruitments=$recruitments->where('limitdate','<=',$lastday);
      }
      if(!$areaNot){
          $recruitments = $recruitments
->whereRaw('6371000*acos(cos(radians(?))*cos(radians(lat))*cos(radians(lng)-radians(?))+sin(radians(?))*sin(radians(lat)))<?',[$request->lat,$request->lng,$request->lat,$request->radius]);
      }
      if($request->sex!=""){
          $ids = User::where('sex',$request->sex)->whereNotNull('sex')->lists('id');
          $recruitments=$recruitments->whereIn('user_id',$ids);
      }
      if($request->age!=0){
          $age = $request->age;
          $sup = Carbon::today()->subYear($request->age)->format('Y-m-d');
          $inf = Carbon::today()->subYear($request->age+1)->format('Y-m-d');
          if($request->agetype=='e'){
              $userids = User::where('birthday','<=',$sup)->where('birthday','>',$inf)->lists('id');
          }elseif($request->agetype=='i'){
              $userids = User::where('birthday','<=',$sup)->lists('id');
          }else{
              $userids = User::where('birthday','>',$inf)->lists('id');
          }
          $recruitments=$recruitments->whereIn('user_id',$userids);
      }
      $recruitments = $recruitments->orderBy('created_at','desc')
                       ->paginate(30);
      $data['recruitments']=$recruitments;
      return view('bodys.show_travelers',$data);
    }
    
    public function showItems(){
      $scenes = Mylogdetailscene::where(function($query){
                if(\Auth::check()){
                    if(\DB::table('follows')->where('follow_id',\Auth::user()->id)->exists()){
                        $userids=\DB::table('follows')->where('follow_id',\Auth::user()->id)->lists('user_id');
                        $titleids = Mylogdetailtitle::whereIn('user_id',$userids)->lists('title_id');
                        $query->whereIn('title_id',$titleids)
                              ->where('publish','<>','private')
                              ->orWhere('scene_id','like',\Auth::user()->id.'-%-%');
                    }else{
                        $query->where('publish','public')
                              ->orWhere('scene_id','like',\Auth::user()->id.'-%-%');
                    }
                }else{
                    $query->where('publish','public');
                }
            })
            ->orderBy('updated_at','desc')
            ->paginate(24);
      $data['scenes'] = $scenes;
      $sceneids = $scenes->lists('scene_id');
      //if(Photo::whereIn('scene_id',$sceneids)->whereNotNull('data')->exists()){

      $data['photos'] = Photo::whereNotNull('path')
                                ->whereIn('scene_id',$sceneids)
                                ->get();
      //}
      foreach($scenes as $key => $scene){
          $data['user'][]=User::find(Mylogdetailtitle::where('title_id',$scene->title_id)->first()->user_id);
          $comments = \DB::table('comments')->where('scene_id',$scene->scene_id)
                                            ->orderBy('created_at','asc')
                                            ->get();
          $data['comments'][] = $comments;
          $userids = \DB::table('mylog_user')->where('scene_id',$scene->scene_id)->lists('user_id');
          $data['favuser'][] = User::whereIn('id',$userids)->get();
          if(Photo::where('scene_id',$scene->scene_id)->whereNotNull('path')->exists()){
          $thumbs = Photo::where('scene_id',$scene->scene_id)
                                  ->whereNotNull('path')
                                  ->get();
          $data['thumb'][]=$thumbs->random();
          }
          $data['titles'][]=Mylogdetailtitle::where('title_id',$scene->title_id)
                                            ->first();
      }
      return view('bodys.show_items',$data);
    }

    public function showUserProfile($id){
      $user = User::find($id);
      $data['user']=$user;

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
      $user = User::find($id);
      $data['user']=$user;
      if(\Auth::user()->id==$id){
          $messages = \DB::table('messages')
                            ->where('user_id',$id)
                            ->orWhere('send_id',$id)
                            ->orderBy('created_at')
                            ->get();
          $sortIds=[];
          foreach($messages as $message){
              if($message->user_id != $id){
                  $userid = $message->user_id;
              }else{
                  $userid = $message->send_id;
              }
              if(!in_array($userid,$sortIds)){
                  $sortIds[]=$userid;
              }
          }
          if(isset($sortIds[0])){
              foreach($sortIds as $sortId){
                  $data['messageUsers'][]=User::find($sortId);
                  $data['sentmessages'][]=$user->getMessages($sortId)
                                               ->where('user_id',$sortId)
                                               ->orderBy('created_at','desc')->get();
                  $temppp=$user->getMessages($sortId)->orderBy('created_at','desc')->get();
                  $data['messages'][]=$temppp;
              }
          }
      }
      return view('bodys.user_menu.messages',$data);
    }



    public function showUserItems($id){
      $user = User::find($id);
      $titles = $user->title()->where(function($query)use($id){
                          if(\Auth::user()->id != $id){
                              if(\Auth::user()->is_followed($id)){
                                $titleids=$user->scene()
                                               ->where('publish','<>','private')
                                               ->lists('title_id');
                                $query->whereIn('title_id',$titleids);
                              }else{
                                $titleids=$user->scene()
                                               ->where('publish','public')
                                               ->lists('title_id');
                                $query->whereIn('title_id',$titleids);
                              }
                          }else{
                              $query;
                          }
                      })
                      ->orderBy('firstday','desc')
                      ->paginate(10);
      foreach($titles as $title){
          $scenes = Mylogdetailscene::where('title_id',$title->title_id)
                                ->where(function($query)use($id){
                                    if(\Auth::user()->id!=$id){
                                        if(\Auth::user()->is_followed($id)){
                                            $query->where('publish','<>','private');
                                        }else{
                                            $query->where('publish','public');
                                        }
                                    }else{
                                        $query;
                                    }
                                })
                                ->orderBy('theday')
                                ->get();
          $data['scenes'][]=$scenes;
          $sceneids = $scenes->lists('scene_id');
          if(Photo::whereIn('scene_id',$sceneids)->whereNotNull('path')->exists()){
          $thumbs = Photo::whereIn('scene_id',$sceneids)
                            ->whereNotNull('path')
                            ->get();
          $data['thumb'][] = $thumbs->random();
          }
      }
      $data['user']=$user;
      $data['titles']=$titles;
      return view('bodys.user_menu.items',$data);
    }



    public function showUserFavorites($id){
      $user = User::find($id);
      $data['user']=$user;
      $sceneids = $user->favor()->lists('mylog_user.scene_id');
      $scenes = Mylogdetailscene::whereIn('scene_id',$sceneids)
                           ->orderBy('updated_at','desc')
                           ->get();
      foreach($scenes as $key => $scene){
          if(Photo::where('scene_id',$scene->scene_id)->whereNotNull('path')->exists()){
          $thumbs=Photo::where('scene_id',$scene->scene_id)
                                    ->whereNotNull('path')
                                    ->get();
          $data['thumb'][] = $thumbs->random();
          }
          $data['titles'][]=Mylogdetailtitle::where('title_id',$scene->title_id)->first();
      }
      //$followingids = User::find($id)->follow()->lists('follow_id');
      //$followerids = User::find($id)->follower()->lists('user_id');
      $data['following'] = $user->follow()//->whereIn('id',$followingids)
                                    //->whereNotIn('id',$followerids)
                                    ->paginate(10);
      $data['followed'] = $user->follower()//->whereIn('id',$followerids)
                                  //->whereNotIn('id',$followingids)
                                  ->paginate(10);
                                  //->get(['user_id','data','mime']);
      /*
      $data['mutual'] = User::whereIn('id',$followingids)
                                ->whereIn('id',$followerids)
                                ->paginate(10);
      */
      $data['scenes']=$scenes;
      return view('bodys.user_menu.favorites',$data);
    }



    public function showUserMatching($id){
      if(\Auth::user()->id!=$id){
          return redirect()->back();
      }
      $user=User::find($id);
      $data['user']=$user;
      $data['recruitments']=$user->guestguide()->orderBy('created_at','desc')->paginate(15);
      foreach($data['recruitments'] as $recruitment){
          $candidateuserids = Guestguide::find($recruitment->id)->recruited()->lists('user_id');
          $data['candidateusers'][]=User::whereIn('id',$candidateuserids)->get();
          //$data['candidatecnt'][]=Guestguide::find($recruitment->id)->recruited()->count();
      }
      return view('bodys.user_menu.matching',$data);
    }


    public function showTitle($id,$title_id){
      $user = User::find($id);
      $data['title'] = Mylogdetailtitle::where('title_id',$title_id)->first();
      $sceneids = Mylogdetailscene::where('title_id',$title_id)->lists('scene_id');
      $userids = \DB::table('mylog_user')->whereIn('scene_id',$sceneids)->lists('user_id');
      $data['favuser']['title'] = User::whereIn('id',$userids)->get();
      $scenes = Mylogdetailscene::where('title_id',$title_id)
                      ->where(function($query)use($id){
                          if(\Auth::user()->id != $id){
                              if(\Auth::user()->is_followed($id)){
                                $query->where('publish','<>','private');
                              }else{
                                $query->where('publish','public');
                              }
                          }else{
                              $query;
                          }
                      })
                      ->orderBy('theday')
                      ->paginate(5);
      $data['scenes'] = $scenes;
      $forFixSceneIds = Mylogdetailscene::where('title_id',$title_id)->lists('scene_id');
      $forFixSceneIdsArr=[];
      foreach($forFixSceneIds as $forFixSceneId){
          $forFixSceneId = str_replace($title_id.'-', '', $forFixSceneId);
          $forFixSceneIdsArr[]=$forFixSceneId;
      }
      if(!isset($forFixSceneIdsArr[0])){
          $newFixSceneId = 1;
      }else{
          $newFixSceneId = max($forFixSceneIdsArr)+1;
      }
      $data['newsceneid'] = $title_id.'-'.$newFixSceneId;
      
      $sceneids = $scenes->lists('scene_id');
      $data['scoreAve'] = Mylogdetailscene::whereIn('scene_id',$sceneids)
                                          ->avg('score');
      $data['photos'] = Photo::whereIn('scene_id',$sceneids)
                              ->get();
      foreach($scenes as $key => $scene){
          $comments = \DB::table('comments')
                          ->where('scene_id',$scene->scene_id)
                          ->orderBy('created_at','asc')
                          ->get();
          $data['comments'][] = $comments;                          
          $userids = \DB::table('mylog_user')->where('scene_id',$scene->scene_id)->lists('user_id');
          $data['favuser'][] = User::whereIn('id',$userids)->get();
          if(Photo::where('scene_id',$scene->scene_id)->whereNotNull('path')->exists()){
          $thumbs = Photo::where('scene_id',$scene->scene_id)
                                  ->whereNotNull('path')
                                  ->get();
          $data['thumb'][] = $thumbs->random();
          }
      }
      $data['user']=$user;
      $data['thisyear']=Carbon::now()->year;
      return view('bodys.user_menu.show_title',$data);
    }
}
