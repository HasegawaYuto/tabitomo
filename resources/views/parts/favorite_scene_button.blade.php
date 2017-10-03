@if(isset($scene))
  @if(\Auth::user()->is_favoritesScene($scene->user_id,$scene->title_id,$scene->scene_id))
      {!!Form::open(['route'=>['unfavorite_scene','id'=>$scene->user_id,'title_id'=>$scene->title_id,'scene_id'=>$scene->scene_id],'style'=>'display:inline;'])!!}
      {!!Form::submit('DISLIKE',['class'=>'btn btn-xs btn-default'])!!}
      {!!Form::close()!!}
  @else
      {!!Form::open(['route'=>['favorite_scene','id'=>$scene->user_id,'title_id'=>$scene->title_id,'scene_id'=>$scene->scene_id],'style'=>'display:inline;'])!!}
      {!!Form::submit('LIKE',['class'=>'btn btn-xs btn-danger'])!!}
      {!!Form::close()!!}
  @endif
@endif
