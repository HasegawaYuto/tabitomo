@extends('layouts.app')

@section('content')
<div class="container-fluid">
<div class="row">
@include('bodys.user_menu.contents_menu',['user'=>$user])

<div class="col-xs-12 col-sm-8 col-md-9 col-lg-6">
    <div class="panel panel-info">
        <div class="panel-heading text-center">
            <i class="fa fa-calendar fa-fw" aria-hidden="true"></i>マイプラン
        </div>
        <div class="panel-body">
            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#makePlan">新規作成</button>
            @if(isset($plans[0]))
            <?php
                function replaceDate($date){
                    $theday = new Carbon\Carbon($date);
                    return $theday->format('Y年m月d日');
                }
            ?>
            <table class="table">
	                    <thead>
		                    <tr>
			                    <th>タイトル</th>
			                    <th>期間</th>
	                       	</tr>
	                    </thead>
	                    <tbody>
	                    @foreach($plans as $plan)
		                    <tr>
			                    <td>
			                        <a href="{{ route('show_plan_detail',['id'=>$plan->user_id,'title_id'=>$plan->title_id]) }}">
			                            {{$plan->title}}
			                        </a>
			                    </td>
			                    <td>{{replaceDate($plan->firstday)}}～{{replaceDate($plan->lastday)}}</td>
		                    </tr>
                        @endforeach
                        </tbody>
	                </table>
            @endif
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="makePlan">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">新規プラン概要<button type="button" class="btn btn-primary btn-xs" data-dismiss="modal">閉じる</button></h4>
      </div>
      <div class="modal-body">
          {!! Form::open(['route'=>['make_plan','id'=>$user->id]]) !!}
          {!! csrf_field() !!}
          <div class="form-group">
              {!! Form::label('title','タイトル') !!}
              {!! Form::text('title',null,['class'=>'form-control','placeholder'=>'プランタイトル']) !!}
          </div>
          <?php
            $today = Carbon\Carbon::now()->format('Y年m月d日');
          ?>
          <label>期間</label>
          <div class="form-group form-inline">
          {!! Form::text('firstday',$today,['class'=>'form-control datepicker','id'=>'firstday0']) !!}
          <label>～</label>
          {!! Form::text('lastday',$today,['class'=>'form-control datepicker','id'=>'lastday0']) !!}
          </div>
          <div class="form-group">
              {!! Form::label('describe','概要') !!}
              {!! Form::textarea('describe',null,['rows'=>'5','class'=>'form-control','placeholder'=>'プラン概要、予算、必需品など']) !!}
          </div>
          {!! Form::submit('保存',['class'=>'btn btn-primary btn-xs']) !!}
          {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
@endsection
