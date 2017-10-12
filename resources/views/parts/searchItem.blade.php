<div class="col-xs-12">
<a class="btn btn-default" data-toggle="collapse" href="#collapseExample">
    条件を付けて探す
</a>
<div class="collapse" id="collapseExample">
<div class="panel panel-warning">
    <div class="panel-heading">
        探す
    </div>
    <ul class="nav nav-pills nav-stacked tabArea">
        <li class="active"><a href="#tab1" data-toggle="tab">キーワード</a></li>
        <li><a href="#tab2" data-toggle="tab">時期</a></li>
        <li><a href="#tab3" data-toggle="tab">地域</a></li>
        <li><a href="#tab4" data-toggle="tab">お気に入り数</a></li>
    </ul>
    {!! Form::open() !!}
    <div class="tab-content tabContents">
        <div class="tab-pane active" id="tab1">
            <div class="col-xs-10 col-xs-offset-1">
            <div class="form-group">
                {!! Form::label('keywords','キーワード') !!}
                {!! Form::text('keywords',null,['class'=>'form-control','placeholder'=>'キーワード']) !!}
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab2">時期検索</div>
        <div class="tab-pane" id="tab3">地域検索</div>
        <div class="tab-pane" id="tab4">評価検索</div>
    </div>
    {!! Form::submit('検索',['class'=>'btn btn-danger btn-block'])!!}
    {!!Form::close()!!}
</div>
</div>
</div>
