<div class="col-sm-4 col-md-3 col-lg-3 hidden-xs ">
<div class="text-left panel panel-info">
  <div class="panel-heading overCut">
      @if($user->id != Auth::user()->id)
      @include('parts.follow_button',['user'=>$user])
      @endif
      <i class="fa fa-user-circle-o fa-fw" aria-hidden="true"></i>{{$user->nickname or '未設定'}}
  </div>
  <div class="panel-body text-center">
    <div id="menuavatarOuter">
        @include('parts.avatar',['user'=>$user,'class'=>'menuavatar'])
    </div>
    @include('parts.menu',['user'=>$user])
  </div>
</div>
</div>

<a data-target="#modalSmallMenu" data-toggle="modal" >
  <div class="avatarImageSOuter">
    @include('parts.avatar',['user'=>$user,'class'=>'visible-xs avatarImageS'])
  </div>
</a>

<div class="modal fade" id="modalSmallMenu" style="width:200px;left:10vw;top:70px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                @include('parts.menu',['user'=>$user])
            </div>
        </div>
    </div>
</div>