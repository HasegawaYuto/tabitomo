//////////  アバター変更時
$(function(){
    if($('#menuavatarBeforeChangeArea').length){
        $menuABC = $('#menuavatarBeforeChangeArea');
        $menuABC.css('height',$menuABC.css('width'));
        $menuABC.css({'background-position':'center',
                  'background-repeat':'no-repeat',
                  'background-size':'cover'
            });
    }
    if($('#avatarBeforeChangeArea').length && $('#avatarAfterChangeArea').length){
    $ABC = $('#avatarBeforeChangeArea');
    $AAC = $('#avatarAfterChangeArea');
    //$menuABC = $('#menuavatarBeforeChangeArea');
    $ABC.css('height',$ABC.css('width'));
    //$menuABC.css('height',$menuABC.css('width'));
    $ABC.css({'background-position':'center',
              'background-repeat':'no-repeat',
              'background-size':'cover'
        });
    $AAC.css('height',$ABC.css('width'));
    $AAC.css({'background-position':'center',
              'background-repeat':'no-repeat',
              'background-size':'cover'
        });
    //$menuABC.css({'background-position':'center',
    //          'background-repeat':'no-repeat',
    //          'background-size':'cover'
    //    });
$('#avatarForm').on('change', 'input[type="file"]', function(e) {
            var file = e.target.files[0],
                reader = new FileReader(),
                $preview = $("#avatarAfterChangeArea");
                //t = this;
            if(file.type.indexOf("image") < 0){
              return false;
            }
            reader.onload = (function(file) {
              return function(e) {
                $preview.empty();
                $preview.css('background-image','url("' + e.target.result + '")')
              };
            })(file);

            reader.readAsDataURL(file);
          });
}});
////////////////////////////////////////////////////////////////////////
/////////////// グーグルマップの初期設定
/*
$(function(){
    $mapSetAreaHeight = $('#mapSetArea').width()  ;
    $('#mapSetArea').css('height',$mapSetAreaHeight);
    function mapInit() {
    var centerPosition = new google.maps.LatLng(38.000, 138.000);
    var option = {
        zoom : 4,
        center : centerPosition,
        mapTypeControlOptions: { mapTypeIds: ['noText', google.maps.MapTypeId.ROADMAP] },
        mapTypeControl: false,
        //fullscreenControl: false,
        streetViewControl: false,
        scrollwheel: true,
        zoomControl: true,
    };
    //地図本体描画
    var googlemap = new google.maps.Map(document.getElementById("mapSetArea"), option);

    var styleOptions = [{
        featureType: 'all',
        elementType: 'labels',
        stylers: [{ visibility: 'off' }]
      },
      {
          featureType: 'road',
          elementType: 'road.highway',
          stylers: [{ visibility: 'off' }]
        },
      {
          featureType: 'road',
          elementType: 'road.local',
          stylers: [{ visibility: 'off' }]
        },
      {
          featureType: 'road',
          elementType: 'road.arterial',
          stylers: [{ visibility: 'off' }]
        },
      {
          featureType: 'transit',
          elementType: 'transit.line',
          stylers: [{ visibility: 'off' }]
        },
      {
          featureType: 'administrative',
          elementType: 'geometry',
          stylers: [{ visibility: 'on' },{ weight:2 }]
        },
      {
          featureType: 'administrative',
          elementType: 'labels',
          stylers: [{ visibility: 'on' }]
        }
    ];
    var styledMapOptions = { name: '文字なし' }
    var lopanType = new google.maps.StyledMapType(styleOptions, styledMapOptions);
    googlemap.mapTypes.set('noText', lopanType);
    googlemap.setMapTypeId('noText');

    //var marker = new google.maps.Marker({
    //                position: googlemap.getCenter(),
    //                map: googlemap
    //            });

    google.maps.event.addListener(googlemap, 'click', function (e) {
                    //marker.position = e.latLng;
                    //googlemap.getPosition(loc);
                    $("#latitude").val(e.latLng.lat());
                    $("#longitude").val(e.latLng.lng());
                    //marker.setMap(googlemap);
                });

}
 
    mapInit();
});
*/
/////////////////////////////////////////////////////////////////////////////////////////
/////生年月日のうるう年設定
$(function(){
    if($('#monthSelectBox').length && $('#daySelectBox').length && $('#yearSelectBox').length){
    var $month = $('#monthSelectBox');
   var $day = $('#daySelectBox');
   var $year = $('#yearSelectBox');
   var originaldays = $day.html();

   $month.change(leapcheck);
   $year.change(leapcheck);

     function leapcheck(){
       var valy = $year.val();
       var valm = $month.val();
       $day.html(originaldays).find('option').each(function() {
           if( valy != "0000" ){
               var vald = $(this).data('val');
               if(valm == "04" || valm == "06" || valm == "09" || valm == "11"){
                   if (vald == "31") {
                       $(this).not(':last-child').remove();
                     }
               } else if(valm == "02"){
                   if (vald == "31" || vald == "30") {
                       $(this).not(':last-child').remove();
                     }
                   if ( valy % 4 == 0 ){
                       if(valy % 100 == 0){
                           if( valy % 400 != 0){
                               if(vald == "29"){
                                   $(this).not(':last-child').remove();
                                 }
                             }
                         }
                   } else {
                       if(vald == "29"){
                           $(this).not(':last-child').remove();
                       }
                   }
               }
       }});
   }
}});
///////////////////////////////////////////////////
/////////都道府県セレクトボックス
$(function(){
  if($('#cityselect').length && $('#prefselect').length){
var $city = $('#cityselect'); //都道府県の要素を変数に入れます。
var original = $city.html(); //後のイベントで、不要なoption要素を削除するため、オリジナルをとっておく

//地方側のselect要素が変更になるとイベントが発生
$('#prefselect').change(function() {

  //選択された地方のvalueを取得し変数に入れる
  var val1 = $(this).val();

  //削除された要素をもとに戻すため.html(original)を入れておく
  $city.html(original).find('option').each(function() {
    var val2 = $(this).data('val'); //data-valの値を取得

    //valueと異なるdata-valを持つ要素を削除
    if (val1 != val2) {
      $(this).not(':first-child').remove();
    }

  });

  //地方側のselect要素が未選択の場合、都道府県をdisabledにする
  if ($(this).val() == "") {
    $city.attr('disabled', 'disabled');
  } else {
    $city.removeAttr('disabled');
  }

});
}});
//////////////////////////////////////////////////////////////////////////////
$(function(){
  if($('.datepicker').length && $('#firstday').length && $('#lastday').length){
    //$('.class-sunday').css('color','red !important');
    //$('.class-saturday').css('color','blue !important');
    $('#firstday').datepicker({
        format: "yyyy年mm月dd日",
        language: "ja",
        daysOfWeekHighlighted: "0,6",
        useCurrent: false
        }
    );
    $('#lastday').datepicker({
        format: "yyyy年mm月dd日",
        language: "ja",
        daysOfWeekHighlighted: "0,6",
        useCurrent: false
        }
    );
    var $FD = $('#firstday');
    var $LD = $('#lastday');
    $FD.change(function(){
        //var theday = $FD.val();
        $LD.val($FD.datepicker('getFormattedDate'));
        $LD.datepicker('update');
    });
    //$('.class-sunday').css('color','red');
    $('.class-saturday').css('color','blue');
}});
////////////////////////////////////////////////////////////////////////////////////////
$(function(){
    if($('#theday').length){
        $('#firstday').change(selectTheDateSet);
        $('#lastday').change(selectTheDateSet);
/////////////////////////////////////
        function selectTheDateSet(){
            var $firstday = $('#firstday').val();
            var $lastday = $('#lastday').val();
            var $firstdayParse = dateToParse($firstday);
            var $lastdayParse = dateToParse($lastday);
            var $difference = ($lastdayParse - $firstdayParse)/1000/60/60/24;
            var $oneday = 1000*60*60*24;
            $('#theday').empty();
            for($d=0;$d<=$difference;$d++){
                var $optionday = new Date($firstdayParse + ($oneday * $d));
                var $optionYear = $optionday.getFullYear();
                var $optionMonth = $optionday.getMonth() + 1;
                var $optionDay = $optionday.getDate();
                $('#theday').append('<option value="'+$optionYear+'-'+("0"+$optionMonth.toString()).slice(-2)+'-'+("0"+$optionDay.toString()).slice(-2)+'">'+$optionYear+'年'+("0"+$optionMonth.toString()).slice(-2)+'月'+("0"+$optionDay.toString()).slice(-2)+'日'+'</option>');
            }
            //$('#theday').append('<option value="01" data-val="01">'+$lastdayParse+'</option>');
            //$('#theday').append('<option value="01" data-val="01">'+$d+'</option>');
        }
//////////////////////////////////////////////////////////////////
        function dateToParse(date){
            date=date.replace('月','/');
            date=date.replace('年','/');
            date=date.replace('日','');
            date=Date.parse(date);
            return date;
        }
    }
});
/////////////////////////////////////////////////////////////////////////////////////
$(function(){
    thumbF = $('#imageThumbnailField');
    if(thumbF.length && $('#myLogForm').length){
        //thumbF.css('height','400px');
        $('#myLogForm').on('change', 'input[type="file"]', function(event) {
            thumbF.css('background-color','silver');
            thumbF.empty();
            var files = event.target.files;
            for (var i = 0, f; f = files[i]; i++) {
                var reader = new FileReader;
                reader.onload = (function(){
                    return function(e){
                        var imageAppend = $('<div class="col-xs-6" />').append('<img class="img-responsive" src="'+ e.target.result +'" style="margin:10px;" />');
                        thumbF.append(imageAppend);
                        //$('#photo'+ i).append('<img class="img-responsive" src="'+ e.target.result +'" style="margin:10px;" />');
                    }
                })(f);
                reader.readAsDataURL(f);
            }
        });
    }
});
