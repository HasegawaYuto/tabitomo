<div class="col-xs-3">
<div class="text-left panel panel-info">
  <div class="panel-heading">
    ニックネーム
  </div>
  <div class="panel panel-body text-center" style="height:80px">
    アバター
  </div>
  <div class="list-group">
      {!! Link_to_route('show_user_profile','プロフィール',['id'=>$id],['class'=>'list-group-item text-center']) !!}
      {!! Link_to_route('show_user_matching','マッチング',['id'=>$id],['class'=>'list-group-item text-center']) !!}
      {!! Link_to_route('show_user_messages','メッセージ',['id'=>$id],['class'=>'list-group-item text-center']) !!}
      {!! Link_to_route('show_user_items','マイログ',['id'=>$id],['class'=>'list-group-item text-center']) !!}
      {!! Link_to_route('show_user_favorites','お気に入り',['id'=>$id],['class'=>'list-group-item text-center']) !!}
  </div>
</div>
</div>
