@extends('layouts.app')

@section('content')
<div class="container-fluid">
<div class="row">
@include('bodys.user_menu.contents_menu',['user'=>$user])

<div class="col-xs-6">
    <div class="panel panel-info">
        <div class="panel-heading text-center">
            <i class="fa fa-envelope-o fa-fw" aria-hidden="true"></i>メッセージ
        </div>
        <div class="panel-body">
            @if(Auth::user()->id == $user->user_id)
                @if(isset($messageUsers[0]))
                    @foreach($messageUsers as $messageUser)
                        <a data-target="#messageboad" data-toggle="modal">
                        <div class="messangerImageOuter text-center black">
                        @if(isset($messageUser->data))
                            <?php
                                $mime = $messageUser->mime;
                                $dataImage = base64_encode($messageUser->data);
                            ?>
                            <div class="lazyload messangerImage img-circle" data-bg="data:{{$mime}};base64,{{$dataImage}}"></div>
                        @else
                            <div class="lazyload messangerImage img-circle" data-bg="{{asset('noimage.png')}}"></div>
                        @endif
                      {{$messageUser->nickname!=""?$messageUser->nickname:"no name"}}
                      </div>
                    </a>
                    @endforeach
                @else
                    メッセージはありません
                @endif
            @else
            <button class="btn btn-xs btn-success" type="button" data-toggle="modal" data-target="#messageboad">メッセージ送信</button>
            @endif
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="messageboad">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                タイトル
            </div>
        </div>
    </div>
</div>
@endsection
