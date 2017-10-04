{!!Form::open(['route'=>['delete_comment',
'id'=>$scene->user_id,
'title_id'=>$scene->title_id,
'scene_id'=>$scene->scene_id,
'comment_id'=>$comment->comment_id,
'comment_user_id'=>$comment->TheUserID],'style'=>'display:inline;float:right;'])!!}
{!!Form::submit('削除',['class'=>'btn btn-xs btn-default'])!!}
{!!Form::close()!!}
