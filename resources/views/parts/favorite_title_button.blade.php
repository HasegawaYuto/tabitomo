@if(\Auth::user()->is_favoritesTitle($title->title_id))
      {!!Form::open(['route'=>['unfavorite_title','title_id'=>$title->title_id],'style'=>'display:inline;'])!!}
      <button type="submit" class="btn btn-xs btn-default"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
        </button>
      {!!Form::close()!!}
@else
      {!!Form::open(['route'=>['favorite_title','title_id'=>$title->title_id],'style'=>'display:inline;'])!!}
      <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></button>
      {!!Form::close()!!}
@endif
