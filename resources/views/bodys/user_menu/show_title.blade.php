@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
@include('bodys.user_menu.contents_menu',['user'=>$user])
<div class="col-md-9">
    <div class="panel panel-info">
        <div class="panel-heading" style="text-overflow:ellipsis;overflow: hidden;white-space: nowrap;">
                {!! Link_to_route('show_user_items','マイログ',['id'=>$id]) !!}
                &nbsp;&nbsp; ≫&nbsp;&nbsp;
                <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#fixTitle">編集</button>
                &nbsp;&nbsp;{{$title->title}}

<div class="modal fade" id="fixTitle">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">タイトルの編集</h4>
      </div>
      <div class="modal-body">
          {!! Form::open(['route'=>['edit_title','id'=>Auth::user()->id,'title_id'=>$title->title_id]]) !!}
          <div class="form-group form-inline">
              {!! Form::label('NewTitle','タイトル：') !!}
              {!! Form::text('NewTitle',$title->title,['class'=>'form-control']) !!}
          </div>
          <div class="form-group form-inline">
              <label>期間：</label>
          <?php
                $today = Carbon\Carbon::now()->format('Y年m月d日');
                $oldfirstday = new Carbon\Carbon($title->firstday);
                $oldlastday = new Carbon\Carbon($title->lastday);
                $OldFirstday = $oldfirstday->format('Y年m月d日');
                $OldLastday = $oldlastday->format('Y年m月d日');
          ?>
              {!! Form::text('firstday',$OldFirstday,['class'=>'form-control datepicker','id'=>'firstday','style'=>'width:40%;']) !!}
              <label>～</label>
              {!! Form::text('lastday',$OldLastday,['class'=>'form-control datepicker','id'=>'lastday','style'=>'width:40%;']) !!}
          </div>
          {!! Form::submit('更新',['class'=>'btn btn-primary btn-xs']) !!}
          {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>



        </div>
        <div class="panel-body">
            <?php
                $firstdayarray = explode('-',$title->firstday);
                $lastdayarray = explode('-',$title->lastday);
            ?>
        <div class="title_detail">
            <div class="col-xs-5">
            <label>期間</label><br>
                {{$firstdayarray[0]}}年{{(int)$firstdayarray[1]}}月{{(int)$firstdayarray[2]}}日
            ～
            {{$lastdayarray[0]==$firstdayarray[0] ? '':$lastdayarray[0].'年'}}{{(int)$lastdayarray[1]}}月{{(int)$lastdayarray[2]}}日
            </div>
            <div class="col-xs-4">
                <label>おすすめ</label><br>
                <div id="showRatyAveDiv" class="showRatyAve">
                {{(int)($scoreAve*2)/2}}
                    @if($scoreAve !="")
                        <input type="hidden" value="{{$scoreAve}}" id="showRatyAve" />
                    @else
                        <input type="hidden" value="0" id="showRatyAve" />
                    @endif
                </div>
            </div>
            <div class="col-xs-3">
                <label>お気に入り</label>〇
            </div>
        </div>
            {!! $scenes->render() !!}
            @foreach($scenes as $key => $scene)
            <?php
                $thedayarray = explode('-',$scene->theday);
            ?>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading" style="text-overflow:ellipsis;overflow: hidden;white-space: nowrap;">
                            <button type="button" class="btn btn-warning btn-xs"  data-toggle="modal" data-target="#fixScene{{$key}}">編集</button>
                            &nbsp;&nbsp;{{$scene->scene =="" ? 'No Title':$scene->scene}}
                        </div>
                        <div class="panel-body">
                            <div class="text-center">
                                <dvi class="col-xs-4">
                                    @if(isset($thumb[$key]->data))
                                    <?php
                                      $mime = $thumb[$key]->mime;
                                      $dataImage = base64_encode($thumb[$key]->data);
                                    ?>
                                    <img class="img-responsive" src="data:{{$mime}};base64,{{$dataImage}}"  style="height:25vh;"/>
                                    @endif
                                </div>
                                <input type="hidden" value="{{$scene->lat}}" id="googlemapLat{{$key}}" />
                                <input type="hidden" value="{{$scene->lng}}" id="googlemapLng{{$key}}" />
                                <div class="googlemapSpot col-xs-4" style="height:25vh;" id="googlemapSpotID{{$key}}">
                                </div>
                                <div class="col-xs-4">
                                <label>日付</label>
                                <div>
                                    {{$thedayarray[0]}}年
                                    {{(int)$thedayarray[1]}}月
                                    {{(int)$thedayarray[2]}}日
                                </div><br>
                                <label>おすすめ</label>
                                <div class="showRaty" id="showRatyDiv{{$key}}">
                                    @if($scene->score !="")
                                        <input type="hidden" value="{{$scene->score}}" id="showRaty{{$key}}" />
                                    @else
                                        <input type="hidden" value="0" id="showRaty{{$key}}" />
                                    @endif
                                </div><br>
                                <div>
                                    <label>お気に入り</label>〇
                                </div>
                                </div>
                                <div class="col-xs-12">
                                  <label>コメント</label><br>
                                    {{$scene->comment != "" ? $scene->comment: 'No comment'}}
                                </div>
                              <div>
                                <div id="demo{{$key}}" class="collapse col-xs-12">
                                    <p>他のユーザのコメント</p>
                                </div>
                                <button type="button" class="btn btn-block" data-toggle="collapse" data-target="#demo{{$key}}"><span class="caret"></span></button>
                            </div>
                        </div>
                    </div>
                </div>

<div class="modal fade" id="fixScene{{$key}}">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">シーンの編集</h4>
      </div>
      <div class="modal-body">
          {!! Form::open(['route'=>['edit_scene','id'=>Auth::user()->id,'title_id'=>$title->title_id,'scene_id'=>$scene->scene_id],'files'=>'true','id'=>'myLogForm{{$key}}','class'=>'myLogForm']) !!}
          {!! csrf_field() !!}
          <div class="form-group form-inline">
              {!! Form::label('NewScene','シーン：') !!}
              {!! Form::text('NewScene',$scene->scene,['class'=>'form-control']) !!}
          </div>
          <div class="form-group form-inline">
          <?php
                $today = Carbon\Carbon::now()->format('Y年m月d日');
                $oldfirstday = new Carbon\Carbon($title->firstday);
                $oldlastday = new Carbon\Carbon($title->lastday);
                $OldFirstday = $oldfirstday->format('Y年m月d日');
                $OldLastday = $oldlastday->format('Y年m月d日');
          ?>
              {!! Form::hidden('firstday',$OldFirstday,['id'=>'firstday{{$key}}']) !!}
              {!! Form::hidden('lastday',$OldLastday,['id'=>'lastday{{$key}}']) !!}
                {!! Form::label('date','日付：') !!}
                <select id="theday" class="form-control" name="theday" style="width:40%;">
                    <option value="{{$today}}">{{$today}}</option>
                </select>
          </div>
          <div class="form-group form-inline">
                {!! Form::label('publish','公開設定') !!}
                {!! Form::radio('publish','public',true) !!}
                <label>公開</label>
                {!! Form::radio('publish','private') !!}
                <label>非公開</label>
          </div>
          <div class="form-group">
                {!! Form::file('image[]',['multiple'=>'multiple','accept'=>'image/*']) !!}
          </div>
          <div id="imageThumbnailField{{$key}}" class="col-xs-12 imageThumbnailField">
          </div>
          <div class="form-group">
              <label>スポット</label>
              <!--div class="row"-->
                  {!! Form::hidden('spotNS', $scene->lat, ['id' => 'ido{{$key}}']) !!}
                  {!! Form::hidden('spotEW', $scene->lng, ['id' => 'keido{{$key}}']) !!}
                  {!! Form::hidden('mapzoom', 6, ['id' => 'mapzoom']) !!}
                <div id="photoSpotSetArea{{$key}}" class="col-xs-12 photoSpotSetArea">
                </div>
              <!--/div-->
          </div>
          <div class="form-group">
              <label>おすすめ度</label>
              <div id="ratefield{{$key}}">
              </div>
          </div>
          <div class="form-group">
                {!! Form::label('comment','コメント') !!}
                {!! Form::textarea('comment',null,['class'=>"form-control",'placeholder'=>"ひとこと",'rows'=>'3']) !!}
          </div>
          {!! Form::submit('更新',['class'=>'btn btn-primary btn-xs']) !!}
          {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>


            @endforeach
        </div>
    </div>
</div>
</div>
</div>
@endsection
