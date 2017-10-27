<?php
$ua=$_SERVER['HTTP_USER_AGENT'];
$browser=((strpos($ua,'iPhone')!==false)||(strpos($ua,'iPod')!==false)||(strpos($ua,'Android')!==false));
?>
@if($browser!='sp')
    <a href="https://maps.google.com/?daddr={{$scene->lat}},{{$scene->lng}}&ll={{$scene->lat}},{{$scene->lng}}&z=10" class="btn btn-danger btn-block">ここへ行く</a>
@else
    <a href="comgooglemaps://?daddr={{$scene->lat}},{{$scene->lng}}&directionsmode=driving" class="btn btn-danger col-xs-4"><i class="fa fa-car" aria-hidden="true"></i></a>
    <a href="comgooglemaps://?daddr={{$scene->lat}},{{$scene->lng}}&directionsmode=transit" class="btn btn-info col-xs-4"><i class="fa fa-subway" aria-hidden="true"></i></a>
    <a href="comgooglemaps://?daddr={{$scene->lat}},{{$scene->lng}}&directionsmode=walking" class="btn btn-warning col-xs-4"><i class="fa fa-male" aria-hidden="true"></i></a>
@endif