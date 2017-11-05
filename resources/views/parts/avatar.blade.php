@if(isset($user->avatar_path)&&$user->avatar_path!='NULL')
<div  class="img-circle {{isset($class) ? $class : ''}}" style="background-image:url('{{$user->avatar_path}}');"></div>
@elseif(isset($user->snsImagePath))
<div  class="img-circle {{isset($class) ? $class : ''}}" style="background-image:url('{{$user->snsImagePath}}');"></div>
@else
<div  class="img-circle {{isset($class) ? $class : ''}}" style="background-image:url('{{asset('noimage.png')}}');"></div>
@endif