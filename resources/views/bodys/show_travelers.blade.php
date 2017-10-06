@extends('layouts.app')

@section('content')
      <?php
          function replaceDate($date){
              $part = explode('-',$date);
              return $part[0].'年'.(int)$part[1].'月'.(int)$part[2].'日';
          }
      ?>
      @if(isset($recruitments))
      <div>
      {!! $recruitments->render() !!}
      </div>
      @foreach($recruitments as $key => $recruitment)
      <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
      <div class="panel panel-warning">
        <div class="panel-heading">
                〆{{$recruitment->limitdate !=0 ? replaceDate($recruitment->limitdate):'なし'}}
        </div>
        <div class="panel-body">
                <div class="wrap col-xs-12">{{$recruitment->contents}}</div>
                <div class="recruitmentMap" id="recruitmentMap{{$key}}" style="width:80%;height:130px;"></div>
                <input type="hidden" value="{{$recruitment->lat}}" id="recruitmentLat{{$key}}">
                <input type="hidden" value="{{$recruitment->lng}}" id="recruitmentLng{{$key}}">
                <input type="hidden" value="{{$recruitment->radius}}" id="recruitmentRadius{{$key}}">
        </div>
        @include('parts.guide_button',['recruitment'=>$recruitment])
      </div>
      </div>
      @endforeach
      @endif
@endsection
