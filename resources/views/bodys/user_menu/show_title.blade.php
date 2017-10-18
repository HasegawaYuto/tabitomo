@extends('layouts.app')

@section('content')
<div class="container-fluid">
<div class="row">
@include('bodys.user_menu.contents_menu',['user'=>$user])
<div class="col-xs-12 col-sm-9 col-md-9 col-lg-6">
    <div class="panel panel-info">
        <div class="panel-heading titleStringL">
                @if(Auth::user()->id == $user->id)
                  <?php
                        //$today = Carbon\Carbon::now()->format('Y年m月d日');
                        $oldfirstday = new Carbon\Carbon($title->firstday);
                        //$oldlastday = new Carbon\Carbon($title->lastday);
                        $OldFirstday = $oldfirstday->format('Y年m月d日');
                        //$OldLastday = $oldlastday->format('Y年m月d日');
                  ?>
                    <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#fixTitle"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                    &nbsp;
                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#fixScene0"
                      data-scene="New Scene"
                      data-title="{{$title->title}}"
                      data-lat="36"
                      data-lng="136"
                      data-score="0"
                      data-comment="no comment"
                      data-oldtheday="{{$OldFirstday}}"
                      data-sceneid="{{$newsceneid}}"
                      data-titleid="{{$title->title_id}}"
                      data-userid="{{$title->user_id}}"
                      data-publish="public"
                      data-firstday="{{$title->firstday}}"
                      data-lastday="{{$title->lastday}}"
                      data-editstyle="add"><i class="fa fa-plus" aria-hidden="true"></i></button>
                      @include('parts.delete_button',['title'=>$title])
                @else
                    @include('parts.favorite_title_button',['title'=>$title])
                @endif
                {{$title->title}}

@if(Auth::user()->id == $user->id)
<div class="modal fade" id="fixTitle">
  <div class="modal-dialog">
    <div class="modal-content black">
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
              {!! Form::text('firstday',$OldFirstday,['class'=>'form-control datepicker','id'=>'firstdayT']) !!}
              <label>～</label>
              {!! Form::text('lastday',$OldLastday,['class'=>'form-control datepicker','id'=>'lastdayT']) !!}
          </div>
          {!! Form::submit('更新',['class'=>'btn btn-primary btn-xs']) !!}
          {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
@endif


        </div>
        <div class="panel-body">
            <?php
                $firstdayarray = explode('-',$title->firstday);
                $lastdayarray = explode('-',$title->lastday);
            ?>
        <div class="title_detail">
            <div class="col-xs-12 col-sm-5">
                <label>期間</label><br>
                {{$firstdayarray[0]}}年{{(int)$firstdayarray[1]}}月{{(int)$firstdayarray[2]}}日～
                {{$lastdayarray[0]==$firstdayarray[0] ? '':$lastdayarray[0].'年'}}{{(int)$lastdayarray[1]}}月{{(int)$lastdayarray[2]}}日
            </div>
            <div class="col-xs-12 col-sm-4">
                <label>おすすめ</label><br>
                <div id="showRatyAveDiv" class="showRatyAve text-center">
                    {{(int)($scoreAve*2)/2}}
                    @if($scoreAve !="")
                        <input type="hidden" value="{{$scoreAve}}" id="showRatyAve" />
                    @else
                        <input type="hidden" value="0" id="showRatyAve" />
                    @endif
                </div>
            </div>
            <div class="col-xs-12 col-sm-3">
                <label>お気に入り</label>
                <div class="text-center">
	        <span class="badge">{{max($favuser)}}</span>
                </div>
            </div>
        <div class="col-xs-12">
            {!! $scenes->render() !!}
        </div>
        </div>

            @foreach($scenes as $key => $scene)
            <?php
                $thedayarray = explode('-',$scene->theday);
            ?>
                <div class="col-xs-12" style="margin-top:10px;">
                    <div class="panel panel-primary">
                        <div class="panel-heading" style="text-overflow:ellipsis;overflow: hidden;white-space: nowrap;">
                          @if(Auth::user()->id == $user->id)
                            <button type="button" class="btn btn-warning btn-xs"  data-toggle="modal" data-target="#fixScene0" data-scene="{{$scene->scene}}"
                            data-title="{{$scene->title}}"
                            data-lat="{{$scene->lat}}"
                            data-lng="{{$scene->lng}}"
                            data-score="{{$scene->score}}"
                            data-comment="{{$scene->comment}}"
                            data-oldtheday="{{$scene->theday}}"
                            data-sceneid="{{$scene->scene_id}}"
                            data-titleid="{{$scene->title_id}}"
                            data-userid="{{$scene->user_id}}"
                            data-publish="{{$scene->publish}}"
                            data-firstday="{{$scene->firstday}}"
                            data-lastday="{{$scene->lastday}}"
                            data-editstyle="fix"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                            @include('parts.delete_button',['scene'=>$scene])
                          @else
                            @include('parts.favorite_scene_button',['scene'=>$scene])
                          @endif
                            &nbsp;&nbsp;{{$scene->scene =="" ? 'No Title':$scene->scene}}
                        </div>
                        <div class="panel-body">
                            <div class="text-center">
                                <dvi class="col-sm-4 col-xs-12">
                                    @if(isset($thumb[$key]->data))
                                    <?php
                                        $mime = $thumb[$key]->mime;
                                        $dataImage = base64_encode($thumb[$key]->data);
                                    ?>
                                    <a href="#modal_carousel{{$scene->user_id}}-{{$scene->title_id}}-{{$scene->scene_id}}" data-toggle="modal" data-local="#myCarousel{{$scene->user_id}}-{{$scene->title_id}}-{{$scene->scene_id}}">
                                        <div class="ItemImageShow lazyload" data-bg="data:{{$mime}};base64,{{$dataImage}}"></div>
                                    </a>
                                    <p class="text-center"><small>↑クリック</small></p>
                                    @else
                                    <div class="ItemImageShow lazyload" data-bg="{{asset('noimage.png')}}"></div>
                                    <p class="text-center"><small>No Image</small></p>
                                    @endif
                                </div>
                                    <div class="googlemapSpot googlemapSizeL col-lg-4 col-md-4 col-sm-4" id="googlemapSpotID{{$key}}"></div>
                                    <input type="hidden" value="{{$scene->lat}}" id="googlemapLat{{$key}}" />
                                    <input type="hidden" value="{{$scene->lng}}" id="googlemapLng{{$key}}" />
                                <div class="col-xs-12 col-sm-4 col-lg-4">
                                    <label>日付</label>
                                    <div class="text-center">
                                        {{$thedayarray[0]}}年
                                        {{(int)$thedayarray[1]}}月
                                        {{(int)$thedayarray[2]}}日
                                    </div><br>
                                    <label>おすすめ</label>
                                    <div class="showRaty text-center" id="showRatyDiv{{$key}}">
                                        @if($scene->score !="")
                                            <input type="hidden" value="{{$scene->score}}" id="showRaty{{$key}}" />
                                        @else
                                            <input type="hidden" value="0" id="showRaty{{$key}}" />
                                        @endif
                                    </div><br>
                                    <div>
                                        <label>お気に入り</label>
                                        <div class="text-center">
                                            <span class="badge">{{$favuser[$key]}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <label>コメント</label><br>
                                    {{$scene->comment != "" ? $scene->comment: 'No comment'}}
                                </div>
                            <div>
                                <div id="demo{{$key}}" class="collapse col-xs-12">
                                    <label>ユーザーコメント</label>
                                    @if(isset($userComments[$key]))
                                    <ul class="list-group">
                                        @foreach($userComments[$key] as $kkey => $userComment)
                                            <li class="list-group-item">
                                            <a href="{{route('show_user_profile',['id'=>$commentUser[$key][$kkey]->user_id])}}" class="black">
                                            <div style="width:100%;">
                                                @include('parts.avatar',['user'=>$commentUser[$key][$kkey],'class'=>'CommentUserAvatarInTitle'])
                                            {{$commentUser[$key][$kkey]->nickname or '未設定'}}
                                            @if(Auth::check())
                                            @if(Auth::user()->id == $commentUser[$key][$kkey]->id || Auth::user()->id==$scene->user_id)
                                                @include('parts.comment_delete_button',['scene'=>$scene,'commentUser'=>$commentUser[$key][$kkey],'comment'=>$userComment])
                                            @endif
                                            @endif
                                            </div></a>
                                            <div class="commentContent clearfix">
                                            {{$userComment->comment}}
                                            </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    @endif
      {!! Form::open(['route'=>['add_comment','id'=>$scene->user_id,'title_id'=>$scene->title_id,'scene_id'=>$scene->scene_id]]) !!}
                                        <div class="form-group">
                                            {!! Form::textarea('comment',null,['placeholder'=>'コメント','class'=>'form-control','rows'=>'3']) !!}
                                        </div>
                                        {!! Form::submit('書き込み',['class'=>'btn btn-xs btn-warning']) !!}
                                        {!! Form::close() !!}
                                </div>
                                <button type="button" class="btn btn-block" data-toggle="collapse" data-target="#demo{{$key}}"><span class="caret"></span></button>
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

<div id="QRdiv" style="width:50px;height:50px;background-color:red;">
    <a ><img id="QRcord"></a>
</div>

@if(isset($photos))
    @include('parts.modal_scene_edit',['photos'=>$photos])
@endif

@foreach($scenes as $scene)
@include('parts.modal_carousel',['photos'=>$photos,'scene'=>$scene])
@endforeach
@endsection
