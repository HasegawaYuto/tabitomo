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
                      @if(isset($sentmessages[$key][0]) &&$sentmessages[$key][0]->status == 0)
                          <button data-partner="{{$messageUser->user_id}}"
                             class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#messageboad">新着あり</button>
                      @else
                          <button data-partner="{{$messageUser->user_id}}"
                            class="btn btn-xs btn-default" type="button" data-toggle="modal" data-target="#messageboad">新着なし</button>
                      @endif
                    </div>
                      </div>
                    @endforeach
                @else
                    メッセージはありません
                @endif
            @else
            <button data-partner="{{$user->user_id}}" class="btn btn-xs btn-success" type="button" data-toggle="modal" data-target="#messageboad">メッセージ送信</button>
            @endif
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="messageboad">
    <div class="modal-dialog">
        <div class="modal-content" id="messageboadbody">
            {!! Form::open(['route'=>['load_message','id'=>Auth::user()->id,'partner_id'=>'num'],'id'=>'loadform']) !!}
            {!! Form::close() !!}
            <input type="hidden" id="newTimestamp" value="0000-00-00 00:00:00">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" id="MessageCsrfTokenGet">
            <div class="modal-header wrap" id="messageHeader">
                メッセージ
            </div>
            <div class="modal-body" id="messageBody">
              <div class="messageShow"></div>
            </div>
            <div class="modal-footer"  id="messageFooter">
                {!! Form::open(['route'=>['send_message','id'=>Auth::user()->id,'send_id'=>'num'],'id'=>'sendform']) !!}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="MessageCsrfTokenPost">
                    <input type="hidden" value="0" id="partnerId">
                    <div class="from-group">
                        {!! Form::textarea('message',null,['class'=>'form-control','id'=>'themessage','rows'=>'3']) !!}
                    </div>
                {!!Form::close()!!}
                <button type="button" class="btn btn-primary btn-block" id="messageSubmit">送信</button>
            </div>
        </div>
    </div>
</div>
@endsection
