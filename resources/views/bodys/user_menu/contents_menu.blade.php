<?php
    if(!isset($user->data) && !isset($user->mime)){
        $src = 'http://placehold.it/640x640/27709b/ffffff';
    }else{
        $src = 'data:' . $user->mime . ';base64,' . base64_encode($user->data);
    }
    $url = 'url("' . $src . '")';
?>
<div class="col-md-3">
<div class="text-left panel panel-info">
  <div class="panel-heading">
      <i class="fa fa-user-circle-o fa-fw" aria-hidden="true"></i>{{$user->nickname or '未設定'}}
  </div>
  <div class="panel-body text-center">
    <div id="menuavatarBeforeChange">
        <div id="menuavatarBeforeChangeArea" class="img-circle" style="background-image:{{$url}};">
        </div>
    </div>
  </div>
  <div class="list-group">
      <a href="{{ route('show_user_profile',['id'=>$user->user_id]) }}" class="list-group-item text-center"><i class="fa fa-user-circle-o fa-fw" aria-hidden="true"></i>プロフィール</a>
      <a href="{{ route('show_user_matching',['id'=>$user->user_id]) }}" class="list-group-item text-center"><i class="fa fa-handshake-o fa-fw" aria-hidden="true"></i>マッチング</a>
      <a href="{{ route('show_user_messages',['id'=>$user->user_id]) }}" class="list-group-item text-center"><i class="fa fa-envelope-o fa-fw" aria-hidden="true"></i>メッセージ</a>
      <a href="{{ route('show_user_items',['id'=>$user->user_id]) }}" class="list-group-item text-center">マイログ</a>
      <a href="{{ route('show_user_favorites',['id'=>$user->user_id]) }}" class="list-group-item text-center">お気に入り</a>
  </div>
</div>
</div>
