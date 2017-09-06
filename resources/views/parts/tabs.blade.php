<ul class="nav {{$class or 'nav-tabs'}}">
@foreach($tab_names as $key => $tab_name)
  @if($key+1==1)
    <li class="active"><a href="#tab{{$key+1}}" data-toggle="tab">{{$tab_name}}</a></li>
  @else
    <li><a href="#tab{{$key+1}}" data-toggle="tab">{{$tab_name}}</a></li>
  @endif
@endforeach
</ul>
