@extends('layouts.app')

@section('content')
<div class="row">
  @include('bodys.user_menu.contents_menu',['id'=>$id])
  <div class="col-xs-6">
    <div class="panel panel-info">
      <div class="panel panel-heading text-center">マイログ</div>
      @include('parts.tabs',['tab_names'=>['一覧','アップロード']])
      <div class="tab-content">
        <div class="tab-pane active" id="tab1">
          ページネーション
          <div class="panel panel-default">
            ああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああ
          </div>
        </div>
        <div class="tab-pane" id="tab2">
          いいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいい
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
