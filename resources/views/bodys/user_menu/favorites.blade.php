@extends('layouts.app')

@section('content')
<div class="row">
    @include('bodys.user_menu.contents_menu',['id'=>$id])
    <div class="col-xs-6">
        <div class="panel panel-info">
            <div class="panel panel-heading text-center">
                お気に入り
            </div>
                @include('parts.tabs',['tab_names'=>['シーン','ユーザー'],'class'=>'nav-tabs nav-justified','activetab'=>$activetab])
                <div class="tab-content">
                    <div class="tab-pane {{$activetab == 1 ? 'active' : ''}}" id="tab1-1">
                          あ
                    </div>
                    <div class="tab-pane {{$activetab == 2 ? 'active' : ''}}" id="tab1-2">
                        <div class="panel-body">
                              @include('parts.tabs',['tab_names'=>['ゲスト','ガイド'],'class'=>'nav-tabs','activetab'=>'1','nest'=>'2','class'=>'nav-pills'])
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
