@extends('layouts.app')

@section('content')
<div class="row">
    @include('bodys.user_menu.contents_menu',['id'=>$id])
    <div class="col-xs-6">
        <div class="panel panel-info">
            <div class="panel panel-heading text-center">
                マイログ
            </div>
                @include('parts.tabs',['tab_names'=>['一覧','アップロード'],'class'=>'nav-tabs nav-justified','activetab'=>$activetab])
                <div class="tab-content">
                    <div class="tab-pane {{$activetab == 1 ? 'active' : ''}}" id="tab1">
                        ページネーション
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                旅のタイトル
                            </div>
                            <div class="panel-body">
                                <div class="col-xs-4" style="height:60px;">
                                    サムネイル
                                </div>
                                <div class="col-xs-8">
                                    bbbb
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane {{$activetab == 2 ? 'active' : ''}}" id="tab2">
                        <div class="panel panel-body">
                            <div class="col-xs-8 col-xs-offset-2">
                                {!! Form::open(['route'=>['create_items',1],'files'=>true]) !!}
                                    {!! Form::hidden('title_id',$title_id) !!}
                                    {!! Form::hidden('scene_id',$scene_id) !!}
                                    {{ Form::hidden('title_id',$title_id) }}
                                    {{ Form::hidden('scene_id',$scene_id) }}
                                    <div class="form-group">
                                        {!! Form::label('title','タイトル') !!}
                                        {!! Form::text('title',null,['class'=>'form-control','placeholder'=>'例〇〇山への旅20XX春']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('date','期間') !!}
                                    カレンダーとか呼び出せたらいいのに
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('scene','シーン') !!}
                                        {!! Form::text('scene',null,['class'=>'form-control','placeholder'=>'例〇〇旅館の夕食']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('date','日付') !!}
                                    上記「期間」の中から選択できるようにしたい
                                    </div>
                                    <div class="form-group">
                                        {!! Form::file('image') !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('comment','コメント') !!}
                                        {!! Form::textarea('comment',null,['class'=>"form-control",'placeholder'=>"ひとこと",'rows'=>'3']) !!}
                                    </div>
                                    {!! Form::submit('&nbsp;保存&nbsp;',['class'=>'btn btn-info','name'=>'fin','value'=>'Fin']) !!}
                                    {!! Form::submit('続ける',['value'=>'Con','class'=>'btn btn-warning','name'=>'con']) !!}
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
