@if(isset($scene))
  @if(\Auth::user()->is_favoritesScene($scene->user_id,$scene->title_id,$scene->scene_id))
      {!!Form::open(['route'=>['unfavorite_scene','id'=>$scene->user_id,'title_id'=>$scene->title_id,'scene_id'=>$scene->scene_id],'style'=>'display:inline;'])!!}
      <button type="submit" class="btn btn-xs btn-default"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
        </button>
      {!!Form::close()!!}
  @else
      {!!Form::open(['route'=>['favorite_scene','id'=>$scene->user_id,'title_id'=>$scene->title_id,'scene_id'=>$scene->scene_id],'style'=>'display:inline;'])!!}
      <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></button>
      {!!Form::close()!!}
  @endif
@endif
