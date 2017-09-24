@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
@include('bodys.user_menu.contents_menu',['user'=>$user])
<div class="col-md-9">
    <div class="panel panel-info">
        <div class="panel-heading">
            {!! Link_to_route('show_user_items','マイログ',['id'=>$id]) !!}　≫　{{$title->title}}&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-xs">編集</button>
        </div>
        <div class="panel-body">
            <?php
                $firstdayarray = explode('-',$title->firstday);
                $lastdayarray = explode('-',$title->lastday);
            ?>
        <div class="title_detail">
            {{$firstdayarray[0]}}年
            {{(int)$firstdayarray[1]}}月
            {{(int)$firstdayarray[2]}}日
            ～
            {{$lastdayarray[0]==$firstdayarray[0] ? '':$lastdayarray[0].'年'}}
            {{(int)$lastdayarray[1]}}月
            {{(int)$lastdayarray[2]}}日
            ページネーション
        </div>
            @foreach($scenes as $key => $scene)
            <?php
                $thedayarray = explode('-',$scene->theday);
            ?>
                <div class="col-xs-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            {{$scene->scene =="" ? 'No Title':$scene->scene}}
                            &nbsp;&nbsp;<button type="button" class="btn btn-warning btn-xs">編集</button>
                        </div>
                        <div class="panel-body">
                            <div class="text-center">
                                サムネイル：モーダルでカルーセル表示
                            </div>
                            <div>
                                <p>
                                  {{$thedayarray[0]}}年
                                  {{(int)$thedayarray[1]}}月
                                  {{(int)$thedayarray[2]}}日
                                </p>
                                <input type="hidden" value="{{$scene->lat}}" id="googlemapLat{{$key}}" />
                                <input type="hidden" value="{{$scene->lng}}" id="googlemapLng{{$key}}" />
                                <div class="googlemapSpot col-xs-12" style="height:20vh;" id="googlemapSpotID{{$key}}">
                                </div>
                                <p>{{$scene->score != "" ? $scene->score : 'No Score'}}</p>
                                <p>{{$scene->comment != "" ? $scene->comment: 'No comment'}}</p>
                                <div id="demo{{$key}}" class="collapse">
                                    <p>他のユーザのコメント</p>
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
@endsection
