@extends('layouts.app')

@section('content')
<div class="container-fluid">
<div class="row">
@include('bodys.user_menu.contents_menu',['user'=>$user])

<?php
    $spotdatas = explode('->',$plan->point);
?>
<div class="col-xs-12 col-sm-8 col-md-9 col-lg-6">
    <div class="panel panel-info">
        <div class="panel-heading text-center">
            <i class="fa fa-calendar fa-fw" aria-hidden="true"></i>マイプラン
        </div>
        <div class="panel-body">
           {!! Form::open(['route'=>['add_spots','id'=>$user->id,'title_id'=>$plan->title_id],'id'=>'addSpots']) !!}
           <input type="hidden" id="planCSRF" value="{{ csrf_token() }}" name="_token">
           <input type="hidden" id="titleid" value="{{$plan->title_id}}">
          <?php
                function replaceDate($date){
                    $theday = new Carbon\Carbon($date);
                    return $theday->format('Y年m月d日');
                }
          ?>
          <div class="form-group">
              {!! Form::label('title','タイトル') !!}
              {!! Form::text('title',$plan->title,['class'=>'form-control','id'=>'titleStr']) !!}
          </div>
          <?php
            $today = Carbon\Carbon::now()->format('Y年m月d日');
          ?>
          <label>期間</label>
          <div class="form-group form-inline">
          {!! Form::text('firstday',replaceDate($plan->firstday),['class'=>'form-control datepicker','id'=>'firstday0']) !!}
          <label>～</label>
          {!! Form::text('lastday',replaceDate($plan->lastday),['class'=>'form-control datepicker','id'=>'lastday0']) !!}
          </div>
          <div class="form-group">
              {!! Form::label('describe','概要') !!}
              {!! Form::textarea('describe',$plan->describe,['id'=>'planDescribe','rows'=>'5','class'=>'form-control','placeholder'=>'プラン概要、予算、必需品など']) !!}
          </div>
          <div class="col-xs-12">
            <button type="button" class="btn btn-danger btn-block" id="planAddButton">スポットを追加</button>
            <div class="list-group" id="planData">
                @if($spotdatas[0]!=null)
                @foreach($spotdatas as $key => $spotdata)
                <?php
                    $datapart = explode(':',$spotdata);
                ?>
                        <div class="list-group-item form-group list-group-item-warning" id="spotData{{$key}}">
                            <label>スポット{{$key+1}}：<label>
                            <input value="{{$datapart[0]}}" type="text" name="searchWord[]" class="form-control searchBox" id="search{{$key}}">
                        </div>
                        <input value="{{$datapart[1]}},{{$datapart[2]}}" type="hidden" id="latlng{{$key}}">
                @endforeach
                @endif
            </div>
          </div>
          <div id="latlngs"></div>
          <div id="planMap"></div>
          {!! Form::close() !!}
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="saveInfo">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="mdoal-body text-center">
                <div style="vertical-align:middle;height:40px;">
                    保存しました
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
