@extends('layouts.app')

@section('content')
<div class="container-fluid">
<div class="row">
  @include('bodys.user_menu.contents_menu',['user'=>$user])
  <div class="col-md-6">
    {!! Link_to_route('show_user_items','一覧',['id'=>$id],['class' => 'btn btn-danger']) !!}
  </div>
</div>
</div>
@endsection
