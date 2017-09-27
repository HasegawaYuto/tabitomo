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
      {{$user->nickname or '未設定'}}
  </div>
  <div class="panel-body text-center">
    <div id="menuavatarBeforeChange" style="padding:50% 0 0 ;width:50%;height:100%;position: relative;left:25%;">
        <div id="menuavatarBeforeChangeArea" class="img-circle" style="background-image:{{$url}};
        height:100%;
        width:100%;
        background-size:cover;
        background-position:center;
        position: absolute;
	top: 0;
	left: 0;">
        </div>
    </div>
  </div>
  <div class="list-group">
      {!! Link_to_route('show_user_profile','プロフィール',['id'=>$user->user_id],['class'=>'list-group-item text-center']) !!}
      {!! Link_to_route('show_user_matching','マッチング',['id'=>$user->user_id],['class'=>'list-group-item text-center']) !!}
      {!! Link_to_route('show_user_messages','メッセージ',['id'=>$user->user_id],['class'=>'list-group-item text-center']) !!}
      {!! Link_to_route('show_user_items','マイログ',['id'=>$user->user_id],['class'=>'list-group-item text-center']) !!}
      {!! Link_to_route('show_user_favorites','お気に入り',['id'=>$user->user_id],['class'=>'list-group-item text-center']) !!}
  </div>
</div>
</div>
