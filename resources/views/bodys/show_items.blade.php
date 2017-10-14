@extends('layouts.app')

@section('content')

@if(isset($scenes[0]))
<div class="container-fluid">
    @include('parts.searchItem')
    <div class="col-xs-12">
        {!! $scenes->render() !!}
    </div>
    @foreach($scenes as $key => $scene)
    <?php
        $thedayarray = explode('-',$scene->theday);
    ?>
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
            <div class="panel panel-primary">
                <div class="panel-heading overCut">
                  @if(Auth::check())
                  @if(Auth::user()->id == $scene->user_id)
                    <button type="button" class="btn btn-warning btn-xs"  data-toggle="modal" data-target="#fixScene0" data-scene="{{$scene->scene}}"
                    data-lat="{{$scene->lat}}"
                    data-lng="{{$scene->lng}}"
                    data-score="{{$scene->score}}"
                    data-comment="{{$scene->comment}}"
                    data-oldtheday="{{$scene->theday}}"
                    data-sceneid="{{$scene->scene_id}}"
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
                  @endif
                    &nbsp;&nbsp;{{$scene->scene =="" ? 'No Title':$scene->scene}}
                </div>
                <div class="panel-body">
                    <a href="{{route('show_title',['id'=>$scene->user_id,'title_id'=>$scene->title_id])}}">
                     <div class="black titleFrom overCut">
                      【FROM】<br>
                    {{$scene->title}}
                  </div></a>
                  <a href="{{route('show_user_profile',['id'=>$scene->user_id])}}">
                  <div class="itemAvatarOuter text-center black overCut">
                    @if(isset($user[$scene->user_id]->data))
                        <?php
                            $mime = $user[$scene->user_id]->mime;
                            $dataImage = base64_encode($user[$scene->user_id]->data);
                        ?>
                        <div class="lazyload itemAvatar img-circle" data-bg="data:{{$mime}};base64,{{$dataImage}}"></div>
                    @else
                        <div class="lazyload itemAvatar img-circle" data-bg="{{asset('noimage.png')}}"></div>
                    @endif
                    {{$user[$scene->user_id]->nickname!=""?$user[$scene->user_id]->nickname:"未設定"}}
                  </div>
                    <div class="col-xs-12">
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
                    <input type="hidden" value="{{$scene->lat}}" id="googlemapLat{{$key}}" />
                    <input type="hidden" value="{{$scene->lng}}" id="googlemapLng{{$key}}" />
                    <div class="googlemapSpot googlemapSizeM" id="googlemapSpotID{{$key}}"></div>
                    <div id="demo{{$key}}" class="collapse col-xs-12">
                    <div class="col-xs-12">
                        <label>日付</label>
                        <div>
                            {{$thedayarray[0]}}年{{(int)$thedayarray[1]}}月{{(int)$thedayarray[2]}}日
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
                            <label>お気に入り</label>
                            <div style="margin-bottom:10px;">
                              <span class="badge">{{$favuser[$key]}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <label>コメント</label>
                        <div class="list-group wrap">
                        {{$scene->comment != "" ? $scene->comment: 'No comment'}}
                        </div>
                    </div>
                    <!--div-->
                        <!--div id="demo{{$key}}" class="collapse col-xs-12"-->
                            <label>ユーザーコメント</label>
                            @if(isset($userComments[$key][0]))
                            <ul class="list-group">
                                @foreach($userComments[$key] as $kkey => $userComment)
                                    <?php
                                        $mime = $commentUser[$key][$kkey]->mime;
                                        $dataImage = base64_encode($commentUser[$key][$kkey]->data);
                                    ?>
                                    <li class="list-group-item">
                                    <a href="{{route('show_user_profile',['id'=>$commentUser[$key][$kkey]->user_id])}}" class="black">
                                    <div class="overCut" style="width:100%;">
                                    @if(isset($dataImage))
                                        <div class="CommentUserAvatar lazyload img-circle" data-bg="data:{{$mime}};base64,{{$dataImage}}"></div>
                                    @else
                                        <div class="CommentUserAvatar lazyload img-circle" data-bg="{{asset('noimage.png')}}"></div>
                                    @endif
                                    {{$commentUser[$key][$kkey]->nickname or '未設定'}}
                                    @if(Auth::check())
                                    @if(Auth::user()->id == $commentUser[$key][$kkey]->user_id || Auth::user()->id==$scene->user_id)
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
                            @if(Auth::check())
                                {!! Form::open(['route'=>['add_comment','id'=>$scene->user_id,'title_id'=>$scene->title_id,'scene_id'=>$scene->scene_id]]) !!}
                                <div class="form-group">
                                    {!! Form::textarea('comment',null,['placeholder'=>'コメント','class'=>'form-control','rows'=>'3']) !!}
                                </div>
                                {!! Form::submit('書き込み',['class'=>'btn btn-xs btn-warning','style'=>'margin-bottom:10px;']) !!}
                                {!! Form::close() !!}
                            @endif
                        </div>
    <button type="button" class="btn btn-block" data-toggle="collapse" data-target="#demo{{$key}}"><span class="caret"></span></button>
                    <!--/div-->
                </div>
            </div>
        </div>
    @endforeach
</div>

@if(isset($photos))
    @include('parts.modal_scene_edit',['photos'=>$photos])
@endif

@foreach($scenes as $scene)
@include('parts.modal_carousel',['photos'=>$photos,'scene'=>$scene])
@endforeach

@else
    <div class="center jumbotron">
    @if(Request::is('search'))
        @include('parts.searchItem')
    @endif
        <div class="text-center">
            シーンがありません
            @if(Auth::check())
                {!! Link_to_route('show_user_items','シーンを追加する',['id'=>Auth::user()->id]) !!}
            @else
                {!! link_to_route('login.get', 'ログイン') !!}
            @endif
        </div>
    </div>
@endif
@endsection
