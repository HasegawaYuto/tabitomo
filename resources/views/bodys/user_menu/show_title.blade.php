@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
@include('bodys.user_menu.contents_menu',['user'=>$user])
<div class="col-md-9">
    <div class="panel panel-info">
        <div class="panel-heading">
            {!! Link_to_route('show_user_items','マイログ',['id'=>$id]) !!}　≫　{{$title_id}}旅のタイトル&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-xs">編集</button>
        </div>
        <div class="panel-body">
            スケジュール<br>
            ページネーション
            @for($i=1;$i<10;$i++)
            @if($i % 3==1)
                <div class="row">
            @endif
                <div class="col-xs-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            シーン{{$i}}：{{$id}}-{{$title_id}}&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-xs">編集</button>
                        </div>
                        <div class="panel-body">
                            <div class="text-center">
                                サムネイル：モーダルでカルーセル表示
                            </div>
                            <div>
                                <p>〇月〇日</p>
                                <p>〇〇寺</p>
                                <p>★★★☆☆</p>
                                <p>コメント</p>
                                <div id="demo{{$i}}" class="collapse">
                                    <p>他のユーザのコメント</p>
                                </div>
                                <button type="button" class="btn btn-block" data-toggle="collapse" data-target="#demo{{$i}}"><span class="caret"></span></button>
                            </div>
                        </div>
                    </div>
                </div>
                @if($i % 3==0)
                </div>
                @endif
            @endfor
        </div>
    </div>
</div>
</div>
</div>
@endsection
