@if(isset($user->avatar_path)&&$user->avatar_path!='NULL')
<div  class="lazyload img-circle {{isset($class) ? $class : ''}}" data-bg="{{$user->avatar_path}}"></div>
@elseif(isset($user->snsImagePath))
<div  class="lazyload img-circle {{isset($class) ? $class : ''}}" data-bg="{{$user->snsImagePath}}"></div>
@else
<div  class="lazyload img-circle {{isset($class) ? $class : ''}}" data-bg="{{asset('noimage.png')}}"></div>
@endif