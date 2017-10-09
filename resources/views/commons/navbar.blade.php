<header>
    <nav class="navbar navbar-inverse navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">旅とも</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-left">
                  <li>{!! link_to_route('show_items', '旅先一覧') !!}</li>
                  @if(Auth::check())
                      <li>{!! link_to_route('show_guides', 'ガイド募集') !!}</li>
                      <li>{!! link_to_route('show_travelers', 'ゲスト募集') !!}</li>
                  @endif
              </ul>
                <ul class="nav navbar-nav navbar-right">
                    @if(!Auth::check())
                    <li>{!! link_to_route('signup.get', '新規登録') !!}</li>
                    <li>{!! link_to_route('login.get', 'ログイン') !!}</li>
                    @else
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{Auth::user()->name}}<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php $id=AUth::user()->id ?>
                            <li><a href="{{ route('show_user_profile',['id'=>$id]) }}" class="list-group-item noborder"><i class="fa fa-user-circle-o fa-fw" aria-hidden="true"></i>プロフィール</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ route('show_user_matching',['id'=>$id]) }}" class="list-group-item noborder"><i class="fa fa-handshake-o fa-fw" aria-hidden="true"></i>マッチング</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ route('show_user_messages',['id'=>$id]) }}" class="list-group-item noborder"><i class="fa fa-envelope-o fa-fw" aria-hidden="true"></i>メッセージ</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ route('show_user_items',['id'=>$id]) }}" class="list-group-item noborder"><i class="fa fa-camera fa-fw" aria-hidden="true"></i>マイログ</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ route('show_user_favorites',['id'=>$id]) }}" class="list-group-item noborder"><i class="fa-fw fa fa-heart-o" aria-hidden="true"></i>お気に入り</a></li>
                            <li role="separator" class="divider"></li>
                            <li>{!! link_to_route('logout.get', 'ログアウト') !!}</li>
                        </ul>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</header>
