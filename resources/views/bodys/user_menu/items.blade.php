@extends('layouts.app')

@section('content')
<div class="row">
    @include('bodys.user_menu.contents_menu',['id'=>$id])
    <div class="col-xs-6">
        <div class="panel panel-info">
            <div class="panel panel-heading text-center">
                マイログ
            </div>
                @include('parts.tabs',['tab_names'=>['一覧','アップロード'],'class'=>'nav-tabs nav-justified'])
                <div class="tab-content">
                    <div class="tab-pane active" id="tab1">
                        ページネーション
                        <div class="panel panel-primary">
                            <div class="panel panel-heading">
                                旅のタイトル
                            </div>
                            <div class="panel panel-body">
                                <div class="col-xs-4">
                                    サムネイル
                                </div>
                                <div class="col-xs-8">
                                    bbbb
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab2">
                        <div class="panel panel-body">
                            <div class="col-xs-8 col-xs-offset-2">
                                {!! Form::open(['files'=>true]) !!}
                                    @include('parts.form',['hidden'=>['var'=>'title_id','val'=>'1']])
                                    @include('parts.form',['hidden'=>['var'=>'title_id','val'=>'1']])
                                    @include('parts.form',['text'=>['label'=>'タイトル','var'=>'title','option'=>['placeholder'=>'例〇〇山への旅20XX春']]])
                                    @include('parts.form',['text'=>['label'=>'シーン','var'=>'scene','option'=>['placeholder'=>'例〇〇旅館の夕食']]])
                                    <div class="form-group">
                                    {!! Form::file('image') !!}
                                    </div>
                                    @include('parts.form',['textarea'=>['option'=>['placeholder'=>'コメント','rows'=>'3'],'label'=>'ひとこと','var'=>'comment']])
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
