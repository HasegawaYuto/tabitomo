@if(isset($title))
{!! Form::open(['route'=>['title_delete','id'=>$title->user_id,'title_id'=>$title->title_id],'style'=>'display:inline;float:right;']) !!}
@endif
@if(isset($scene))
{!! Form::open(['route'=>['scene_delete','id'=>$scene->user_id,'title_id'=>$scene->title_id,'scene'=>$scene->scene_id],'style'=>'display:inline;float:right;']) !!}
@endif
{!! Form::submit('削除',['class'=>'btn btn-danger btn-xs']) !!}
{!! Form::close() !!}
