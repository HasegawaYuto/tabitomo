{!!Form::open(['route'=>['delete_comment','comment_id'=>$comment->id],'style'=>'display:inline;float:right;'])!!}
<button type="submit" class="btn btn-xs btn-default"><i class="fa fa-trash-o" aria-hidden="true"></i></button>

{!!Form::close()!!}
