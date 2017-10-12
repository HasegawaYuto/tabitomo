<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App;
use App\Mylog,App\User,App\Profile,DB;

class MessageController extends Controller
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

    public function sendMessage(){
        $id = \Input::get('id');
        $message = \Input::get('message');
        $chtimestamp = \Input::get('chtimestamp');
        \Auth::user()->sendMessage($id,$message);
        DB::table('messages')
                      ->where('created_at','>',$chtimestamp)
                      ->where('user_id',$id)
                      ->update(['status'=>'1']); 
        $tempdata = DB::table('messages')
                      ->where('created_at','>',$chtimestamp)
                      ->where(function($query)use($id){
                        $query->where('user_id',$id)->orWhere('send_id',$id);
                      })
                      ->get();
        return response()->json($tempdata,200);
        //return $json=$chtimestamp;
    }
    public function loadMessage(){
        $id = \Input::get('id');
        $chtimestamp = \Input::get('chtimestamp');
        DB::table('messages')
                      ->where('created_at','>',$chtimestamp)
                      ->where('user_id',$id)
                      ->update(['status'=>'1']);
        $tempdata = DB::table('messages')
                      ->where('created_at','>',$chtimestamp)
                      ->where(function($query)use($id){
                        $query->where('user_id',$id)->orWhere('send_id',$id);
                      })
                      ->get();
        return response()->json($tempdata,200);
    }
}
