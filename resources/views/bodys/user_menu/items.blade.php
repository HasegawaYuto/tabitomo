@extends('layouts.app')

@section('content')
@if(!isset($title_id))
    <?php $title_id='1'; ?>
@endif
@if(!isset($scene_id))
    <?php $scene_id='1'; ?>
@endif
@if(!isset($activetab))
    <?php $activetab='1'; ?>
@endif
<div class="container">
<div class="row">
    @include('bodys.user_menu.contents_menu',['user'=>$user])
    <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel panel-heading text-center">
                <i class="fa fa-camera fa-fw" aria-hidden="true"></i>マイログ
            </div>
            @if($user->user_id == Auth::user()->id)
                <ul class="nav nav-tabs nav-justified" id="logtabs">
                    <li class="{{ $activetab == '1' ? 'active' : ''}}"><a href="#tab1-1" data-toggle="tab">一覧</a></li>
                    <li class="{{ $activetab == '2' ? 'active' : ''}}"><a href="#tab1-2" data-toggle="tab">アップロード</a></li>
                </ul>
            @endif
            @if($user->user_id == Auth::user()->id)
                <div class="tab-content">
                    <div class="tab-pane {{ $activetab == '1' ? 'active' : ''}}" id="tab1-1">
            @endif
            <div class="panel-body">
                {!! $titles->render() !!}
                @if(isset($titles[0]))
                <?php $cnt = 0; ?>
                @foreach($titles as $key => $mylog)
                <?php
                    $firstdayarray = explode('-',$mylog->firstday);
                    $lastdayarray = explode('-',$mylog->lastday);
                ?>
                <a href="{{route('show_title',['id'=>$mylog->user_id,'title_id'=>$mylog->title_id])}}">
                <div class="panel panel-primary">
                      <div class="panel-heading">
                        {{$mylog->title}}
                      </div>
                      <div class="panel-body">
                            <div class="titleImageDiv">
                          @if(isset($thumb[$key]->data))
                          <?php
                            $mime = $thumb[$key]->mime;
                            $dataImage = base64_encode($thumb[$key]->data);
                          ?>
                          <img class="img-responsive titleImage" src="data:{{$mime}};base64,{{$dataImage}}"/>
                          @endif
                            </div>
                            <div class="titleContents">
                                <table>
                                    <tr>
                                        <td>{{$firstdayarray[0]}}年</td>
                                        <td></td>
                                        <td>{{$lastdayarray[0]==$firstdayarray[0] ? '':$lastdayarray[0].'年'}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{(int)$firstdayarray[1]}}月{{(int)$firstdayarray[2]}}日</td>
                                        <td>～</td>
                                        <td>{{(int)$lastdayarray[1]}}月{{(int)$lastdayarray[2]}}日</td>
                                    </tr>
                                </table>
                                @if(isset($scenes[0]))
                                <label>シーン</label>
                                <ul class="sceneList">
                                @foreach($scenes[$key] as $scene)
                                    <li>{{$scene->scene!= "" ? $scene->scene : 'No Title' }}</li>
                                @endforeach
                                </ul>
                                @endif
                            </div>
                      </div>
                </div>
                </a>
                @endforeach
                @endif
            </div>
            @if($user->user_id == Auth::user()->id)
          </div>
            <div class="tab-pane {{ $activetab == '2' ? 'active' : ''}}" id="tab1-2">
                <div class="panel panel-body">
                    <div class="col-xs-12">
                        {!! Form::open(['route'=>['create_items',Auth::user()->id],'files'=>'true','id'=>'myLogForm0','class'=>'myLogForm']) !!}
                        {!! csrf_field() !!}
                        {!! Form::hidden('title_id',$title_id) !!}
                        {!! Form::hidden('scene_id',$scene_id) !!}
                        @if($scene_id==1)
                            <div class="form-group">
                                  {!! Form::label('title','タイトル') !!}
                                  {!! Form::text('title',null,['class'=>'form-control','placeholder'=>'例〇〇山への旅20XX春']) !!}
                            </div>
                        @else
                            <div class="form-group">
                                <label>タイトル</label>
                                <p>{{$title or 'No Title'}}</p>
                                {!! Form::hidden('title',$title) !!}
                            </div>
                        @endif
                        <?php
                              $today = Carbon\Carbon::now()->format('Y年m月d日');
                        ?>
                        <label>期間</label>
                        @if($scene_id==1)
                            <div class="form-group form-inline">
                                {!! Form::text('firstday',$today,['class'=>'form-control datepicker','id'=>'firstday0','style'=>'width:40%;']) !!}
                                <label>～</label>
                                {!! Form::text('lastday',$today,['class'=>'form-control datepicker','id'=>'lastday0','style'=>'width:40%;']) !!}
                            </div>
                        @else
                            <div class="form-group form-inline">
                                <p>{{$firstday or 'UnSettingFirstday'}}～{{$lastday or 'UnSettingLastday'}}</p>
                                {!! Form::hidden('firstday', $firstday, ['id' => 'firstday0']) !!}
                                {!! Form::hidden('lastday', $lastday, ['id' => 'lastday0']) !!}
                            </div>
                        @endif
                        <div class="form-group">
                              {!! Form::label('scene','シーン') !!}
                              {!! Form::text('scene',null,['class'=>'form-control','placeholder'=>'例〇〇旅館の夕食']) !!}
                        </div>
                        <div class="form-group">
                              {!! Form::label('theday','日付') !!}
                              <select id="theday0" class="form-control theday" name="theday">
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
                        <div id="imageThumbnailField0" class="col-xs-12 imageThumbnailField">
                        </div>
                        <div class="form-group">
                            <label>スポット</label>
                            <!--div class="row"-->
                            @if(isset($spotNS) && isset($spotEW))
                                {!! Form::hidden('spotNS', $spotNS, ['id' => 'ido0']) !!}
                                {!! Form::hidden('spotEW', $spotEW, ['id' => 'keido0']) !!}
                            @else
                                {!! Form::hidden('spotNS', null, ['id' => 'ido0']) !!}
                                {!! Form::hidden('spotEW', null, ['id' => 'keido0']) !!}
                            @endif
                            @if(isset($mapzoom))
                                {!! Form::hidden('mapzoom', $mapzoom, ['id' => 'mapzoom0']) !!}
                            @else
                                {!! Form::hidden('mapzoom', null, ['id' => 'mapzoom0']) !!}
                            @endif
                              <div id="createPhotoSpotSetArea0" class="col-xs-12 photoSpotSetArea">
                              </div>
                            <!--/div-->
                        </div>
                        <div class="form-group">
                            <label>おすすめ度</label>
                            <div id="createRateField0" class="rateField">
                            </div>
                        </div>
                        <div class="form-group">
                              {!! Form::label('comment','コメント') !!}
                              {!! Form::textarea('comment',null,['class'=>"form-control",'placeholder'=>"ひとこと",'rows'=>'3']) !!}
                        </div>
                              {!! Form::submit('&nbsp;保存&nbsp;',['class'=>'btn btn-info','name'=>'fin','value'=>'Fin']) !!}
                              {!! Form::submit('続ける',['value'=>'Con','class'=>'btn btn-warning','name'=>'con']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        @endif
        </div>
    </div>
</div>
</div>
@endsection
