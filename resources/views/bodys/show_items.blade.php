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
                  @if(Auth::user()->id == $titles[$key]->user_id)
                    <button type="button" class="btn btn-warning btn-xs"  data-toggle="modal" data-target="#fixScene0" data-scene="{{$scene->scene}}"
                    data-lat="{{$scene->lat}}"
                    data-lng="{{$scene->lng}}"
                    data-score="{{$scene->score}}"
                    data-comment="{{$scene->comment}}"
                    data-oldtheday="{{$scene->theday}}"
                    data-sceneid="{{$scene->scene_id}}"
                    data-titleid="{{$scene->title_id}}"
                    data-userid="{{$titles[$key]->user_id}}"
                    data-publish="{{$scene->publish}}"
                    data-firstday="{{$titles[$key]->firstday}}"
                    data-lastday="{{$titles[$key]->lastday}}"
                    data-genre="{{$scene->genre}}" 
                    data-editstyle="fix"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                    @include('parts.delete_button_scene',['thescene'=>$scene])
                  @else
                    @include('parts.favorite_scene_button',['thescene'=>$scene])
                  @endif
                  @endif
                    &nbsp;&nbsp;{{$scene->scene =="" ? 'No Title':$scene->scene}}
                </div>
                <div class="panel-body">
                    <a href="{{route('show_title',['id'=>$titles[$key]->user_id,'title_id'=>$scene->title_id])}}">
                     <div class="black titleFrom">
                        <p class="smallp">【ジャンル】</p>
                        @if($scene->genre!="")
                            @if(strpos($scene->genre,'A')!== false)
                                <div class="white chlbl chdivH" style="background-color:#228b22;">
                                      <i class="fa fa-leaf" aria-hidden="true"></i>
                                  </div>
                            @endif
                            @if(strpos($scene->genre,'B')!== false)
                                <div class="black chlbl chdivH" style="background-color:#ffff00;">
                                      <i class="fa fa-history" aria-hidden="true"></i>
                                  </div>
                            @endif
                            @if(strpos($scene->genre,'C')!== false)
                                <div class="white chlbl chdivH" style="background-color:#a0522d;">
                                      <i class="fa fa-university" aria-hidden="true"></i>
                                  </div>
                            @endif
                            @if(strpos($scene->genre,'D')!== false)
                                <div class="black chlbl chdivH" style="background-color:#ff69b4;">
                                      <i class="fa fa-cutlery" aria-hidden="true"></i>
                                  </div>
                            @endif
                            @if(strpos($scene->genre,'E')!== false)
                                <div class="black chlbl chdivH" style="background-color:#00ffff;">
                                      <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                  </div>
                            @endif
                            @if(strpos($scene->genre,'F')!== false)
                                <div class="black chlbl chdivH" style="background-color:#ffffff;">
                                      <i class="fa fa-futbol-o" aria-hidden="true"></i>
                                  </div>
                            @endif
                        @else
                            <div class="black chlbl chdivH">
                                      未設定
                            </div>
                        @endif
                      <p class="smallp">【タイトル】</p>
                    <p class="overCut">{{$titles[$key]->title}}</p>
                  </div></a>
                  <a href="{{route('show_user_profile',['id'=>$titles[$key]->user_id])}}">
                  <div class="itemAvatarOuter text-center black overCut">
                      @include('parts.avatar',['user'=>$user[$key],'class'=>'itemAvatar'])
                    <p class="smallp">{{isset($user[$key]->nickname)?$user[$key]->nickname:"未設定"}}</p>
                  </div>
                  </a>
                    <div class="col-xs-12">
                    @if(isset($thumb[$key]->data))
                    <?php
                        $mime = $thumb[$key]->mime;
                        $phase1 = pg_fetch_object($thumb[$key]->escdata);
                        $phase2 = pg_unescape_bytea($phase1);
                        $dataImage = base64_encode($phase2);
                    ?>
                    <a href="#modal_carousel{{$scene->scene_id}}" data-toggle="modal" data-local="#myCarousel{{$scene->user_id}}-{{$scene->title_id}}-{{$scene->scene_id}}">
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
                    @include('parts.go_navi',['scene'=>$scene])
                    <div id="demo{{$key}}" class="collapse col-xs-12">
                        <label>日付</label>
                        <div class="text-center">
                            {{$thedayarray[0]}}年{{(int)$thedayarray[1]}}月{{(int)$thedayarray[2]}}日
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
                            <div style="margin-bottom:10px;" class="text-center">
                              <a data-target="#modalFavoriteUsers{{$key}}" data-toggle="modal">
                                <span class="badge">{{count($favuser[$key])}}</span>
                            </a>
                            </div>
                        </div>
                    <!--div class="col-xs-12"-->
                        <label>コメント</label>
                        <div class="list-group wrap">
                        {{$scene->comment != "" ? $scene->comment: 'No comment'}}
                        </div>
                    <!--/div-->
                    <!--div-->
                            @if(isset($comments[$key][0]))
                            <label>ユーザーコメント</label>
                            <ul class="list-group">
                                @foreach($comments[$key] as $kkey => $comment)
                                <?php $theuser = App\User::find($comment->user_id); ?>
                                    <li class="list-group-item">
                                    <a href="{{route('show_user_profile',['id'=>$theuser->id])}}" class="black">
                                    <div class="overCut black" style="width:100%;">
                                        @include('parts.avatar',['user'=>$theuser,'class'=>'CommentUserAvatar'])
                                    <p class="smallp">{{$theuser->nickname or '未設定'}}</p>
                                    @if(Auth::check())
                                    @if(Auth::user()->id == $titles[$key]->user_id || Auth::user()->id==$theuser->id)
                                        @include('parts.comment_delete_button',['comment'=>$comment])
                                    @endif
                                    @endif
                                    </div></a>
                                    <div class="commentContent clearfix">
                                    {{$comment->comment}}
                                    </div>
                                    </li>
                                @endforeach
                            </ul>
                            @endif
                            @if(Auth::check())
                                {!! Form::open(['route'=>['add_comment','scene_id'=>$scene->scene_id]]) !!}
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

@if(isset($photos[0]))
    @include('parts.modal_scene_edit',['photos'=>$photos])
@endif

@foreach($scenes as $key => $scene)
@include('parts.modal_carousel',['photos'=>$photos,'scene'=>$scene,'title'=>$titles[$key]])
@include('parts.showFavoriteUsers',['key'=>$key,'users'=>$favuser[$key]])
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
