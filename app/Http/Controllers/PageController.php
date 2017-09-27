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
      $mylogsByTitle = Mylog::where('user_id',$id)
                      ->where('publish','public')
                      ->groupBy('title_id')
                      ->select('title_id','title','firstday','lastday','user_id')//->get();
                      ->paginate(10);
      foreach($mylogsByTitle as $key => $mylogByTitle){
          $data['logtitle'][$key] = Mylog::where('user_id',$id)
                                ->where('publish','public')
                                ->where('title_id',$mylogByTitle->title_id)
                                ->groupBy('scene_id')
                                ->select('scene')->get();
          $thumbIDs = Mylog::where('user_id',$id)
                            //->whereNotNull('mime')
                            ->where('publish','public')
                            ->where('title_id',$mylogByTitle->title_id)
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
                  $data['thumb'][$key] = Mylog::find($arr[$thumbIDrand]);
              }
          }
      }
      $data['user']=$user;
      $data['id']=$id;
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



    public function createItems(Request $request,$id){
      if($request->scene_id==1){
          $title_id = Mylog::where('user_id',$id)->max('title_id');
          if(!isset($title_id)){
              $data['title_id'] = 1;
          }else{
              $data['title_id'] = $title_id + 1;
          }
      }else{
          $data['title_id'] = $request->title_id;
      }
      function replaceDate($DateString){
            $theday = str_replace(array("月","年","日"),array("-","-",""),$DateString);
            return $theday;
      }
      //$mylog = new Mylog;
/////////////////////////////////////////////
      if(\Input::hasFile('image')){
        $files = \Input::file('image');
        $typearray = [
          'gif' => 'image/gif',
          'jpg' => 'image/jpeg',
          'png' => 'image/png'];
        for($i=0;$i<count($_FILES['image']['name']);$i++){
          //$mylog = new Mylog;
          if (!isset($_FILES['image']['error'][$i]) || !is_int($_FILES['image']['error'][$i])) {
              return false;
          }else{
            if(array_search(mime_content_type($_FILES['image']['tmp_name'][$i]),$typearray)){
                $file = $files[$i];//\Input::file('image');
                $filename = public_path() . '/image/upload' . $id . '-' . $request->title_id . '-' . $request->scene_id . '-' . $i . '.' . $file->getClientOriginalExtension();
                $image = \Image::make($file->getRealPath())->resize(300, null, function ($constraint) {
                      $constraint->aspectRatio();
                    })->orientate()->save($filename);
                $mylog = new Mylog;
                $mylog['data']=file_get_contents($filename);
                $mylog['mime']=$file->getMimeType();
                $mylog['user_id'] = $id;
                $mylog['title_id'] = $data['title_id'];
                $mylog['scene_id'] = $request->scene_id;
                $mylog['photo_id'] = $i;
                if(isset($request->title)){
                    $mylog['title'] = $request->title;
                }
                if(isset($request->scene)){
                    $mylog['scene'] = $request->scene;
                }
                if(isset($request->firstday)){
                    $mylog['firstday'] = replaceDate($request->firstday);
                }
                if(isset($request->lastday)){
                    $mylog['lastday'] = replaceDate($request->lastday);
                }
                if(isset($request->theday)){
                    $mylog['theday'] = replaceDate($request->theday);
                }
                if(isset($request->publish)){
                    $mylog['publish'] = $request->publish;
                }
                if(isset($request->spotNS)&&isset($request->spotEW)){
                    $mylog['lat'] = $request->spotNS;
                    $mylog['lng'] = $request->spotEW;
                }
                if(isset($request->score)){
                    $mylog['score'] = $request->score;
                }
                if(isset($request->comment)){
                    $mylog['comment'] = $request->comment;
                }
                $mylog->save();
                if(isset($filename)){
                  if (\File::exists($filename)) {
                        \File::delete($filename);
                    }
                }
            }
          }
        }
      }else{
            $mylog = new Mylog;
            $mylog['user_id'] = $id;
            $mylog['title_id'] = $data['title_id'];
            $mylog['scene_id'] = $request->scene_id;
            if(isset($request->title)){
                $mylog['title'] = $request->title;
            }
            if(isset($request->scene)){
                $mylog['scene'] = $request->scene;
            }
            if(isset($request->firstday)){
                $mylog['firstday'] = replaceDate($request->firstday);
            }
            if(isset($request->lastday)){
                $mylog['lastday'] = replaceDate($request->lastday);
            }
            if(isset($request->theday)){
                $mylog['theday'] = replaceDate($request->theday);
            }
            if(isset($request->publish)){
                $mylog['publish'] = $request->publish;
            }
            if(isset($request->spotNS)&&isset($request->spotEW)){
                $mylog['lat'] = $request->spotNS;
                $mylog['lng'] = $request->spotEW;
            }
            if(isset($request->score)){
                $mylog['score'] = $request->score;
            }
            if(isset($request->comment)){
                $mylog['comment'] = $request->comment;
            }
            $mylog->save();
      }
////////////////////////////////////////////
      if(\Input::get('fin')){
          return redirect('user/'. $id .'/mylog');
      }elseif(\Input::get('con')){
          $user = Profile::where('user_id',$id)->first();
          $data['user']=$user;
          $data['id']=$id;
          $data['title'] = $request->title;
          $data['activetab'] = '2';
          $data['scene_id'] = $request->scene_id+1;
          $data['spotNS'] = $request->spotNS;
          $data['spotEW'] = $request->spotEW;
          $data['firstday'] = $request->firstday;
          $data['lastday'] = $request->lastday;
          $data['mapzoom'] = $request->mapzoom;
          $mylogsByTitle = Mylog::where('user_id',$id)
                          ->where('publish','public')
                          ->groupBy('title_id')
                          ->select('title_id','title','firstday','lastday','user_id')
                          ->paginate(10);
          foreach($mylogsByTitle as $key => $mylogByTitle){
              $data['logtitle'][$key] = Mylog::where('user_id',$id)
                                    ->where('publish','public')
                                    ->where('title_id',$mylogByTitle->title_id)
                                    ->groupBy('scene_id')->get();
          }
          $data['mylogs']=$mylogsByTitle;
          return view('bodys.user_menu.items',$data);
      }
    }


    public function showTitle($id,$title_id){
      $user = Profile::where('user_id',$id)->first();
      $data['title'] = Mylog::where('user_id',$id)
                              ->where('title_id',$title_id)
                              ->where('publish','public')
                              ->select('title','firstday','lastday','title_id')
                              ->first();
      $scenes = Mylog::where('user_id',$id)
                              ->where('title_id',$title_id)
                              ->where('publish','public')
                              ->groupBy('scene_id')
                              ->orderBy('theday')
                              ->select('scene','theday','lat','lng','score','comment','scene_id')
                              ->paginate(5);
      $data['scenes'] = $scenes;
      $data['scoreAve'] = Mylog::where('user_id',$id)
                              ->where('title_id',$title_id)
                              ->where('publish','public')
                              ->orderBy('scene_id')
                              ->avg('score');
      $data['photos'] = Mylog::where('user_id',$id)
                              ->where('title_id',$title_id)
                              ->whereNotNull('data')
                              ->select('mime','data','scene_id','id')
                              ->get();
      foreach($scenes as $key => $scene){
          $thumbIDs = Mylog::where('user_id',$id)
                                  ->where('title_id',$title_id)
                                  ->where('publish','public')
                                  ->where('scene_id',$scene->scene_id)
                                  ->whereNotNull('data')
                                  ->select('id')
                                  ->get();
          if(isset($thumbIDs)){
              foreach($thumbIDs as $thumbID){
                  $arr[] = $thumbID->id;
              }
              if(isset($arr[0])){
                  $thumbIDrand = array_rand($arr);
                  $data['thumb'][$key] = Mylog::find($arr[$thumbIDrand]);
              }
          }
      }
      $data['user']=$user;
      $data['id']=$id;
      //$data['title_id']=$title_id;
      $data['thisyear']=Carbon::now()->year;
      return view('bodys.user_menu.show_title',$data);
    }

    public function editTitle(Request $request ,$id,$title_id){
        function replaceDate($DateString){
              $theday = str_replace(array("月","年","日"),array("-","-",""),$DateString);
              return $theday;
        }
        $changes = Mylog::where('user_id',$id)
                        ->where('title_id',$title_id)
                        ->get();
        foreach($changes as $change){
              $change->update([ 'title'=>$request->NewTitle,
                                'firstday'=>replaceDate($request->firstday),
                                'lastday'=>replaceDate($request->lastday)
                              ]);
        }
        return redirect()->back();
    }

    public function editScene(Request $request ,$id,$title_id,$scene_id){
        function replaceDate($DateString){
              $theday = str_replace(array("月","年","日"),array("-","-",""),$DateString);
              return $theday;
        }
        $changes = Mylog::where('user_id',$id)
                        ->where('title_id',$title_id)
                        ->where('scene_id',$scene_id)
                        ->get();
        foreach($changes as $change){
              $change->update([ 'scene'=>$request->NewScene,
                                'theday'=>replaceDate($request->theday),
                                'publish'=>$request->publish,
                                'lat'=>$request->spotNS,
                                'lng'=>$request->spotEW,
                                'score'=>$request->score,
                              ]);
        }
        if(\Input::hasFile('image')){
          $files = \Input::file('image');
          $typearray = [
            'gif' => 'image/gif',
            'jpg' => 'image/jpeg',
            'png' => 'image/png'];
          $photoCnt = Mylog::where('user_id',$id)
                          ->where('title_id',$title_id)
                          ->where('scene_id',$scene_id)
                          ->whereNotNull('photo_id')
                          ->max('photo_id');
          if(!isset($photoCnt)){
              $photoCnt = 0;
          }else{
              $photoCnt = $photoCnt+1;
          }
          for($i=0;$i<count($_FILES['image']['name']);$i++){
            //$mylog = new Mylog;
            if (!isset($_FILES['image']['error'][$i]) || !is_int($_FILES['image']['error'][$i])) {
                return false;
            }else{
              if(array_search(mime_content_type($_FILES['image']['tmp_name'][$i]),$typearray)){
                  $file = $files[$i];//\Input::file('image');
                  $filename = public_path() . '/image/upload' . $id . '-' . $request->title_id . '-' . $request->scene_id . '-' . $i . '.' . $file->getClientOriginalExtension();
                  $image = \Image::make($file->getRealPath())->resize(300, null, function ($constraint) {
                        $constraint->aspectRatio();
                      })->orientate()->save($filename);
                  $mylog = new Mylog;
                  $mylog['data']=file_get_contents($filename);
                  $mylog['mime']=$file->getMimeType();
                  $mylog['user_id'] = $id;
                  $mylog['title_id'] = $title_id;
                  $mylog['scene_id'] = $scene_id;
                  $mylog['photo_id'] = $i+$photoCnt;
                  //if(isset($request->title)){
                      $mylog['title'] = $request->title;
                  //}
                  //if(isset($request->scene)){
                      $mylog['scene'] = $request->scene;
                  //}
                  //if(isset($request->firstday)){
                      $mylog['firstday'] = replaceDate($request->firstday);
                  //}
                  //if(isset($request->lastday)){
                      $mylog['lastday'] = replaceDate($request->lastday);
                  //}
                  if(isset($request->theday)){
                      $mylog['theday'] = replaceDate($request->theday);
                  }
                  if(isset($request->publish)){
                      $mylog['publish'] = $request->publish;
                  }
                  if(isset($request->spotNS)&&isset($request->spotEW)){
                      $mylog['lat'] = $request->spotNS;
                      $mylog['lng'] = $request->spotEW;
                  }
                  if(isset($request->score)){
                      $mylog['score'] = $request->score;
                  }
                  if(isset($request->comment)){
                      $mylog['comment'] = $request->comment;
                  }
                  $mylog->save();
                  if(isset($filename)){
                    if (\File::exists($filename)) {
                          \File::delete($filename);
                      }
                  }
              }
            }
          }
        }
        return redirect()->back();
    }
}
