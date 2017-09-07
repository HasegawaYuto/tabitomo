@extends('layouts.app')

@section('content')
@include('bodys.user_menu.contents_menu')
<div class="col-xs-9">
    <div class="panel panel-info">
        <div class="panel-heading">
            {!! Link_to_route('show_user_items','マイログ',['id'=>1]) !!}　≫　{{$title_id}}
        </div>
{{$id}}
    </div>
</div>
@endsection
