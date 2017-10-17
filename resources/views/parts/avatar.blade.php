@if(isset($user->data))
<?php
    $mime = $user->mime;
    $dataImage = base64_encode($user->data);
?>
<div  class="lazyload img-circle {{isset($class) ? $class : ''}}" data-bg="data:{{$mime}};base64,{{$dataImage}}"></div>
@elseif($user->snsImagePath)
<div  class="lazyload img-circle {{isset($class) ? $class : ''}}" data-bg="{{$user->snsImagePath}}"></div>
@else
<div  class="lazyload img-circle {{isset($class) ? $class : ''}}" data-bg="{{asset('noimage.png')}}"></div>
@endif