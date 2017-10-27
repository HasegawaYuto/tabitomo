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
use App\Mylogdetailtitle,App\Mylogdetailscene,App\Photo;
use Illuminate\Support\Facades\Log;
use AWS,Storage;

class ItemPostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function replaceDate($DateString){
      $theday = str_replace(array("月","年","日"),array("-","-",""),$DateString);
      return $theday;
}
     
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
          $titleids = $user->title()->lists('title_id');
          if(!isset($titleids[0])){
              $theTitleId = 1;
          }else{
              $TitleIds=[]; 
              foreach($titleids as $titleid){
                  $TitleIdsArr = explode('-',$titleid);
                  $TitleIds[] = $TitleIdsArr[1];
              }
              $theTitleId = max($TitleIds)+1;
          }
      }else{
          $theTitleId = $request->title_id;
      }
      
      $theSceneId = $id.'-'.$theTitleId.'-'.$request->scene_id;
      if(isset($request->firstday)){
            $firstday = $this->replaceDate($request->firstday);
      }else{
            $firstday='';
      }
      if(isset($request->lastday)){
            $lastday = $this->replaceDate($request->lastday);
      }else{
            $lastday='';
      }
      if($request->scene_id==1){
            Mylogdetailtitle::create([
                'user_id'=>$id,
                'title_id'=>$id.'-'.$theTitleId,
                'title'=>$request->title,
                'firstday'=>$firstday,
                'lastday'=>$lastday,
            ]);
      }
      if(isset($request->theday)){
            $theday = $this->replaceDate($request->theday);
      }else{
            $theday = '';
      }
      if(isset($_POST['genre'][0])){
            $genre=implode("-", $_POST['genre']);
      }else{
            $genre='';
      }
      Mylogdetailscene::create([
           'scene_id' => $theSceneId,
           'title_id' => $id.'-'.$theTitleId,
           'scene'=>$request->scene,
           'theday'=>$theday,
           'publish' => $request->publish,
           'lat' => $request->spotNS,
           'lng' => $request->spotEW,
           'score' => $request->score,
           'comment' => $request->comment,
           'genre'=>$genre
          ]);
      if(\Input::hasFile('image')){
        $files = \Input::file('image');
        $typearray = [
          'gif' => 'image/gif',
          'jpg' => 'image/jpeg',
          'png' => 'image/png'];
        for($i=0;$i<count($_FILES['image']['name']);$i++){
          if (!isset($_FILES['image']['error'][$i]) || !is_int($_FILES['image']['error'][$i])) {
              return false;
          }else{
            if(array_search(mime_content_type($_FILES['image']['tmp_name'][$i]),$typearray)){
                $file = $files[$i];//\Input::file('image');
                $folder = '/tmp/';
                $filename = 'upload' . $theSceneId . '-' . $i . '.' . $file->getClientOriginalExtension();
                $path = $folder.$filename;
                $image = \Image::make($file->getRealPath())->resize(900, null, function ($constraint) {
                      $constraint->aspectRatio();
                    })->orientate()->save($path);
                $s3 = AWS::get('s3');
                    $result = $s3->putObject(array(
                        'Bucket'     => 'bucket-for-tabitomo',
                        'Key'        => $id.'/'.$filename,
                        'SourceFile' => $path
                ));
                Photo::create([
                        'scene_id' => $theSceneId,
                        'photo_id' => $i,
                        'path' => 'https://s3-ap-northeast-1.amazonaws.com/bucket-for-tabitomo/'.$id.'/'.$filename
                    ]);
                if(isset($filename)){
                  if (\File::exists($filename)) {
                        \File::delete($filename);
                    }
                }
            }
          }
        }
      }
      if(\Input::get('fin')){
          return redirect('user/'. $id .'/mylog');
      }elseif(\Input::get('con')){
          $data['user']=$user;
          $data['titleStr'] = $request->title;
          $data['activetab'] = '2';
          $data['scene_id'] = $request->scene_id+1;
          $data['spotNS'] = $request->spotNS;
          $data['spotEW'] = $request->spotEW;
          $data['firstday'] = $request->firstday;
          $data['lastday'] = $request->lastday;
          $data['mapzoom'] = $request->mapzoom;
          $data['title_id'] = $theTitleId;
          $titles = $user->title()->orderBy('created_at')->get();
          $data['titles'] = $titles;
          foreach($titles as $title){
              $data['scenes'][]=Mylogdetailscene::where('title_id',$title->title_id)->get();
              $sceneids=Mylogdetailscene::where('title_id',$title->title_id)->lists('scene_id');
              $data['thumb'][]=Photo::whereIn('scene_id',$sceneids)
                                    ->whereNotNull('path')
                                    //->orderByRaw("RAND()")
                                    ->first();
          }
          return view('bodys.user_menu.items',$data);
      }
    }


    public function editTitle(Request $request ,$id,$title_id){
        $user = User::find($id);
        $changes = Mylogdetailtitle::where('title_id',$title_id)->get();
        foreach($changes as $change){
              $change->update([ 'title'=>$request->NewTitle,
                                'firstday'=>$this->replaceDate($request->firstday),
                                'lastday'=>$this->replaceDate($request->lastday)
                              ]);
        }
        return redirect()->back();
    }



    public function editScene(Request $request ,$id,$title_id,$scene_id){
        if($request->editstyle=='fix'){
            $user = User::find($id);
            $change = Mylogdetailscene::where('scene_id',$scene_id)->first();
            if(isset($_POST['genre'][0])){
                    $valgenre=implode("-", $_POST['genre']);
            }else{
                $valgenre = "";
            }
            if(isset($_POST['deletePhotoNo'])){
                $postDelNos = $_POST['deletePhotoNo'];
                if(isset($postDelNos)){
                    foreach($postDelNos as $no => $postDelNo){
                        $bool = $postDelNo;
                        if($bool == 'true'){
                            $thephoto = Photo::find($no);
                            $filename = explode('upload',$thephoto->path);
                            $thephotoextension = explode('.',$filename[1]);
                            $s3 = AWS::get('s3');
                            $result = $s3->deleteObject(array(
                                'Bucket'     => 'bucket-for-tabitomo',
                                'Key'        => $id.'/upload'.$scene_id.'-'.$thephoto->photo_id.'.'.$thephotoextension[1],
                            ));
                            $thephoto->delete();
                        }
                    }
                }
            }
            if(isset($request->score)){
                  $score = $request->score;
            }else{
                  $score = 0;
            }
            $change->update([ 'scene'=>$request->scene,
                                'theday'=>$this->replaceDate($request->theday),
                                'publish'=>$request->publish,
                                'lat'=>$request->spotNS,
                                'lng'=>$request->spotEW,
                                'score'=>$score,
                                'genre'=>$valgenre,
                                'comment'=>$request->comment
                              ]);
        }else{
            if(isset($request->spotNS)&&isset($request->spotEW)){
                  $lat = $request->spotNS;
                  $lng = $request->spotEW;
            }else{
                  $lat = '';
                  $lng = '';
            }
            if(isset($request->score)){
                  $score = $request->score;
            }else{
                  $score = 0;
            }
            if(isset($request->comment)){
                  $comment = $request->comment;
            }else{
                  $comment = '';
            }
            if(isset($_POST['genre'][0])){
                $genre=implode("-", $_POST['genre']);
            }else{
                $genre='';
            }
            Mylogdetailscene::create([
                'title_id' => $title_id,
                'scene_id' => $scene_id,
                'scene' => $request->scene,
                'theday' => $this->replaceDate($request->theday),
                'publish' => $request->publish,
                'lat' => $lat,
                'lng' => $lng,
                'score' => $score,
                'comment' => $comment,
                'genre' => $genre
            ]);
        }
        if(\Input::hasFile('image')){
          $files = \Input::file('image');
          $typearray = [
            'gif' => 'image/gif',
            'jpg' => 'image/jpeg',
            'png' => 'image/png'];
          if($request->editstyle=='fix'){
              $starti = Photo::where('scene_id',$scene_id)->max('photo_id')+1;
          }else{
              $starti = 0;
          }
          for($i=0;$i<count($_FILES['image']['name']);$i++){
            $ii = $i + $starti;
            if (!isset($_FILES['image']['error'][$i]) || !is_int($_FILES['image']['error'][$i])) {
                return false;
            }else{
              if(array_search(mime_content_type($_FILES['image']['tmp_name'][$i]),$typearray)){
                  $file = $files[$i];//\Input::file('image');
                  $folder = '/tmp/';
                $filename = 'upload' . $scene_id . '-' . $ii . '.' . $file->getClientOriginalExtension();
                $path = $folder.$filename;
                $image = \Image::make($file->getRealPath())->resize(900, null, function ($constraint) {
                      $constraint->aspectRatio();
                    })->orientate()->save($path);
                $s3 = AWS::get('s3');
                    $result = $s3->putObject(array(
                        'Bucket'     => 'bucket-for-tabitomo',
                        'Key'        => $id.'/'.$filename,
                        'SourceFile' => $path
                ));
                Photo::create([
                        'scene_id' => $scene_id,
                        'photo_id' => $ii,
                        'path' => 'https://s3-ap-northeast-1.amazonaws.com/bucket-for-tabitomo/'.$id.'/'.$filename
                    ]);
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


    public function deleteTitle($title_id){
        if(Photo::whereIn('scene_id','like',$title_id.'-%')->exists()){
            $thephotos = whereIn('scene_id','like',$title_id.'-%')->get();
            foreach($thephotos as $thephoto){
                $filename = explode('upload',$thephoto->path);
                $thephotoextension = explode('.',$filename[1]);
                $userid = explode('-',$thephotoextension[0]);
                $s3 = AWS::get('s3');
                    $result = $s3->deleteObject(array(
                        'Bucket'     => 'bucket-for-tabitomo',
                        'Key'        => $userid[0].'/upload'.$photo->scene_id.'-'.$thephoto->photo_id.'.'.$thephotoextension[1],
                    ));
                $thephoto->delete();
            }
        }
        if(Milogdetailscene::whereIn('title_id',$title_id)->exists()){
            Milogdetailscene::whereIn('title_id',$title_id)->delete();
        }
        Mylogdetailtitle::where('title_id',$title_id)->delete();
        return redirect('user/'.\Auth::user()->id.'/mylog');
    }
    public function deleteScene($scene_id){
        if(Photo::where('scene_id',$scene_id)->exists()){
            $thephotos = Photo::where('scene_id',$scene_id)->get();
            foreach($thephotos as $thephoto){
                $filename = explode('upload',$thephoto->path);
                $thephotoextension = explode('.',$filename[1]);
                $userid = explode('-',$thephotoextension[0]);
                $s3 = AWS::get('s3');
                    $result = $s3->deleteObject(array(
                        'Bucket'     => 'bucket-for-tabitomo',
                        'Key'        => $userid[0].'/upload'.$photo->scene_id.'-'.$thephoto->photo_id.'.'.$thephotoextension[1],
                    ));
                $thephoto->delete();
            }
        }
        Mylogdetailscene::where('scene_id',$scene_id)->delete();
        return redirect()->back();
    }
    public function favoriteScene($scene_id){
        \Auth::user()->favoringScene($scene_id);
        return redirect()->back();
    }
    public function unfavoriteScene($scene_id){
        \Auth::user()->unfavoringScene($scene_id);
        return redirect()->back();
    }
    public function favoriteTitle($title_id){
        \Auth::user()->favoringTitle($title_id);
        return redirect()->back();
    }
    public function unfavoriteTitle($title_id){
        \Auth::user()->unfavoringTitle($title_id);
        return redirect()->back();
    }

    public function postComment(Request $request,$scene_id){
         $this->validate($request, [
            'comment' => 'required|max:255',
          ]);
        \Auth::user()->commentTo($scene_id,$request->comment);
        return redirect()->back();
    }

    public function deleteComment($comment_id){
        \DB::table('comments')->where('id',$comment_id)->delete();
      return redirect()->back();
    }

}
