<div class="col-sm-3 col-md-3 col-xs-6 col-lg-3 hidden-xs">
<div class="text-left panel panel-info">
  <div class="panel-heading overCut">
      @if($user->user_id != Auth::user()->id)
      @include('parts.follow_button',['user'=>$user])
      @endif
      <i class="fa fa-user-circle-o fa-fw" aria-hidden="true"></i>{{$user->nickname or '未設定'}}
  </div>
  <div class="panel-body text-center">
    <div id="menuavatarOuter">
        @include('parts.avatar',['user'=>$user,'class'=>'menuavatar'])
    </div>
    <div class="list-group">
      <a href="{{ route('show_user_profile',['id'=>$user->user_id]) }}" class="list-group-item text-center"><i class="fa fa-user-circle-o fa-fw" aria-hidden="true"></i>プロフィール</a>
      @if(Auth::user()->id==$user->user_id)
      <a href="{{ route('show_user_matching',['id'=>$user->user_id]) }}" class="list-group-item text-center"><i class="fa fa-handshake-o fa-fw" aria-hidden="true"></i>マッチング</a>
      @endif
      <a href="{{ route('show_user_messages',['id'=>$user->user_id]) }}" class="list-group-item text-center"><i class="fa fa-envelope-o fa-fw" aria-hidden="true"></i>メッセージ</a>
      <a href="{{ route('show_user_items',['id'=>$user->user_id]) }}" class="list-group-item text-center"><i class="fa fa-camera fa-fw" aria-hidden="true"></i>マイログ</a>
      <a href="{{ route('show_user_favorites',['id'=>$user->user_id]) }}" class="list-group-item text-center"><i class="fa-fw fa fa-heart-o" aria-hidden="true"></i>お気に入り</a>
  </div>
  </div>
</div>
</div>

<a data-target="#modalSmallMenu" data-toggle="modal" >
    @include('parts.avatar',['user'=>$user,'class'=>'visible-xs avatarImageS'])
</a>

<div class="modal fade" id="modalSmallMenu" style="width:200px;left:10vw;top:70px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="list-group">
      <a href="{{ route('show_user_profile',['id'=>$user->user_id]) }}" class="list-group-item text-center"><i class="fa fa-user-circle-o fa-fw" aria-hidden="true"></i>プロフィール</a>
      @if(Auth::user()->id==$user->user_id)
      <a href="{{ route('show_user_matching',['id'=>$user->user_id]) }}" class="list-group-item text-center"><i class="fa fa-handshake-o fa-fw" aria-hidden="true"></i>マッチング</a>
      @endif
      <a href="{{ route('show_user_messages',['id'=>$user->user_id]) }}" class="list-group-item text-center"><i class="fa fa-envelope-o fa-fw" aria-hidden="true"></i>メッセージ</a>
      <a href="{{ route('show_user_items',['id'=>$user->user_id]) }}" class="list-group-item text-center"><i class="fa fa-camera fa-fw" aria-hidden="true"></i>マイログ</a>
      <a href="{{ route('show_user_favorites',['id'=>$user->user_id]) }}" class="list-group-item text-center"><i class="fa-fw fa fa-heart-o" aria-hidden="true"></i>お気に入り</a>
  </div>
            </div>
        </div>
    </div>
</div>