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
                マイログ
            </div>
            @if($id == Auth::user()->id)
                <ul class="nav nav-tabs nav-justified" id="logtabs">
                    <li class="{{ $activetab == '1' ? 'active' : ''}}"><a href="#tab1-1" data-toggle="tab">一覧</a></li>
                    <li class="{{ $activetab == '2' ? 'active' : ''}}"><a href="#tab1-2" data-toggle="tab">アップロード</a></li>
                </ul>
            @endif
            @if($id == Auth::user()->id)
                <div class="tab-content">
                    <div class="tab-pane {{ $activetab == '1' ? 'active' : ''}}" id="tab1-1">
            @endif
            <div class="panel-body">
                ページネーション
                @for($i=1;$i<10;$i++)
                <div class="panel panel-primary">
                      <div class="panel-heading">
                            {!! Link_to_route('show_title','旅のタイトル',['id'=>$id,'title_id'=>$i],['style'=>'color:white;']) !!}
                      </div>
                      <div class="panel-body">
                            <div class="col-xs-3" style="height:60px;">
                                サムネイル
                            </div>
                            <div class="col-xs-9">
                                <p>〇〇月△△日から〇〇月△△日</p>
                                <p>〇〇地方と寺社</p>
                            </div>
                      </div>
                </div>
                @endfor
            </div>
            @if($id == Auth::user()->id)
          </div>
            <div class="tab-pane {{ $activetab == '2' ? 'active' : ''}}" id="tab1-2">
                <div class="panel panel-body">
                    <div class="col-xs-12">
                        {!! Form::open(['route'=>['create_items',Auth::user()->id],'files'=>'true','id'=>'myLogForm']) !!}
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
                        @if($scene_id==1)
                            <label>期間</label>
                            <div class="form-group form-inline">
                                {!! Form::text('firstday',$today,['class'=>'form-control datepicker','id'=>'firstday','style'=>'width:40%;']) !!}
                                <label>～</label>
                                {!! Form::text('lastday',$today,['class'=>'form-control datepicker','id'=>'lastday','style'=>'width:40%;']) !!}
                            </div>
                        @else
                            <div class="form-group form-inline">
                                <label>期間</label>
                                <p>{{$firstday or 'UnSettingFirstday'}}～{{$lastday or 'UnSettingLastday'}}</p>
                                {!! Form::hidden('firstday', $firstday, ['id' => 'firstday']) !!}
                                {!! Form::hidden('lastday', $lastday, ['id' => 'lastday']) !!}
                            </div>
                        @endif
                        <div class="form-group">
                              {!! Form::label('scene','シーン') !!}
                              {!! Form::text('scene',null,['class'=>'form-control','placeholder'=>'例〇〇旅館の夕食']) !!}
                        </div>
                        <div class="form-group">
                              {!! Form::label('date','日付') !!}
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
                        <div id="imageThumbnailField" class="col-xs-12">
                        </div>
                        <!--div class="form-group"-->
                            <label>スポット</label>
                            <!--div class="row"-->
                            @if(isset($spotNS) && isset($spotEW))
                                {!! Form::hidden('spotNS', $spotNS, ['id' => 'ido']) !!}
                                {!! Form::hidden('spotEW', $spotEW, ['id' => 'keido']) !!}
                            @else
                                {!! Form::hidden('spotNS', null, ['id' => 'ido']) !!}
                                {!! Form::hidden('spotEW', null, ['id' => 'keido']) !!}
                            @endif
                            @if(isset($mapzoom))
                                {!! Form::hidden('mapzoom', $mapzoom, ['id' => 'mapzoom']) !!}
                            @else
                                {!! Form::hidden('mapzoom', null, ['id' => 'mapzoom']) !!}
                            @endif
                              <div id="photoSpotSetArea" class="col-xs-12">
                              </div>
                            <!--/div-->
                        <!--/div-->
                        <div class="form-group">
                            <label>おすすめ度</label>
                            <div id="ratefield">
                            </div>
                        </div>
                        <div class="form-group">
                              {!! Form::label('comment','コメント') !!}
                              {!! Form::textarea('comment',null,['class'=>"form-control",'placeholder'=>"ひとこと",'rows'=>'3']) !!}
                        </div>
                              {!! Form::submit('&nbsp;保存&nbsp;',['class'=>'btn btn-info','name'=>'fin','value'=>'Fin']) !!}
                              {!! Form::submit('続ける',['value'=>'Con','class'=>'btn btn-warning','name'=>'con']) !!}
                        {!! Form::close() !!}
                        <!--div class="col-xs-12" id="photoSpotSetArea">
                            {!! Form::hidden('spotNS', null, ['id' => 'ido']) !!}
                            {!! Form::hidden('spotEW', null, ['id' => 'keido']) !!}
                        </div-->
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
