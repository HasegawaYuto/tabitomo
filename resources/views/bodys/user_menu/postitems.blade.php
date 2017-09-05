@extends('layouts.app')

@section('content')
<div class="row">
  @include('bodys.user_menu.contents_menu',['id'=>$id])
  <div class="col-xs-6">
    {!! Link_to_route('show_user_items','一覧',['id'=>$id],['class' => 'btn btn-danger']) !!}
  </div>
</div>
@endsection
