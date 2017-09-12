@extends('layouts.app')

@section('content')
<div class="row">
@include('bodys.user_menu.contents_menu',['id'=>$id])

  <div class="text-center col-xs-6">
    <div class="panel panel-info">
      <div class="panel panel-heading">
        プロフィールの編集
      </div>
      <div class="panel-body">
      @if($id==Auth::user()->id)
        <table class="table table-striped">
          <tr>
            <td class="test-center">
              <div class="row">
                <div class="col-xs-4 col-xs-offset-1">
                  <div id="avatar_change_original"  class="img-circle" style="background-image:{{$user->avatar or 'url(http://placehold.it/640x640/27709b/ffffff)'}};">
                  </div>
                </div>
                <div class="col-xs-offset-2 col-xs-4 " id="avatarChange">
                </div>

<script>
$(function(){//
    var beforeAA = $('#avatar_change_original');//
    var changeAA = $('#avatarChange');//
    beforeAA.css('height',beforeAA.css('width'));
    changeAA.css('height',beforeAA.css('height'));
    beforeAA.css('background-size','cover');
    beforeAA.css('background-position','center');
    beforeAA.css('background-repeat','no-repeat');
    //changeAA.css('background-color','red');
});
////////////////////////////////////////////////////
$(function(){
  //画像ファイルプレビュー表示のイベント追加 fileを選択時に発火するイベントを登録
  $('#avatarForm').on('change', 'input[type="file"]', function(e) {
    var file = e.target.files[0],
        reader = new FileReader(),
        $preview = $("#avatarChange");
        t = this;

    // 画像ファイル以外の場合は何もしない
    if(file.type.indexOf("image") < 0){
      return false;
    }

    // ファイル読み込みが完了した際のイベント登録
    reader.onload = (function(file) {
      return function(e) {
        //既存のプレビューを削除
        $preview.empty();
        // .prevewの領域の中にロードした画像を表示するimageタグを追加
        $preview.prepend('<div class="img-circle" id="NewAvatarField"></div>');
        $('#NewAvatarField').css('background-image','url('+ e.target.result +')');
        $('#NewAvatarField').css('height',$preview.css('height'));
        $('#NewAvatarField').css('background-size','cover');
        $('#NewAvatarField').css('background-position','center');
        $('#NewAvatarField').css('background-repeat','no-repeat');
        $preview.css('background-color','');
        //$preview.append($('<img>').attr({
        //          src: e.target.result,
        //          width: "150px",
        //          class: "preview",
        //          title: file.name
        //      }));
      };
    })(file);
    reader.readAsDataURL(file);
  });
});

</script>

              </div>
                <p><button type="button" class="btn btn-warning btn-xs" data-toggle="collapse" data-target="#userdata0">編集</button></p>
                <div id="userdata0" class="collapse text-center">
                    {!! Form::open(['route'=>['edit_user_profile',$id],'files'=>true,'id'=>'avatarForm']) !!}
                    <div class="form-group">
                        {!! Form::file('avatar') !!}
                    </div>
                    {!! Form::submit('保存',['class'=>'btn btn-primary btn-xs']) !!}
                    {!! Form::close() !!}
                </div>
            </td>
          </tr>
          <tr>
            <td class="col-xs-3 text-left"><p><b>ニックネーム</b>
                <button type="button" class="btn btn-warning btn-xs" data-toggle="collapse" data-target="#userdata1">編集</button>
                {{$user->name or '未設定'}}</p>
                <div id="userdata1" class="collapse text-center">
                    {!! Form::open(['route'=>['edit_user_profile',$id]]) !!}
                    <div class="form-group">
                        {!! Form::text('userNickName',$user->name,['class'=>'form-control userNickNamefld']) !!}
                    </div>
                    {!! Form::submit('保存',['class'=>'btn btn-primary btn-xs']) !!}
                    {!! Form::close() !!}
                </div>
            </td>
          </tr>
          <tr>
            <td class="text-left"><p><b>生年月日</b>
                <button type="button" class="btn btn-warning btn-xs" data-toggle="collapse" data-target="#userdata2">編集</button>
                {{ $user->birthday or '未設定'}}</p>
                <div id="userdata2" class="collapse text-center">
                    {!! Form::open(['route'=>['edit_user_profile',$id]]) !!}
                    <div class="form-group form-inline">
                        {!! Form::selectYear('year',$thisyear,1900,$thisyear,['class'=>'form-control']) !!}
                        {!! Form::label('year','年') !!}
                        {!! Form::selectRange('month',1,12,1,['class'=>'form-control']) !!}
                        {!! Form::label('month','月') !!}
                        {!! Form::selectRange('day',1,31,1,['class'=>'form-control']) !!}
                        {!! Form::label('day','日') !!}
                    </div>
                    {!! Form::submit('保存',['class'=>'btn btn-primary btn-xs']) !!}
                    {!! Form::close() !!}
                </div>
            </td>
          </tr>
          <tr>
            <td class="text-left"><p><b>性別</b>
                <button type="button" class="btn btn-warning btn-xs" data-toggle="collapse" data-target="#userdata3">編集</button>
                {{$user->sex or '未設定'}}</p>
                <div id="userdata3" class="collapse text-center">
                    {!! Form::open(['route'=>['edit_user_profile',$id]]) !!}
                    <div class="form-group form-inline">
                        {!! Form::radio('sex','F',null,['class'=>'form-control','style'=>'width:15px;']) !!}
                        <label>男性</label>
                        {!! Form::radio('sex','M',null,['class'=>'form-control']) !!}
                        <label>女性</label>
                        {!! Form::radio('sex','U',null,['class'=>'form-control']) !!}
                        <label>その他</label>
                    </div>
                    {!! Form::submit('保存',['class'=>'btn btn-primary btn-xs']) !!}
                    {!! Form::close() !!}
                </div>
            </td>
          </tr>
          <tr>
            <td class="text-left"><p><b>エリア</b>
              <button type="button" class="btn btn-warning btn-xs" data-toggle="collapse" data-target="#userdata4">編集</button>
              {{$user->area or '未設定'}}</p>
              <div id="userdata4" class="collapse text-center" style="margin-top:20px;">
                  {!! Form::open(['route'=>['edit_user_profile',$id]]) !!}
                  <div class="form-group form-inline">
                      {!! Form::select('pref_id',$prefs,'00',['class'=>'form-control pref']) !!}
                      <select class="form-control city" name="number" disabled>
                          @foreach($locations as $location)
                              <option selected="selected" value="00000" data-val="00">--市町村--</option>
                              <option data-val="{{$location->pref_id}}" value="{{$location->city_id}}">
                                  {{$location->city_name}}
                              </option>
                          @endforeach
                      </select>
                  </div>
                  {!! Form::submit('保存',['class'=>'btn btn-primary btn-xs']) !!}
                  {!! Form::close() !!}
              </div>
            </td>
          </tr>
        </table>
      </div>
        @else
          その他のユーザー
        @endif
    </div>
  </div>
</div>
@endsection
