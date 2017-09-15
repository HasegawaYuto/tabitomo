<?php
    if(!isset($user->data) && !isset($user->mime)){
        $src = 'http://placehold.it/640x640/27709b/ffffff';
    }else{
        $src = 'data:' . $user->mime . ';base64,' . base64_encode($user->data);
    }
    $url = 'url("' . $src . '")';
?>
<div class="col-xs-3">
<div class="text-left panel panel-info">
  <div class="panel-heading">
      {{$user->nickname or 未設定}}
  </div>
  <div class="panel-body text-center">
    <div class="col-xs-offset-2 col-xs-8"  id="menuavatarBeforeChange">
        <div id="menuavatarBeforeChangeArea" class="img-circle" style="background-image:{{$url}};">
        </div>
    </div>
  </div>
  <div class="list-group">
      <?php $user->user_id= Auth::user()->id ?>
      {!! Link_to_route('show_user_profile','プロフィール',['id'=>$id],['class'=>'list-group-item text-center']) !!}
      {!! Link_to_route('show_user_matching','マッチング',['id'=>$id],['class'=>'list-group-item text-center']) !!}
      {!! Link_to_route('show_user_messages','メッセージ',['id'=>$id],['class'=>'list-group-item text-center']) !!}
      {!! Link_to_route('show_user_items','マイログ',['id'=>$id],['class'=>'list-group-item text-center']) !!}
      {!! Link_to_route('show_user_favorites','お気に入り',['id'=>$id],['class'=>'list-group-item text-center']) !!}
  </div>
</div>
</div>
