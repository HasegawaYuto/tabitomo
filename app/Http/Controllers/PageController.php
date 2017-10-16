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
use App\Profile,App\Mylog,App\Guestguide;
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
      $keywordNotExists = $request->keywords=="";
      $termNotWildCard = $request->year1=="0000"&&$request->month1=="00"&&$request->day1=="00";
      $termNotBetween = $request->year2=="0000"&&$request->month2=="01"&&($request->day2=="01" xor $request->day2=="00")&&$request->year3=="9999"&&$request->month3=="12"&&($request->day3=="31" xor $request->day3=="00");
      $areaNot = $request->lat=="0"&&$request->lng=="0"&&$request->radius=="0";
      if($keywordNotExists && $termNotWildCard && $termNotBetween && $areaNot){
        return redirect('/');
      }
      $scenes = Mylog::select('scene','lat','lng','user_id','title_id','title','scene_id','score','comment','theday','publish','firstday','lastday','theday','id')
            ->where(function($query){
                if(\Auth::check()){
                    $query->where('publish','public')->orWhere('user_id',\Auth::user()->id);
                }else{
                    $query->where('publish','public');
                }
            });
      if(isset($request->keywords)){
          $keywords = explode(' ',$request->keywords);
          foreach($keywords as $keyword){
            $scenes = $scenes->where(function($query)use($keyword){
                                    $query->where('title','like','%'.$keyword.'%')
                                          ->orWhere('scene','like','%'.$keyword.'%')
                                          ->orWhere('comment','like','%'.$keyword.'%');
                              });
          }
      }
      if($request->year1!='0000'){
          $scenes=$scenes->where('theday','like',$request->year1.'-__-__');
      }
      if($request->month1!='00'){
          $scenes=$scenes->where('theday','like','____-'.$request->month1.'-__');
      }
      if($request->day1!='00'){
          $scenes=$scenes->where('theday','like','____-__-'.$request->day1);
      }
      $date2 = $request->year2.'-'.$request->month2.'-'.$request->day2;
      $date3 = $request->year3.'-'.$request->month3.'-'.$request->day3;
      $scenes = $scenes->whereBetween('theday',[$date2,$date3]);
      if(!$areaNot){
          $scenes = $scenes
->whereRaw('6371000*acos(cos(radians(?))*cos(radians(lat))*cos(radians(lng)-radians(?))+sin(radians(?))*sin(radians(lat)))<?',[$request->lat,$request->lng,$request->lat,$request->radius]);
      }
      $scenes=$scenes->orderBy('updated_at','desc')
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
          $data['user'][$scene->user_id]=User::find($scene->user_id);
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

    public function showGuides(){
      $today = Carbon::today();
      $chdate = $today->year . '-' . str_pad($today->month, 2, 0, STR_PAD_LEFT) . '-' . $today->day;
      $data['recruitments']=Guestguide::where('type','guide')
                                      ->where('user_id','!=',\Auth::user()->id)
                                      ->where(function($query)use($chdate){
                                          $query->whereNull('limitdate')->orWhere('limitdate','>',$chdate);
                                      })
                                      ->orderBy('created_at','desc')
                                      ->paginate(30);
      foreach($data['recruitments'] as $key => $recruitment){
          $data['recruituser'][$key] = User::find($recruitment->user_id);
      }
      return view('bodys.show_guides',$data);
    }

    public function searchGuides(Request $request){
      $keywordNotExists = $request->keywords=="";
      $termNotWildCard = $request->year1=="0000"&&$request->month1=="00"&&$request->day1=="00";
      $termNotBetween = $request->year2=="0000"&&$request->month2=="01"&&($request->day2=="01" xor $request->day2=="00")&&$request->year3=="9999"&&$request->month3=="12"&&($request->day3=="31" xor $request->day3=="00");
      $areaNot = $request->lat=="0"&&$request->lng=="0"&&$request->radius=="0";
      $personalNot = !isset($request->sex)&&!isset($request->age);
      if($keywordNotExists && $termNotWildCard && $termNotBetween && $areaNot && $personalNot){
        return redirect('/guides');
      }
      $today = Carbon::today();
      $chdate = $today->year . '-' . str_pad($today->month, 2, 0, STR_PAD_LEFT) . '-' . str_pad($today->day, 2, 0, STR_PAD_LEFT);
      $recruitments=Guestguide::where('type','guide')
                          ->where('user_id','!=',\Auth::user()->id)
                          ->where(function($query)use($chdate){
                              $query->whereNull('limitdate')->orWhere('limitdate','>',$chdate);
                          });
      if(isset($request->keywords)){
          $keywords = explode(' ',$request->keywords);
          foreach($keywords as $keyword){
            $recruitments = $recruitments->where('contents','like','%'.$keyword.'%');
          }
      }
      if($request->year1!='0000'){
          $recruitments=$recruitments->where('limitdate','like',$request->year1.'-__-__');
      }
      if($request->month1!='00'){
          $recruitments=$recruitments->where('limitdate','like','____-'.$request->month1.'-__');
      }
      if($request->day1!='00'){
          $recruitments=$recruitments->where('limitdate','like','____-__-'.$request->day1);
      }
      $date2 = $request->year2.'-'.$request->month2.'-'.$request->day2;
      $date3 = $request->year3.'-'.$request->month3.'-'.$request->day3;
      $recruitments = $recruitments->whereBetween('limitdate',[$date2,$date3]);
      if(!$areaNot){
          $recruitments = $recruitments
->whereRaw('6371000*acos(cos(radians(?))*cos(radians(lat))*cos(radians(lng)-radians(?))+sin(radians(?))*sin(radians(lat)))<?',[$request->lat,$request->lng,$request->lat,$request->radius]);
      }
      if(isset($request->sex)){
          $ids = User::where('sex',$request->sex)->whereNotNull('sex')->lists('id');
          $recruitments=$recruitments->whereIn('user_id',$ids);
      }
      if($request->age!=0){
          $ages = $request->age+1;
          $dt = Carbon::today()->subYear($request->age)->format('Y-m-d');
          $dts = Carbon::today()->subYear($ages)->format('Y-m-d');
          if($request->agetype=='='){
              $ids = User::where('birthday','>=',$dts)
                          ->where('birthday','<=',$dt)
                          ->whereNotNull('birthday')->lists('id');
          }elseif($request->agetype=='>='){
              $ids = User::where('birthday',$request->agetype,$dts)->whereNotNull('birthday')->lists('id');
          }else{
              $ids = User::where('birthday',$request->agetype,$dt)->whereNotNull('birthday')->lists('id');
          }
          $recruitments=$recruitments->whereIn('user_id',$ids);
      }
      $recruitments = $recruitments->orderBy('created_at','desc')
                       ->paginate(30);
      $data['recruitments']=$recruitments;
      foreach($data['recruitments'] as $key => $recruitment){
          $data['recruituser'][$key] = User::find($recruitment->user_id);
      }
      return view('bodys.show_guides',$data);
    }

    public function showTravelers(){
      $today = Carbon::today();
      $chdate = $today->year . '-' . str_pad($today->month, 2, 0, STR_PAD_LEFT) . '-' . $today->day;
      $data['recruitments']=Guestguide::where('type','guest')
                          ->where('user_id','!=',\Auth::user()->id)
                          ->where(function($query)use($chdate){
                              $query->whereNull('limitdate')->orWhere('limitdate','>',$chdate);
                          })
                          ->orderBy('created_at','desc')
                          ->paginate(30);
      foreach($data['recruitments'] as $key => $recruitment){
          $data['recruituser'][$key] = User::find($recruitment->user_id);
      }
      return view('bodys.show_travelers',$data);
    }

    public function searchTravelers(Request $request){
      $keywordNotExists = $request->keywords=="";
      $termNotWildCard = $request->year1=="0000"&&$request->month1=="00"&&$request->day1=="00";
      $termNotBetween = $request->year2=="0000"&&$request->month2=="01"&&($request->day2=="01" xor $request->day2=="00")&&$request->year3=="9999"&&$request->month3=="12"&&($request->day3=="31" xor $request->day3=="00");
      $areaNot = $request->lat=="0"&&$request->lng=="0"&&$request->radius=="0";
      $personalNot = !isset($request->sex)&&!isset($request->age);
      if($keywordNotExists && $termNotWildCard && $termNotBetween && $areaNot && $personalNot){
        return redirect('/guests');
      }
      $today = Carbon::today();
      $chdate = $today->year . '-' . str_pad($today->month, 2, 0, STR_PAD_LEFT) . '-' . str_pad($today->day, 2, 0, STR_PAD_LEFT);
      $recruitments=Guestguide::where('type','guest')
                          ->where('user_id','!=',\Auth::user()->id)
                          ->where(function($query)use($chdate){
                              $query->whereNull('limitdate')->orWhere('limitdate','>',$chdate);
                          });
      if(isset($request->keywords)){
          $keywords = explode(' ',$request->keywords);
          foreach($keywords as $keyword){
            $recruitments = $recruitments->where('contents','like','%'.$keyword.'%');
          }
      }
      if($request->year1!='0000'){
          $recruitments=$recruitments->where('limitdate','like',$request->year1.'-__-__');
      }
      if($request->month1!='00'){
          $recruitments=$recruitments->where('limitdate','like','____-'.$request->month1.'-__');
      }
      if($request->day1!='00'){
          $recruitments=$recruitments->where('limitdate','like','____-__-'.$request->day1);
      }
      $date2 = $request->year2.'-'.$request->month2.'-'.$request->day2;
      $date3 = $request->year3.'-'.$request->month3.'-'.$request->day3;
      $recruitments = $recruitments->whereBetween('limitdate',[$date2,$date3]);
      if(!$areaNot){
          $recruitments = $recruitments
->whereRaw('6371000*acos(cos(radians(?))*cos(radians(lat))*cos(radians(lng)-radians(?))+sin(radians(?))*sin(radians(lat)))<?',[$request->lat,$request->lng,$request->lat,$request->radius]);
      }
      if(isset($request->sex)){
          $ids = User::where('sex',$request->sex)->whereNotNull('sex')->lists('id');
          $recruitments=$recruitments->whereIn('user_id',$ids);
      }
      if($request->age!=0){
          $ages = $request->age+1;
          $dt = Carbon::today()->subYear($request->age)->format('Y-m-d');
          $dts = Carbon::today()->subYear($ages)->format('Y-m-d');
          if($request->agetype=='='){
              $ids = User::where('birthday','>=',$dts)
                          ->where('birthday','<=',$dt)
                          ->whereNotNull('birthday')->lists('id');
          }elseif($request->agetype=='>='){
              $ids = User::where('birthday',$request->agetype,$dts)->whereNotNull('birthday')->lists('id');
          }else{
              $ids = User::where('birthday',$request->agetype,$dt)->whereNotNull('birthday')->lists('id');
          }
          $recruitments=$recruitments->whereIn('user_id',$ids);
      }
      $recruitments = $recruitments->orderBy('created_at','desc')
                       ->paginate(30);
      $data['recruitments']=$recruitments;
      foreach($data['recruitments'] as $key => $recruitment){
          $data['recruituser'][$key] = User::find($recruitment->user_id)->profile;
      }
      return view('bodys.show_travelers',$data);
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
              $data['commentUser'][$key][$kkey] = User::find($userComment->TheUserID);
          }
          $data['favuser'][$key] = Mylog::find($scene->id)->favoredBy()->groupBy('mylog_user.user_id')->count();
          $data['user'][$scene->user_id]=User::find($scene->user_id);
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
                            ->groupBy('user_id','send_id')
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
                  $data['sentmessages'][]=$user->getMessages($sortId)->where('user_id',$sortId)->orderBy('created_at','desc')->get();
                  $temppp=$user->getMessages($sortId)->orderBy('created_at','desc')->get();
                  $data['messages'][]=$temppp;
              }
          }

          //$data['messageUsers']=$sortIds;
      }
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
      $data['user']=$user;
      $data['titles']=$titles;
      return view('bodys.user_menu.items',$data);
    }



    public function showUserFavorites($id){
      $user = User::find($id);
      $data['user']=$user;
      $sceneids = $user->favors()->lists('mylog_user.scene_id');
      $scenes = Mylog::select('title_id','scene_id','user_id','scene','title','id')
                                      ->whereIn('id',$sceneids)
                                      ->groupBy('user_id','title_id','scene_id')
                                      ->orderBy('updated_at','desc')
                                      ->get();
      foreach($scenes as $key => $scene){
          $data['thumb'][$key]=User::find($scene->user_id)->scene($scene->title_id,$scene->scene_id)
                                    ->whereNotNull('data')
                                    ->select('data','mime')
                                    ->orderByRaw("RAND()")
                                    ->first();
      }
      $followingids = User::find($id)->follow()->lists('follow_id');
      $followerids = User::find($id)->follower()->lists('user_id');
      $data['following'] = User::whereIn('id',$followingids)
                                    ->whereNotIn('id',$followerids)
                                    ->paginate(10);
      $data['followed'] = User::whereIn('id',$followerids)
                                  ->whereNotIn('id',$followingids)
                                  ->paginate(10);
                                  //->get(['user_id','data','mime']);
      $data['mutual'] = User::whereIn('id',$followingids)
                                ->whereIn('id',$followerids)
                                ->paginate(10);
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
      foreach($data['recruitments'] as $key => $recruitment){
          $candidateuserids = Guestguide::find($recruitment->id)->recruited()->lists('user_id');
          $data['candidateusers'][$key]=User::whereIn('id',$candidateuserids)->get();
          $data['candidatecnt'][$key]=Guestguide::find($recruitment->id)->recruited()->count();
      }
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
          $data['userComments'][$key] = Mylog::find($scene->id)->commented()->select('comments.user_id AS TheUserID','comments.comment','comments.comment_id')->groupBy('comments.comment_id','comments.comment')->orderBy('comments.created_at','asc')->get();
          foreach($data['userComments'][$key] as $kkey => $userComment){
              $data['commentUser'][$key][$kkey] = User::find($userComment->TheUserID);
          }
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
      $data['user']=$user;
      $data['thisyear']=Carbon::now()->year;
      return view('bodys.user_menu.show_title',$data);
    }
}
