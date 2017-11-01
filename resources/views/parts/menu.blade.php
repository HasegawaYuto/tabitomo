<div class="list-group">
      <a href="{{ route('show_user_profile',['id'=>$user->id]) }}" class="list-group-item text-center"><i class="fa fa-user-circle-o fa-fw" aria-hidden="true"></i>プロフィール</a>
      @if(Auth::user()->id==$user->id)
      <a href="{{ route('show_user_matching',['id'=>$user->id]) }}" class="list-group-item text-center"><i class="fa fa-handshake-o fa-fw" aria-hidden="true"></i>マッチング</a>
      <a href="{{ route('show_user_plans',['id'=>$user->id]) }}" class="list-group-item text-center"><i class="fa fa-calendar fa-fw" aria-hidden="true"></i>マイプラン</a>
      @endif
      <a href="{{ route('show_user_messages',['id'=>$user->id]) }}" class="list-group-item text-center"><i class="fa fa-envelope-o fa-fw" aria-hidden="true"></i>メッセージ
      @if(\Auth::user()->newMessageHas())
        <button class="btn btn-xs btn-danger " type="button">新着</button>
        @endif</a>
      <a href="{{ route('show_user_items',['id'=>$user->id]) }}" class="list-group-item text-center"><i class="fa fa-camera fa-fw" aria-hidden="true"></i>マイログ</a>
      <a href="{{ route('show_user_favorites',['id'=>$user->id]) }}" class="list-group-item text-center"><i class="fa-fw fa fa-heart-o" aria-hidden="true"></i>お気に入り</a>
  </div>