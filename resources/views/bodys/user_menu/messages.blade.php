@extends('layouts.app')

@section('content')
  @include('bodys.user_menu.contents_menu',['user'=>$user])

            メッセージ
@endsection
