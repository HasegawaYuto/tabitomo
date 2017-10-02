@if(isset($scene))
  @if(\Auth::user()->is_favoritesScene($scene->user_id,$scene->title_id,$scene->scene_id))
      {!!Form::open(['route'=>['unfavorite_scene','id'=>$scene->user_id,'title_id'=>$scene->title_id,'scene_id'=>$scene->scene_id],'style'=>'display:inline;'])!!}
      {!!Form::submit('ファボはずす',['class'=>'btn btn-xs btn-danger'])!!}
      {!!Form::close()!!}
  @else
      {!!Form::open(['route'=>['favorite_scene','id'=>$scene->user_id,'title_id'=>$scene->title_id,'scene_id'=>$scene->scene_id],'style'=>'display:inline;'])!!}
      {!!Form::submit('ファボる',['class'=>'btn btn-xs btn-danger'])!!}
      {!!Form::close()!!}
  @endif
@endif
