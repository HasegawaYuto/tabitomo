@if(isset($title))
  @if(\Auth::user()->is_favoritesTitle($title->user_id,$title->title_id))
      {!!Form::open(['route'=>['unfavorite_title','id'=>$title->user_id,'title_id'=>$title->title_id],'style'=>'display:inline;'])!!}
      {!!Form::submit('DISLIKE',['class'=>'btn btn-xs btn-default'])!!}
      {!!Form::close()!!}
  @else
      {!!Form::open(['route'=>['favorite_title','id'=>$title->user_id,'title_id'=>$title->title_id],'style'=>'display:inline;'])!!}
      {!!Form::submit('LIKE',['class'=>'btn btn-xs btn-danger'])!!}
      {!!Form::close()!!}
  @endif
@endif
