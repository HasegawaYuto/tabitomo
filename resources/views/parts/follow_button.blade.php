@if(isset($user))
  @if(\Auth::user()->is_following($user->id))
      {!!Form::open(['route'=>['unfollow_user','follow_id'=>$user->id],'style'=>'display:inline;'])!!}
      <button type="submit" class="btn btn-xs btn-default"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
        </button>
      {!!Form::close()!!}
  @else
      {!!Form::open(['route'=>['follow_user','follow_id'=>$user->id],'style'=>'display:inline;'])!!}
      <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></button>
      {!!Form::close()!!}
  @endif
@endif
