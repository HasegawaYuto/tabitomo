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
                      <li>{!! link_to_route('show_guides', 'ガイドを探す') !!}</li>
                      <li>{!! link_to_route('show_travelers', '旅行者を探す') !!}</li>
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
                            <li>{!! link_to_route('show_user_profile', 'プロフィール',['id' => 1]) !!}</li>
                            <li role="separator" class="divider"></li>
                            <li>{!! link_to_route('show_user_matching', 'マッチング',['id' => 1]) !!}</li>
                            <li role="separator" class="divider"></li>
                            <li>{!! link_to_route('show_user_messages', 'メッセージ',['id' => 1]) !!}</li>
                            <li role="separator" class="divider"></li>
                            <li>{!! link_to_route('show_user_items', 'マイログ',['id' => 1]) !!}</li>
                            <li role="separator" class="divider"></li>
                            <li>{!! link_to_route('show_user_favorites', 'お気に入り',['id' => 1]) !!}</li>
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
