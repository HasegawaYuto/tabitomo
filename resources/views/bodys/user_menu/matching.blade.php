@extends('layouts.app')

@section('content')
<div class="row">
    @include('bodys.user_menu.contents_menu',['user'=>$user])
    <div class="col-xs-6">
        <div class="panel panel-info">
            <div class="panel-heading text-center">
                マッチング
            </div>
            <div class="panel-body">
                @include('parts.tabs',['tab_names'=>['ガイド','旅行者'],'class'=>'nav-tabs nav-justified','activetab'=>'1'])
                <div class="tab-content">
                    <div class="tab-pane active" id="tab1-1">
                      <div class="nav-tabs-outer">
                          <ul class="nav nav-pills js-tabs js-swipe">
                              <li class="active text-center"><a href="#swipetab1" data-toggle="tab">TAB1</a></li>
                              @for($i=2;$i<=3;$i++)
                                  <li class="text-center"><a href="#swipetab{{$i}}" data-toggle="tab">TAB{{$i}}</a></li>
                              @endfor
                            </ul>
                      </div>
                      <div class="tab-content">
                            @for($i=1;$i<=10;$i++)
                                <div class="tab-pane {{$i==1 ? 'active':''}}" id="swipetab{{$i}}">
                                    {{$i}}
                                </div>
                            @endfor
                      </div>
                    </div>
                    <div class="tab-pane" id="tab1-2">
                        aaaaa
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group" id="datepicker-inline">

    <div class="col-sm-9 form-inline">
        <div class="in-line" data-date="2010年10月26日"></div>
        <input type="text" id="my_hidden_input">
    </div>
</div>

<script>// detapicker
    $(function(){
        $('#datepicker-inline .in-line').datepicker({
            format: "yyyy年mm月dd日",
            language: "ja"
          });
    $('.in-line').on('changeDate', function() {
        $('#my_hidden_input').val(
            $('.in-line').datepicker('getFormattedDate')
        );
    });
});
</script>

<script>//nav scroll
$(function(){
  var ACTIVE_SELECTOR = '.nav-pills > .active';

  var $jsTabs = $('.js-tabs');
  var $jsTabsLi = $('.js-tabs li');

  var $jsSwipe = $('.js-swipe');
  var $scrollContainer = $('.nav-tabs-outer');

  var tabsLiLen = $jsTabsLi.length;
  var tabsLiWid = $jsTabsLi.eq(0).width();

  //タブエリアの横幅指定
  //$jsTabs.css('width',tabsLiWid * tabsLiLen);
  $jsTabs.css('width','400px');
  $('.nav-tabs-outer').css('overflow-x','scroll');
  $('.nav-tabs-outer .nav-pills li').css('display','block');
  $('.nav-tabs-outer .nav-pills li').css('width','100px');

  //スワイプイベント登録
  $jsSwipe.hammer().on('swipeleft',next);  //--------C
  $jsSwipe.hammer().on('swiperight',prev);

  function next() {
    tabManager($(ACTIVE_SELECTOR).next('li'));
  }
  function prev() {
    tabManager($(ACTIVE_SELECTOR).prev('li'));
  }                                        //--------C

  // 指定されたタブをカレントし要素にスクロールする
  function tabManager($nextTarget){
    $nextTarget.find('a').trigger('click');  //--------D

    if($nextTarget.index() != -1){
      $scrollContainer.scrollLeft($nextTarget.index() * tabsLiWid);  //--------E

    }
  }
});
</script>
@endsection
