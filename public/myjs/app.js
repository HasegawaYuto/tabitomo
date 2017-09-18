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
            //$('#filecheck').append($('<img class="img-responsive" />').attr('src', e.target.result));
            var files = event.target.files;

            //for($i=0;$i<files[].length;$i++){
            //    thumbF.append('<div class="col-xs-6" style="background-color:blue;height:200px;padding:20px;"></div>');
            //}
            //$('#imageThumbnailField > div').each(function(divcnt){
            //    $(this).attr('id','photo' + divcnt);
            //});
            for (var i = 0, f; f = files[i]; i++) {
              $('#filecheck').append(i);
                var reader = new FileReader;
                reader.onload = (function(){
                    return function(e){
                        if(i % 2 == 0){
                              //thumbF.append('<div class="row">');
                              thumbF.append('<div class="col-xs-6" id="photo'+ i +'" style="background-color:blue;height:400px;padding:20px;"></div>');
                        }else{
                              thumbF.append('<div class="col-xs-6" id="photo'+ i +'" style="background-color:red;height:400px;padding:20px;"></div>');
                        }
                        $('#photo'+ i).append('<img class="img-responsive" src="'+ e.target.result +'" style="margin:20px;" />');
                        //if(i % 2 == 1){
                        //      thumbF.append('</div>');
                        //}
                    }
                })(f);//showPhotoInField(f);
                reader.readAsDataURL(f);
            }

            //function showPhotoInField(f){
            //        if(i % 2 == 0){
            //            thumbF.append('<div class="row">');
            //            thumbF.append('<div class="col-xs-6" id="photo'+i+'" style="background-color:blue;"></div>');
            //        }else{
            //        thumbF.append('<div class="col-xs-6" id="photo'+i+'"></div>');
            //        }
            //        $('#photo'+i).append($('<img class="img-responsive" />').attr('src', f.target.result));
            //        if(i % 2 == 1){
            //            thumbF.append('</div>');
            //        }
            //    }
                //reader.onload = (function(theFile) {
                    //return function (e) {
                      //if(i % 2 == 0){
                      //    thumbF.append('<div class="row">');
                      //    thumbF.append('<div class="col-xs-6" id="photo'+i+'" style="background-color:blue;"></div>');
                      //}else{
                      //thumbF.append('<div class="col-xs-6" id="photo'+i+'"></div>');
                      //}
                      //$('#photo'+i).append($('<img class="img-responsive" />').attr('src', e.target.result));
                      //if(i % 2 == 1){
                      //    thumbF.append('</div>');
                      //}
                        //var div = document.createElement('div');
                        //div.className = 'reader_file';
                        //div.innerHTML = '<div class="reader_title">' + encodeURIComponent(theFile.name) + '</div>';
                        //div.innerHTML += '<img class="reader_image" src="' + e.target.result + '" />';
                        //document.getElementById('list').insertBefore(div, null);
                  //  }
                //})(f);
            //}

            //thumbF.append('<div class="col-xs-12" style="background-color:red;" id="photo'+$idnum+'">');
                    //$('#imageThumbnailField > div').html('hogehogehoge');
            //thumbF.append('</div>');
                    //$('#imageThumbnailField > div')
            //$('#photo'+$idnum).append($('<img class="img-responsive" />').attr('src', this.result));
            //};
                // ファイル名表示
                //$('div#file_list').append($(this)[0].name+'<br />');
            //thumbF.append('<div class="col-xs-12" style="background-color:red;" id="photo1">');
            //$('#imageThumbnailField > div').html('hogehogehoge');
            //thumbF.append('</div>');
          //});
        });
        //thumbF.css('background-color','silver');
        //thumbF.css('height','400px');
    }
});
