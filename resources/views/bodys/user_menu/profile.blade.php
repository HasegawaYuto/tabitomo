@extends('layouts.app')

@section('content')
<div class="row">
  <?php
      if($data == '未設定' && $mime =='未設定'){
          $src = 'http://placehold.it/640x640/27709b/ffffff';
      }else{
          $src = 'data:' . $mime . ';base64,' . $data;
      }
      $url = 'url("' . $src . '")';
  ?>
@include('bodys.user_menu.contents_menu',['id'=>$id,'url'=>$url,'nickname'=>$nickname])

  <div class="text-center col-xs-6">
    <div class="panel panel-info">
      <div class="panel panel-heading">
        プロフィールの編集
      </div>
      <div class="panel-body">
      @if(Auth::user()->id == $id)
        <table class="table table-striped">
          <tr>
            <td class="text-center">
                <div class="row">
                <div class="col-xs-offset-1 col-xs-4"  id="avatarBeforeChange">
                    <div id="avatarBeforeChangeArea" class="img-circle" style="background-image:{{$url}};">
                    </div>
                </div>
                <div class="col-xs-offset-2 col-xs-4">
                    <div id="avatarAfterChangeArea" class="img-circle">
                    </div>
                </div>
                </div>
                <button type="button" class="btn btn-warning btn-xs" data-toggle="collapse" data-target="#userprofile0">編集</button>
                <div id="userprofile0" class="collapse">
                    {!! Form::open(['route'=>['edit_user_profile',$id],'files'=>'true','id'=>'avatarForm'])!!}
                    {!! csrf_field() !!}
                    <div class="form-group">
                        {!! Form::file('avatar') !!}
                    </div>
                    {!! Form::submit('保存',['class'=>'btn btn-primary btn-xs']) !!}
                    {!! Form::close() !!}
                </div>
            </td>
          </tr>
          <tr>
            <td class="text-left"><p><b>ニックネーム</b><button type="button" class="btn btn-warning btn-xs" data-toggle="collapse" data-target="#userprofile1">編集</button></p>
              <p class="text-center">{{$nickname}}</p>
              <div id="userprofile1" class="collapse">
                  {!! Form::open(['route'=>['edit_user_profile',$id]])!!}
                  <div class="form-group">
                      {!! Form::text('nickname',$nickname,['class'=>'form-control']) !!}
                  </div>
                  {!! Form::submit('保存',['class'=>'btn btn-primary btn-xs']) !!}
                  {!! Form::close() !!}
              </div>
            </td>
          </tr>
          <tr>
            <td class="text-left">生年月日（ダイヤル式）</td>
          </tr>
          <tr>
            <td class="text-left">性別ラジオ</td>
          </tr>
          <tr>
            <td class="text-left">エリアドロップダウン</td>
          </tr>
        </table>
        @else
          その他のユーザー
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
