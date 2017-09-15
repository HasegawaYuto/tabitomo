@extends('layouts.app')

@section('content')
<div class="row">
  <?php
      if(!isset($user->data) && !isset($user->mime)){
          $src = 'http://placehold.it/640x640/27709b/ffffff';
      }else{
          $src = 'data:' . $user->mime . ';base64,' . base64_encode($user->data);
      }
      $url = 'url("' . $src . '")';
  ?>
@include('bodys.user_menu.contents_menu',['user'=>$user])
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
            <td class="text-left"><p><b>ニックネーム</b>&ensp;<button type="button" class="btn btn-warning btn-xs" data-toggle="collapse" data-target="#userprofile1">編集</button></p>
              <p class="text-center">{{$user->nickname or '未設定'}}</p>
              <div id="userprofile1" class="collapse text-center">
                  {!! Form::open(['route'=>['edit_user_profile',$id]])!!}
                  <div class="form-group">
                      {!! Form::text('nickname',null,['class'=>'form-control']) !!}
                  </div>
                  {!! Form::submit('保存',['class'=>'btn btn-primary btn-xs']) !!}
                  {!! Form::close() !!}
              </div>
            </td>
          </tr>
          <tr>
            <td class="text-left"><p><b>年齢</b>&ensp;<button type="button" class="btn btn-warning btn-xs" data-toggle="collapse" data-target="#userprofile2">編集</button></p>
            <p class="text-center">{{isset($user->age) ? $user->age . '歳' : '未設定'}}{{$birthdayOfYear}}</p>
            <div id="userprofile2" class="collapse text-center">
                <label class="text-left">生年月日</label><br>
                {!! Form::open(['route'=>['edit_user_profile',$id]])!!}
                <div class="form-group form-inline">
                    <select name="year" class="form-control">
                        <option value="0000" selected="{{ isset($birthdayOfYear) ? '':'selected'}}">----</option>
                        @for($year=$thisyear;$year>=1900;$year--)
                            <option value="{{$year}}" selected="{{ isset($birthdayOfYear) && $birthdayOfYear==$year ? 'selected':''}}">{{$year}}</option>
                        @endfor
                    </select>
                    <label>年</label>
                    <select name="month" class="form-control">
                        <option value="0" selected="{{ isset($birthdayOfMonth) ? '':'selected'}}">--</option>
                        @for($month=1;$month<=12;$month++)
                            <?php
                              $month0 = str_pad($month, 2, 0, STR_PAD_LEFT);
                            ?>
                            <option value="{{$month0}}" selected="{{ isset($birthdayOfMonth) && $birthdayOfMonth==$month ? 'selected':''}}">{{$month}}</option>
                        @endfor
                    </select>
                    <label>月</label>
                    <select name="day" class="form-control">
                        <option value="0" selected="{{ isset($birthdayOfDay) ? '':'selected'}}">--</option>
                        @for($day=1;$day<=31;$day++)
                            <?php
                                $day0 = str_pad($day, 2, 0, STR_PAD_LEFT);
                            ?>
                            <option value="{{$day0}}" selected="{{ isset($birthdayOfDay) && $birthdayOfDay==$day ? 'selected':''}}">{{$day}}</option>
                        @endfor
                    </select>
                    <label>月</label>
                </div>
                {!! Form::submit('保存',['class'=>'btn btn-primary btn-xs']) !!}
                {!! Form::close() !!}
            </div>
          </td>
          </tr>
          <tr>
            <td class="text-left">性別ラジオ</td>
          </tr>
          <tr>
            <td class="text-left"><p>エリアドロップダウン</p>
              <div id="mapSetArea" class="col-xs-12"></div>
            </td>
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
