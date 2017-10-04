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

function replaceDate($DateString){
      $theday = str_replace(array("月","年","日"),array("-","-",""),$DateString);
      return $theday;
}

class ItemPostController extends Controller
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

    public function createItems(Request $request,$id){
      $user = User::find($id);
      if($request->scene_id==1){
          $title_id = $user->mylogs()->max('title_id');
          if(!isset($title_id)){
              $data['title_id'] = 1;
          }else{
              $data['title_id'] = $title_id + 1;
          }
      }else{
          $data['title_id'] = $request->title_id;
      }
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
                $image = \Image::make($file->getRealPath())->resize(900, null, function ($constraint) {
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
      if(\Input::get('fin')){
          return redirect('user/'. $id .'/mylog');
      }elseif(\Input::get('con')){
          //$user = Profile::where('user_id',$id)->first();
          $data['user']=$user->profile;
          //$data['id']=$id;
          $data['title'] = $request->title;
          $data['activetab'] = '2';
          $data['scene_id'] = $request->scene_id+1;
          $data['spotNS'] = $request->spotNS;
          $data['spotEW'] = $request->spotEW;
          $data['firstday'] = $request->firstday;
          $data['lastday'] = $request->lastday;
          $data['mapzoom'] = $request->mapzoom;
          $mylogsByTitle = $user->mylogs()
                          ->where(function($query)use($id){
                              if(\Auth::user()->id != $id){
                                  $query->where('publish','public');
                              }else{
                                  $query;
                              }
                          })
                          ->groupBy('title_id')
                          ->select('title_id','title','firstday','lastday','user_id')
                          ->paginate(10);
          foreach($mylogsByTitle as $key => $mylogByTitle){
              $data['scenes'][$key] = $user->title($mylogByTitle->title_id)
                                    ->where(function($query)use($id){
                                        if(\Auth::user()->id != $id){
                                            $query->where('publish','public');
                                        }else{
                                            $query;
                                        }
                                    })
                                    ->groupBy('scene_id')
                                    ->orderBy('theday')
                                    ->get();
          }
          $data['titles']=$mylogsByTitle;
          return view('bodys.user_menu.items',$data);
      }
    }


    public function editTitle(Request $request ,$id,$title_id){
        function replaceDate($DateString){
              $theday = str_replace(array("月","年","日"),array("-","-",""),$DateString);
              return $theday;
        }
        $user = User::find($id);
        $changes = $user->title($title_id)->get();
        foreach($changes as $change){
              $change->update([ 'title'=>$request->NewTitle,
                                'firstday'=>replaceDate($request->firstday),
                                'lastday'=>replaceDate($request->lastday)
                              ]);
        }
        return redirect()->back();
    }



    public function editScene(Request $request ,$id,$title_id,$scene_id){
        if($request->editstyle=='fix'){
            $user = User::find($id);
            $changes = $user->scene($title_id,$scene_id)->get();
            if(isset($_POST['deletePhotoNo'])){
                $postDelNos = $_POST['deletePhotoNo'];
                if(isset($postDelNos)){
                    foreach($postDelNos as $no => $postDelNo){
                        $bool = $postDelNo;
                        if($bool == 'true'){
                            Mylog::find($no)->delete();
                        }
                    }
                }
            }
            foreach($changes as $change){
                $change->update([ 'scene'=>$request->scene,
                                'theday'=>replaceDate($request->theday),
                                'publish'=>$request->publish,
                                'lat'=>$request->spotNS,
                                'lng'=>$request->spotEW,
                                'score'=>$request->score,
                              ]);
            }
        }
        if(\Input::hasFile('image')){
          $files = \Input::file('image');
          $typearray = [
            'gif' => 'image/gif',
            'jpg' => 'image/jpeg',
            'png' => 'image/png'];
          $photoCnt = $user->scene($title_id,$scene_id)->max('photo_id');
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
                  $image = \Image::make($file->getRealPath())->resize(900, null, function ($constraint) {
                        $constraint->aspectRatio();
                      })->orientate()->save($filename);
                  $mylog = new Mylog;
                  $mylog['data']=file_get_contents($filename);
                  $mylog['mime']=$file->getMimeType();
                  $mylog['user_id'] = $id;
                  $mylog['title_id'] = $title_id;
                  $mylog['scene_id'] = $scene_id;
                  $mylog['photo_id'] = $i+$photoCnt;
                  $mylog['title'] = $request->title;
                  $mylog['scene'] = $request->scene;
                  $mylog['firstday'] = replaceDate($request->firstday);
                  $mylog['lastday'] = replaceDate($request->lastday);
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


    public function deleteTitle($id,$title_id){
        User::find($id)->title($title_id)->delete();
        return redirect('user/'.$id.'/mylog');
    }
    public function deleteScene($id,$title_id,$scene_id){
        User::find($id)->scene($title_id,$scene_id)->delete();
        return redirect()->back();
    }
    public function favoriteScene($id,$title_id,$scene_id){
        $user = User::find($id);
        //$favscene = $user->scene($title_id,$scene_id)->select('id')->first();
        \Auth::user()->favoringScene($id,$title_id,$scene_id);
        return redirect()->back();
    }
    public function unfavoriteScene($id,$title_id,$scene_id){
        $user = User::find($id);
        //$favscene = $user->scene($title_id,$scene_id)->select('id')->first();
        \Auth::user()->unfavoringScene($id,$title_id,$scene_id);
        return redirect()->back();
    }
    public function favoriteTitle($id,$title_id){
        $user = User::find($id);
        //$favscene = $user->scene($title_id,$scene_id)->select('id')->first();
        \Auth::user()->favoringTitle($id,$title_id);
        return redirect()->back();
    }
    public function unfavoriteTitle($id,$title_id){
        $user = User::find($id);
        //$favscene = $user->scene($title_id,$scene_id)->select('id')->first();
        \Auth::user()->unfavoringTitle($id,$title_id);
        return redirect()->back();
    }

    public function postComment(Request $request,$id,$title_id,$scene_id){
         $this->validate($request, [
            'comment' => 'required|max:255',
          ]);
        $user=User::find($id);
        \Auth::user()->commentTo($id,$title_id,$scene_id,$request->comment);
        return redirect()->back();
    }

    public function deleteComment($id,$title_id,$scene_id,$comment_user_id,$comment_id){
      $delSceneCommentIds=User::find($id)->scene($title_id,$scene_id)->lists('mylogs.id');
      foreach($delSceneCommentIds as $delSceneCommentId){
        //User::find($comment_user_id)->comments()->where('comments.scene_id',$delSceneCommentId)->wherePivot('comment_id',$comment_id)->detach();
        \DB::table('comments')->where('user_id',$comment_user_id)
                              ->where('scene_id',$delSceneCommentId)
                              ->where('comment_id',$comment_id)
                              ->delete();
      }
      return redirect()->back();
    }

}
