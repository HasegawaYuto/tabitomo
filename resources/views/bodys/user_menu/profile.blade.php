@extends('layouts.app')

@section('content')
<div class="container">
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
  <div class="text-center col-md-6">
    <div class="panel panel-info">
      <div class="panel panel-heading">
        プロフィールの編集
      </div>
      <div class="panel-body">
      @if(Auth::user()->id == $id)
        <table class="table table-striped">
          <tr>
            <td class="text-center">
                <div class="col-xs-6">
                <div  id="avatarBeforeChange"
                style="padding:50% 0 0;position:relative;width:50%;height:50%;left:25%;">
                    <div id="avatarBeforeChangeArea" class="img-circle" style="background-image:{{$url}};height:100%;
                    width:100%;
                    background-size:cover;
                    background-position:center;
                    position: absolute;
            	top: 0;
            	left: 0;">
                    </div>
                </div>
                </div>
                <div class="col-xs-6">
                <div style="padding:50% 0 0;position:relative;width:50%;height:50%;left:25%;">
                    <div id="avatarAfterChangeArea" class="img-circle"  style="height:100%;
                    width:100%;
                    background-size:cover;
                    background-position:center;
                    position: absolute;
            	top: 0;
            	left: 0;">
                    </div>
                </div>
                </div>
                <button type="button" class="btn btn-warning btn-xs" data-toggle="collapse" data-target="#userprofile0">編集</button>
                <div id="userprofile0" class="collapse">
                    {!! Form::open(['route'=>['edit_user_profile',$id],'files'=>'true','id'=>'avatarForm'])!!}
                    {!! csrf_field() !!}
                    <div class="form-group">
                        {!! Form::file('avatar',['accept'=>'image/*']) !!}
                    </div>
                    {!! Form::submit('保存',['class'=>'btn btn-primary btn-xs']) !!}
                    {!! Form::close() !!}
                    <p>スマホで撮った画像などは保存の際に向きを調節します</p>
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
            <p class="text-center">
            <?php
                if(isset($user->birthday)){
                    $age = Carbon\Carbon::parse($user->birthday)->age;
                    $birthday = new Carbon\Carbon($user->birthday);
                        $birthdayOfYear = $birthday->year;
                        $birthdayOfMonth = $birthday->month;
                        $birthdayOfDay = $birthday->day;
                }
            ?>
            {{isset($user->birthday) ? $age . '歳' : '未設定'}}</p>
            <div id="errorcheckdiv"></div>
            <div id="userprofile2" class="collapse text-center">
                <label class="text-left">生年月日</label><br>
                {!! Form::open(['route'=>['edit_user_profile',$id]])!!}
                <div class="form-group form-inline">
                    <select name="year" id="yearSelectBox" class="form-control">
                      @for($year=$thisyear;$year>=1900;$year--)
                          <option value="{{$year}}" {{ isset($birthdayOfYear) && $birthdayOfYear==$year ? 'selected="selected"':''}}>{{$year}}</option>
                      @endfor
                      <option value="0000" {{ isset($birthdayOfYear) ? '':'selected="selected"'}}>----</option>
                    </select>
                    <label>年</label>
                    <select name="month" id="monthSelectBox" class="form-control">
                        @for($month=12;$month>=1;$month--)
                            <?php
                              $month0 = str_pad($month, 2, 0, STR_PAD_LEFT);
                            ?>
                            <option value="{{$month0}}" {{ isset($birthdayOfMonth) && $birthdayOfMonth==$month ? 'selected="selected"':''}}>{{$month}}</option>
                        @endfor
                        <option value="00" {{ isset($birthdayOfMonth) ? '':'selected="selected"'}}>--</option>
                    </select>
                    <label>月</label>
                    <select name="day" id="daySelectBox" class="form-control">
                        @for($day=31;$day>=1;$day--)
                            <?php
                                $day0 = str_pad($day, 2, 0, STR_PAD_LEFT);
                            ?>
                            <option data-val="{{$day0}}" value="{{$day0}}" {{ isset($birthdayOfDay) && $birthdayOfDay==$day ? 'selected="selected"':''}}>{{$day}}</option>
                        @endfor
                        <option data-val="00" value="00" {{ isset($birthdayOfDay) ? '':'selected="selected"'}}>--</option>
                    </select>
                    <label>日</label>
                </div>
                {!! Form::submit('保存',['class'=>'btn btn-primary btn-xs']) !!}
                {!! Form::close() !!}
            </div>
          </td>
          </tr>
          <tr>
            <td class="text-left"><p><b>性別</b>&ensp;<button type="button" class="btn btn-warning btn-xs" data-toggle="collapse" data-target="#userprofile3">編集</button></p>
            <p class="text-center">{{$user->sex or '未設定'}}</p>
              <div id="userprofile3" class="collapse text-center">
                  {!! Form::open(['route'=>['edit_user_profile',$id]])!!}
                  <div class="form-group form-inline">
                      <input {{ isset($user->sex) && $user->sex == "男性" ? 'checked="checked"' : ''}} name="sex" type="radio" value="男性">
                      <label>男性</label>
                      <input {{ isset($user->sex) && $user->sex == "女性" ? 'checked="checked"' : ''}} name="sex" type="radio" value="女性">
                      <label>女性</label>
                      <input {{ isset($user->sex) && $user->sex == "その他" ? 'checked="checked"' : ''}} name="sex" type="radio" value="その他">
                      <label>その他</label>
                  </div>
                  {!! Form::submit('保存',['class'=>'btn btn-primary btn-xs']) !!}
                  {!! Form::close() !!}
              </div>
            </td>
          </tr>
          <tr>
            <td class="text-left"><p><b>エリア</b>&ensp;<button type="button" class="btn btn-warning btn-xs" data-toggle="collapse" data-target="#userprofile4">編集</button></p>
            <p class="text-center">{{$user->area or '未設定'}}</p>
              <div id="userprofile4" class="collapse text-center">
                  {!! Form::open(['route'=>['edit_user_profile',$id]])!!}
                  <div class="form-group form-inline">
                      {!! Form::select('pref_id',$prefs,old('pref_id'),['class'=>'form-control','id'=>'prefselect']) !!}
                      <select id="cityselect" class="form-control" name="city_id" disabled>
                          <option data-val="00000" value="00000" >--市町村--</option>
                          @foreach($locations as $location)
                          <option data-val="{{$location->pref_id}}" value="{{$location->city_id}}" >{{$location->city_name}}</option>
                          @endforeach
                      </select>
                  </div>
                  {!! Form::submit('保存',['class'=>'btn btn-primary btn-xs']) !!}
                  {!! Form::close() !!}
              </div>
            </td>
          </tr>
        </table>
        @else
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
            </td>
          </tr>
          <tr>
            <td class="text-left"><p><b>ニックネーム</b></p>
              <p class="text-center">{{$user->nickname or '未設定'}}</p>
            </td>
          </tr>
          <tr>
            <td class="text-left"><p><b>年齢</b></p>
            <p class="text-center">
            <?php
                if(isset($user->birthday)){
                    $age = Carbon\Carbon::parse($user->birthday)->age;
                }
            ?>
            {{isset($user->birthday) ? $age . '歳' : '未設定'}}</p>
          </td>
          </tr>
          <tr>
            <td class="text-left"><p><b>性別</b></p>
            <p class="text-center">{{$user->sex or '未設定'}}</p>
            </td>
          </tr>
          <tr>
            <td class="text-left"><p><b>エリア</b></p>
            <p class="text-center">{{$user->area or '未設定'}}</p>
            </td>
          </tr>
        </table>
        @endif
      </div>
    </div>
  </div>
</div>
</div>
@endsection
