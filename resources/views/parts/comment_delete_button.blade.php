{!!Form::open(['route'=>['delete_comment',
'id'=>$scene->user_id,
'title_id'=>$scene->title_id,
'scene_id'=>$scene->scene_id,
'comment_id'=>$comment->comment_id,
'comment_user_id'=>$comment->TheUserID],'style'=>'display:inline;float:right;'])!!}
<button type="submit" class="btn btn-xs btn-default"><i class="fa fa-trash-o" aria-hidden="true"></i></button>

{!!Form::close()!!}
