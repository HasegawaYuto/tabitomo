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
                    @foreach($messageUsers as $key => $messageUser)
                        <a data-target="#messageboad" data-toggle="modal" UserFrom="{{Auth::user()->id}}" UserTo="{{$messageUser->user_id}}" User="{{$messageUser->nickname==''?'No nickname':$messageUser->nickname}}">
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
                      <div>
                      @if($messages[$key][0]->status == 0)
                          <span class="label label-danger" id="checkNew{{$key}}">New</span>
                      @else
                          <span class="label label-default" id="checkNew{{$key}}">新着なし</span>
                      @endif
                    </div>
                      </div>
                    </a>
                    @endforeach
                @else
                    メッセージはありません
                @endif
            @else
            <button User="{{$user->nickname==''?'No nickname':$user->nickname}}" UserFrom="{{Auth::user()->id}}" UserTo="{{$user->user_id}}" class="btn btn-xs btn-success" type="button" data-toggle="modal" data-target="#messageboad">メッセージ送信</button>
            @endif
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="messageboad">
    <div class="modal-dialog">
        <div class="modal-content" id="messageboadbody">
            <div class="modal-header wrap">
                チャット&nbsp;with&nbsp;
            </div>
            <div class="modal-body">
                本体
            </div>
            <div class="modal-footer">
                <form>
                    <div class="from-group">
                        {!! Form::textarea('message',null,['class'=>'form-control','id'=>'themessage','rows'=>'3']) !!}
                    </div>
                </form>
                <input type="submit" class="btn btn-primary btn-block" value="送信" id="messageSubmit">
            </div>
        </div>
    </div>
</div>
@endsection
