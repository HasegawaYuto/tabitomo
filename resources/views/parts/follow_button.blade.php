@if(isset($user))
  @if(\Auth::user()->is_following($user->user_id))
      {!!Form::open(['route'=>['unfollow_user','follow_id'=>$user->user_id],'style'=>'display:inline;'])!!}
      {!!Form::submit('DISLIKE',['class'=>'btn btn-xs btn-default'])!!}
      {!!Form::close()!!}
  @else
      {!!Form::open(['route'=>['follow_user','follow_id'=>$user->user_id],'style'=>'display:inline;'])!!}
      {!!Form::submit('LIKE',['class'=>'btn btn-xs btn-danger'])!!}
      {!!Form::close()!!}
  @endif
@endif
