@extends('layouts.app')

@section('content')
<div class="container-fluid">
<div class="row">
    @include('bodys.user_menu.contents_menu',['user'=>$user])
    <div class="col-xs-12 col-sm- col-md-9 col-lg-6">
        <div class="panel panel-info">
            <div class="panel-heading text-center">
                <i class="fa fa-handshake-o fa-fw" aria-hidden="true"></i>マッチング
            </div>
            <div class="panel-body">
                <button type="button" data-userid="{{$user->id}}" data-man="guide" class="btn btn-xs btn-success" data-toggle="modal" data-target="#GuestGuidePost">ガイド募集</button>
                <button type="button" data-userid="{{$user->id}}" data-man="guest" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#GuestGuidePost">ゲスト募集</button>
            <ul class="list-group">
            <?php
                function replaceDate($date){
                    $part = explode('-',$date);
                    return $part[0].'年'.(int)$part[1].'月'.(int)$part[2].'日';
                }
                function avatarSrc($user){
                    if(isset($user->data)){
                        return 'data:'.$user->mime.'base64,'.base64_encode($user->data);
                    }else{
                        return "asset('noimage.png')";
                    }
                }
            ?>
            @if(isset($recruitments[0]))
            <div>
            {!! $recruitments->render() !!}
            </div>
            @foreach($recruitments as $key => $recruitment)
            <li class="list-group-item list-group-item-{{$recruitment->type =='guide' ? 'success':'warning'}}" style="margin-bottom:10px;">
                      〆{{$recruitment->limitdate !=0 ? replaceDate($recruitment->limitdate):'なし'}}
                      @if(count($candidateusers[$key])!=0)
                      <span class="badge pull-left">{{count($candidateusers[$key])}}</span>
                      @endif
                      @if($recruitment->type=="guide")
                      {!!Form::open(['route'=>['guide_delete','guide_id'=>$recruitment->id],'style'=>'display:inline;float:right;'])!!}
                      @else
                      {!!Form::open(['route'=>['guest_delete','guest_id'=>$recruitment->id],'style'=>'display:inline;float:right;'])!!}
                      @endif
                      <button class="btn btn-xs btn-danger" type="submit">
                          <i class="fa fa-trash-o" aria-hidden="true"></i>
                      </button>
                      {!!Form::close()!!}
                      <div class="wrap col-xs-12 Info">{{$recruitment->contents}}</div>
                      <div class="recruitmentMap recruitmentmapSpot" id="recruitmentMap{{$key}}"></div>
                      <input type="hidden" value="{{$recruitment->lat}}" id="recruitmentLat{{$key}}">
                      <input type="hidden" value="{{$recruitment->lng}}" id="recruitmentLng{{$key}}">
                      <input type="hidden" value="{{$recruitment->radius}}" id="recruitmentRadius{{$key}}">
            @if(isset($candidateusers[$key][0]))
            <div style="margin-top:10px;">【マッチング】</div>
            @foreach($candidateusers[$key] as $kkey => $candidateuser)
                <a href="{{route('show_user_profile',['id'=>$candidateuser->id])}}">
                <div class="candidateImageOuter black text-center overCut">
                    @include('parts.avatar',['user'=>$candidateuser,'class'=>'CandidateImage'])
                {{$candidateuser->nickname!=""?$candidateuser->nickname:"no name"}}
              </div>
                </a>
            @endforeach
            @endif
            </li>
            @endforeach
            @endif
            </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="GuestGuidePost">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                header
            </div>
            <div class="modal-body">
                {!! Form::open(['route'=>['guest_post','id'=>Auth::user()->id],'id'=>'GuestGuideForm']) !!}
                <input type="hidden" value="0" name="cnt" id="cnt">
                <div class="form-group">
                    <label>締め切り</label><small style="color:red;">オプション</small>
                    {!! Form::text('limitdate',null,['class'=>'form-control','style'=>'width:50%;','id'=>'Relimitdate']) !!}
                </div>
                <label>スポット</label>
                <input type="hidden" value="35" name="centerLat" id="centerLat">
                <input type="hidden" value="136" name="centerLng" id="centerLng">
                <input type="hidden" value="20000" name="circleRadius" id="circleRadius">
                <div id="GuestGuideSpotMap" style="width:300px;height:200px;max-width:100%;"></div>
                <div id="SpotRadiusArea"></div>
                <div class="form-group">
                    {!! Form::label('contents','フリー') !!}<small>日程、キワードなど必要なことを書いてください</small>
                    {!! Form::textarea('contents',null,['class'=>'form-control','rows'=>'4']) !!}
                </div>
                {!! Form::submit('登録',['class'=>'btn btn-xs btn-primary']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
