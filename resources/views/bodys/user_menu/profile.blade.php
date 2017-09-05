@extends('layouts.app')

@section('content')
<div class="row">
@include('bodys.user_menu.contents_menu',['id'=>$id])

  <div class="text-center col-xs-6">
    <div class="panel panel-info">
      <div class="panel panel-heading">
        プロフィールの編集
      </div>
      @if($id==1)
        <table class="table table-striped">
          <tr>
            <th class="col-xs-4 text-center">ニックネーム</th><td class="text-left">（テキストエリア）</td>
          </tr>
          <tr>
            <th class="text-center">生年月日</th><td class="text-left">（ダイヤル式）</td>
          </tr>
          <tr>
            <th class="text-center">性別</th><td class="text-left">ラジオ</td>
          </tr>
          <tr>
            <th class="text-center">エリア</th><td class="text-left">ドロップダウン</td>
          </tr>
        </table>
        @else
          その他のユーザー
        @endif
    </div>
  </div>
</div>
@endsection
