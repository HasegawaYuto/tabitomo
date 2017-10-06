<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

function replaceDate($DateString){
      $theday = str_replace(array("月","年","日"),array("-","-",""),$DateString);
      return $theday;
}

class GuestGuideController extends Controller
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

    public function guestPost(Request $request,$id)
    {
      $cnt = $request->cnt;
      for($i=0;$i<=$cnt;$i++){
          if(isset($request->limitdate)){
              $data['limitdate']=replaceDate($request->limitdate);
          }
          if(isset($request->contents)){
              $data['contents']=$request->contents;
          }
          $data['lat']=$request->centerLat[$i];
          $data['lng']=$request->centerLng[$i];
          $data['radius']=$request->circleRadius[$i];
          $data['type']='guest';
          $request->user()->guestguide()->create($data);
      }
        return redirect()->back();
    }
    public function guidePost(Request $request,$id)
    {
        $cnt = $request->cnt;
        for($i=0;$i<=$cnt;$i++){
            if(isset($request->limitdate)){
                $data['limitdate']=replaceDate($request->limitdate);
            }
            if(isset($request->contents)){
                $data['contents']=$request->contents;
            }
            $data['lat']=$request->centerLat[$i];
            $data['lng']=$request->centerLng[$i];
            $data['radius']=$request->circleRadius[$i];
            $data['type']='guide';
            $request->user()->guestguide()->create($data);
        }
        return redirect()->back();
    }
    public function candidateGuide($guide_id){
        return redirect()->back();
    }
}
