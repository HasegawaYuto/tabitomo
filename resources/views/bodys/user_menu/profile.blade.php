@extends('layouts.app')

@section('content')
<div class="row">
@include('bodys.user_menu.contents_menu',['id'=>$id])

  <div class="text-center col-xs-6">
    <div class="panel panel-info">
      <div class="panel panel-heading">
        プロフィールの編集
      </div>
      @if($id==Auth::user()->id)
        <table class="table table-striped">
          <tr>
            <td colspan="2" class="test-center">
              <div class="row">
                <div class="col-xs-10 col-xs-offset-1">
                  <img src="http://placehold.it/640x340/27709b/ffffff" class="img-circle img-responsive">
                </div>
              </div>
                <button type="button" class="btn btn-warning btn-xs" data-toggle="collapse" data-target="#userdata0">編集</button>
                <div id="userdata0" class="collapse" style="margin-top:20px;">
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
            <th class="col-xs-4 text-center">ニックネーム</th>
            <td class="text-left">
                <button type="button" class="btn btn-warning btn-xs" data-toggle="collapse" data-target="#userdata1">編集</button>
                {{$user->name or '未設定'}}
                <div id="userdata1" class="collapse" style="margin-top:20px;">
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
            <th class="text-center">生年月日</th>
            <td class="text-left">
                <button type="button" class="btn btn-warning btn-xs" data-toggle="collapse" data-target="#userdata2">編集</button>
                {{ $user->birthday or '未設定'}}
                <div id="userdata2" class="collapse" style="margin-top:20px;">
                    {!! Form::open(['route'=>['edit_user_profile',$id]]) !!}
                    <div class="form-group form-inline">
                    <div class="form-inline">
                        {!! Form::selectYear('year',$thisyear,1900,$thisyear,['class'=>'form-control']) !!}
                        {!! Form::label('year','年') !!}
                        {!! Form::selectRange('month',1,12,1,['class'=>'form-control']) !!}
                        {!! Form::label('month','月') !!}
                        {!! Form::selectRange('day',1,31,1,['class'=>'form-control']) !!}
                        {!! Form::label('day','日') !!}
                    </div>
                    </div>
                    {!! Form::submit('保存',['class'=>'btn btn-primary btn-xs']) !!}
                    {!! Form::close() !!}
                </div>
            </td>
          </tr>
          <tr>
            <th class="text-center">性別</th>
            <td class="text-left">
                <button type="button" class="btn btn-warning btn-xs" data-toggle="collapse" data-target="#userdata3">編集</button>
                {{$user->sex or '未設定'}}
                <div id="userdata3" class="collapse" style="margin-top:20px;">
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
            <th class="text-center">エリア</th>
            <td class="text-left">
              <button type="button" class="btn btn-warning btn-xs" data-toggle="collapse" data-target="#userdata4">編集</button>
              {{$user->area or '未設定'}}
              <div id="userdata4" class="collapse" style="margin-top:20px;">
                  {!! Form::open(['route'=>['edit_user_profile',$id]]) !!}
                  <div class="form-group form-inline">
                      {!! Form::select('pref_id',$prefs,'00',['class'=>'form-control parent']) !!}
                      <select class="form-control children" name="number" disabled>
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
        @else
          その他のユーザー
        @endif
    </div>
  </div>
</div>

<script>
var $children = $('.children'); //都道府県の要素を変数に入れます。
var original = $children.html(); //後のイベントで、不要なoption要素を削除するため、オリジナルをとっておく

//地方側のselect要素が変更になるとイベントが発生
$('.parent').change(function() {

  //選択された地方のvalueを取得し変数に入れる
  var val1 = $(this).val();

  //削除された要素をもとに戻すため.html(original)を入れておく
  $children.html(original).find('option').each(function() {
    var val2 = $(this).data('val'); //data-valの値を取得

    //valueと異なるdata-valを持つ要素を削除
    if (val1 != val2) {
      $(this).not(':first-child').remove();
    }

  });

  //地方側のselect要素が未選択の場合、都道府県をdisabledにする
  if ($(this).val() == "00") {
    $children.attr('disabled', 'disabled');
  } else {
    $children.removeAttr('disabled');
  }

});
</script>

@endsection
