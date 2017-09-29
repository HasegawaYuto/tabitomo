@if($unit == 'title')
    <button type="submit" class="btn btn-warning btn-xs" name="favTitle" value="Y-{{$title->user_id}}-{{$title->title_id}}"><i class="fa fa-thumbs-up fa-lg" aria-hidden="true"></i></button>
@elseif($unit == 'scene')
    ファボ{{$data->scene_id}}
@elseif($unit=='friend')
    ファボ{{$id}}
@endif
