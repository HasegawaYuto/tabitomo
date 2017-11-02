<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App;
use App\Plandetail;

class PlanController extends Controller
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
        $data['user'] = User::find($id);
        return view('bodys.user_menu.plans',$data);
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
    
    public function getPrint($id)
    {
        $data['user']= User::find($id);
        $pdf = \PDF::loadView('bodys.pdf_export',$data);
        //return $pdf->download('print.pdf');
        return view('bodys.pdf_export',$data);
    }
    public function createPlan(Request $request,$id){
        $planids=Plandetail::where('user_id',$id)->lists('title_id');
        if(isset($planids[0])){
            $theplanids = [];
            foreach($planids as $planid){
                $ids = explode('-',$planid);
                $theplanids[] = $ids[1];
            }
            $theplanid = max($theplanids)+1;
        }else{
            $theplanid = 0;
        }
        
        Plandetail::create([
            'user_id' => $id,
            'title' => $request->title,
            'title_id' =>$id.'-'.$theplanid,
            'firstday' => $this->replaceDate($request->firstday),
            'lastday' => $this->replaceDate($request->lastday),
            'describe' =>$request->describe
            ]);
        return redirect()->back();
    }
    
    public function addSpots(){
        $titleid = \Input::get('titleid');
        if(\Input::get('keys') && \Input::get('lats') && \Input::get('lngs')){
            $Keys = \Input::get('keys');
            $Lats = \Input::get('lats');
            $Lngs = \Input::get('lngs');
            $spots = [];
            foreach($Keys as $key => $keyword){
                $spots[$key] = $keyword . ':' . $Lats[$key] . ':' . $Lngs[$key];
            }
            $spotdata = implode('->',$spots);
        }else{
            $Keys = [];
            $Lats = [];
            $Lngs = [];
            $spotdata = '';
        }
        $title = \Input::get('title');
        $firstday = \Input::get('firstday');
        $lastday = \Input::get('lastday');
        $describe = \Input::get('describe');
        $thetitle = Plandetail::where('title_id',$titleid)->first();
        $thetitle->update([
                'title' => $title,
                'firstday' => $this->replaceDate($firstday),
                'lastday' => $this->replaceDate($lastday),
                'describe' => $describe,
                'point' => $spotdata,
                'trans' => 'driving'
            ]);
        $saveDone = 'true';
        return $saveDone;
    }
}
