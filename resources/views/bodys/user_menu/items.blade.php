@extends('layouts.app')

@section('content')
<div class="row">
  @include('bodys.user_menu.contents_menu',['id'=>$id])
  <div class="col-xs-6">
    <div class="panel panel-info">
      <div class="panel panel-heading text-center">マイログ</div>
      @include('parts.tabs',['tab_names'=>['一覧','アップロード'],'class'=>'nav-tabs nav-justified'])
      <div class="tab-content">
        <div class="tab-pane active" id="tab1">
          ページネーション
          <div class="panel panel-primary">
            <div class="panel panel-heading">
              旅のタイトル
            </div>
            <div class="panel panel-body">
              <div class="col-xs-4">
                サムネイル
              </div>
              <div class="col-xs-8">
                bbbb
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab2">
          @include('parts.form',['config'=>['url'=>'/']])
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
