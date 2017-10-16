@extends('layouts.app')

@section('content')
        <?php
            function replaceDate($date){
                $part = explode('-',$date);
                return $part[0].'年'.(int)$part[1].'月'.(int)$part[2].'日';
            }
            function getAge($date){
                return Carbon\Carbon::parse($date)->age.'歳';
            }
        ?>
        @if(isset($recruitments[0]))
        @include('parts.searchPlan')
        <div>
        {!! $recruitments->render() !!}
        </div>
        @foreach($recruitments as $key => $recruitment)
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
        <div class="panel panel-success">
          <div class="panel-heading">
                  〆{{$recruitment->limitdate !=0 ? replaceDate($recruitment->limitdate):'なし'}}
          </div>
          <div class="panel-body">
                  <?php $theuser=$recruituser[$key]; ?>
                  <a href="{{route('show_user_profile',['id'=>$theuser->user_id])}}" class="black">
                  <div class="GuideImageOuter">
                      @include('parts.avatar',['user'=>$theuser,'class'=>'GuideImage'])
                  </div>
                  <div class="GuideProfile black overCut">
                  {{$theuser->nickname==""?'ニックネーム未設定':$theuser->nickname}}<br>
                  {{$theuser->sex=="" ? '性別未設定':$theuser->sex}}<br>
                  {{$theuser->birthday=="" ? '年齢未設定':getAge($theuser->birthday)}}<br>
                  </div>
                  </a>
                  <div class="wrap col-xs-12 Info">{{$recruitment->contents}}</div>
                  <div class="recruitmentMap googlemapSizeM" id="recruitmentMap{{$key}}"></div>
                  <input type="hidden" value="{{$recruitment->lat}}" id="recruitmentLat{{$key}}">
                  <input type="hidden" value="{{$recruitment->lng}}" id="recruitmentLng{{$key}}">
                  <input type="hidden" value="{{$recruitment->radius}}" id="recruitmentRadius{{$key}}">
          </div>
          @include('parts.guide_button',['recruitment'=>$recruitment])
        </div>
        </div>
        @endforeach
        @else
          <div class="center jumbotron">
          @if(Request::is('guides/search'))
              @include('parts.searchPlan')
          @endif
              <div class="text-center">
              募集はありません
              </div>
          </div>
        @endif
@endsection
