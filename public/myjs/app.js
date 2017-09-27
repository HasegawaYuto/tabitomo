$(function(){
    if($('.imgPhotos').length){
        var $imgPhotos = $('.imgPhotos');
        $imgPhotos.css('max-height','20vh');
    }
});
//////////////////////////////////////////////////
$(function(){
    if($('#fixScene0').length){
        $('#fixScene0').on('shown.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var oldScene = button.data('scene');
            var oldLat = button.data('lat');
            var oldLng = button.data('lng');
            var oldScore = button.data('score');
            var oldComment = button.data('comment');
            var oldTheday = button.data('theday');
            $('#NewScene0').val(oldScene);
            $('#ido0').val(oldLat);
            $('#keido0').val(oldLng);
            $('#oldScore').val(oldScore);
            $('#edittheday0').val(oldTheday);
            $('#comment0').val(oldComment);
            if($('#editRateField0').length){
                    $.fn.raty.defaults.path = "/raty/lib/images";
                    rateF = $('#editRateField0');
                        rateF.raty({score: parseInt($('#oldScore').val())});
                        //rateF.raty();
            }
            if($('#editPhotoSpotSetArea0').length){
                var $pSS = $('#editPhotoSpotSetArea0');
                $pSS.css('height','40vh');
        //////////////////////////////////////////////
                mapInit();
        ///////////////////////////
                function mapInit() {//
                if($('#ido0').val()!="" && $('#keido0').val()!=""){
                    var centerPosition = {lat: parseFloat($('#ido0').val()), lng: parseFloat($('#keido0').val())};
                }else{
                    var centerPosition = {lat: 36, lng: 136};
                }
                if($('#mapzoom0').val()!=""){
                    var mapzoom = parseInt($('#mapzoom0').val());
                }else{
                    var mapzoom = 6;
                }
                var option = {//
                    zoom : mapzoom,//
                    center : centerPosition,//
                    mapTypeId: google.maps.MapTypeId.TERRAIN,//
                    //mapTypeControlOptions: { mapTypeIds: ['noText', google.maps.MapTypeId.ROADMAP] },
                    mapTypeControl: false,//
                    //fullscreenControl: false,
                    streetViewControl: false,//
                    scrollwheel: true,//
                    zoomControl: true,//
                };
                var googlemap = new google.maps.Map(document.getElementById("editPhotoSpotSetArea0"), option);
        /////////////////////////////////////////////////
                var marker = new google.maps.Marker({
                                position: centerPosition,
                                map: googlemap
                            });
        //////////////////////////////////////////////////////////
                google.maps.event.addListener(googlemap, 'click', function (e) {
                                marker.position = e.latLng;
                                //googlemap.getPosition(loc);
                                //$("#photoSpot").val(e.latLng.lat()+':'+e.latLng.lng());
                                $("#ido0").val(e.latLng.lat());
                                $("#keido0").val(e.latLng.lng());
                                marker.setMap(googlemap);
                                googlemap.panTo(new google.maps.LatLng(e.latLng.lat(), e.latLng.lng()));
                                //$("#mapzoom"+no).val(googlemap.getZoom());
                            });

                google.maps.event.addDomListener(window, "resize", function() {
        	           var center = marker.getPosition();//googlemap.getCenter();
        	            google.maps.event.trigger(googlemap, "resize");
        	             googlemap.setCenter(center);
                       //$("#mapzoom"+no).val(googlemap.getZoom());
                  });
            }

                //mapInit();
            }
    });
  }
});
//////////////////////////////////////////////////////
$(function(){
    if($('.showRaty').length){
        $.fn.raty.defaults.path = "/raty/lib/images";
        var $showRatyCnt = $('.showRaty').length;
        for($i=0;$i<$showRatyCnt;$i++){
            $('#showRatyDiv'+$i).raty({ readOnly: true, score: parseInt($('#showRaty'+$i).val()) });
        }
    }
});
////////////////////////////////////////////////////////////////
$(function(){
    if($('.showRatyAve').length){
        $.fn.raty.defaults.path = "/raty/lib/images";
        var $showRatyAveCnt = $('.showRatyAve').length;
        //for($i=0;$i<$showRatyAveCnt;$i++){
            $('#showRatyAveDiv').raty({ readOnly: true, score: parseInt($('#showRatyAve').val()*2)/2 });
        //}
    }
});
////////////////////////////////////////////////////////////////////////
$(function(){
    if($('.googlemapSpot').length){
        var $gmScnt = $('.googlemapSpot').length;
        for($i=0;$i<$gmScnt;$i++){
        //$('.googlemapSpot').each(function(){
            var $latval = parseFloat($('#googlemapLat'+$i).val());
            var $lngval = parseFloat($('#googlemapLng'+$i).val());
            var centerPosition = {lat: $latval, lng: $lngval};
            var googlemap = new google.maps.Map(document.getElementById("googlemapSpotID"+$i),
            //var googlemap = new google.maps.Map($(this),
                    {
                      zoom : 8,
                      center : centerPosition,
                      mapTypeId: google.maps.MapTypeId.TERRAIN,//
                      mapTypeControl: false,//
                      fullscreenControl: true,
                      streetViewControl: false,//
                      scrollwheel: true,//
                      zoomControl: true
                    });
            var marker = new google.maps.Marker({
                            position: centerPosition,
                            map: googlemap
                        });

            google.maps.event.addDomListener(window, "resize", function() {
    	           //var center = marker.getPosition();//googlemap.getCenter();
    	            google.maps.event.trigger(googlemap, "resize");
    	             googlemap.setCenter(centerPosition);
                   //$("#mapzoom").val(googlemap.getZoom());
              });
        }
        //});
            //var centerPosition = {lat: 36, lng: 136};
            //var option = {//
                    //zoom : mapzoom,//
                    //center : centerPosition,//
                    //mapTypeId: google.maps.MapTypeId.TERRAIN,//
                    //mapTypeControlOptions: { mapTypeIds: ['noText', google.maps.MapTypeId.ROADMAP] },
                    //mapTypeControl: false,//
                    //fullscreenControl: false,
                    //streetViewControl: false,//
                    //scrollwheel: true,//
                    //zoomControl: true,//
                //};
                //var googlemap = new google.maps.Map($(this), option);
        //mapInit();
///////////////////////////
/*
        function mapInit() {//
        if($('#ido').val()!="" && $('#keido').val()!=""){
            console.log($('#keido').val());
            var centerPosition = {lat: parseFloat($('#ido').val()), lng: parseFloat($('#keido').val())};
        }else{
            var centerPosition = {lat: 36, lng: 136};
        }
        if($('#mapzoom').val()!=""){
            var mapzoom = parseInt($('#mapzoom').val());
        }else{
            var mapzoom = 6;
        }
        var option = {//
            zoom : mapzoom,//
            center : centerPosition,//
            mapTypeId: google.maps.MapTypeId.TERRAIN,//
            //mapTypeControlOptions: { mapTypeIds: ['noText', google.maps.MapTypeId.ROADMAP] },
            mapTypeControl: false,//
            //fullscreenControl: false,
            streetViewControl: false,//
            scrollwheel: true,//
            zoomControl: true,//
        };
        var googlemap = new google.maps.Map(document.getElementById("photoSpotSetArea"), option);
/////////////////////////////////////////////////
        var marker = new google.maps.Marker({
                        position: centerPosition,
                        map: googlemap
                    });
//////////////////////////////////////////////////////////
        google.maps.event.addListener(googlemap, 'click', function (e) {
                        marker.position = e.latLng;
                        //googlemap.getPosition(loc);
                        //$("#photoSpot").val(e.latLng.lat()+':'+e.latLng.lng());
                        $("#ido").val(e.latLng.lat());
                        $("#keido").val(e.latLng.lng());
                        marker.setMap(googlemap);
                        googlemap.panTo(new google.maps.LatLng(e.latLng.lat(), e.latLng.lng()));
                        $("#mapzoom").val(googlemap.getZoom());
                    });

        google.maps.event.addDomListener(window, "resize", function() {
	           var center = marker.getPosition();//googlemap.getCenter();
	            google.maps.event.trigger(googlemap, "resize");
	             googlemap.setCenter(center);
               $("#mapzoom").val(googlemap.getZoom());
          });
        $('#logtabs a').on('shown.bs.tab', function(){
              var center = marker.getPosition();//
	            google.maps.event.trigger(googlemap, 'resize');
              //var center = marker.getPosition();//
              googlemap.setCenter(center);
              $("#mapzoom").val(googlemap.getZoom());
	            //return false;
          });
    }

        //mapInit();
    */
    }
});
//////////  アバター変更時
$(function(){
    if($('#avatarBeforeChangeArea').length && $('#avatarAfterChangeArea').length){
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
$(function(){
    if($('#createPhotoSpotSetArea0').length){
        var $pSS = $('#createPhotoSpotSetArea0');
        $pSS.css('height','40vh');
//////////////////////////////////////////////
        mapInit();
///////////////////////////
        function mapInit() {//
        if($('#ido0').val()!="" && $('#keido0').val()!=""){
            var centerPosition = {lat: parseFloat($('#ido0').val()), lng: parseFloat($('#keido0').val())};
        }else{
            var centerPosition = {lat: 36, lng: 136};
        }
        if($('#mapzoom0').val()!=""){
            var mapzoom = parseInt($('#mapzoom0').val());
        }else{
            var mapzoom = 6;
        }
        var option = {//
            zoom : mapzoom,//
            center : centerPosition,//
            mapTypeId: google.maps.MapTypeId.TERRAIN,//
            //mapTypeControlOptions: { mapTypeIds: ['noText', google.maps.MapTypeId.ROADMAP] },
            mapTypeControl: false,//
            //fullscreenControl: false,
            streetViewControl: false,//
            scrollwheel: true,//
            zoomControl: true,//
        };
        var googlemap = new google.maps.Map(document.getElementById("createPhotoSpotSetArea0"), option);
/////////////////////////////////////////////////
        var marker = new google.maps.Marker({
                        position: centerPosition,
                        map: googlemap
                    });
//////////////////////////////////////////////////////////
        google.maps.event.addListener(googlemap, 'click', function (e) {
                        marker.position = e.latLng;
                        //googlemap.getPosition(loc);
                        //$("#photoSpot").val(e.latLng.lat()+':'+e.latLng.lng());
                        $("#ido0").val(e.latLng.lat());
                        $("#keido0").val(e.latLng.lng());
                        marker.setMap(googlemap);
                        googlemap.panTo(new google.maps.LatLng(e.latLng.lat(), e.latLng.lng()));
                        //$("#mapzoom"+no).val(googlemap.getZoom());
                    });

        google.maps.event.addDomListener(window, "resize", function() {
	           var center = marker.getPosition();//googlemap.getCenter();
	            google.maps.event.trigger(googlemap, "resize");
	             googlemap.setCenter(center);
               //$("#mapzoom"+no).val(googlemap.getZoom());
          });

        if($('#logtabs a').length){
        $('#logtabs a').on('shown.bs.tab', function(){
              var center = marker.getPosition();//
	            google.maps.event.trigger(googlemap, 'resize');
              //var center = marker.getPosition();//
              googlemap.setCenter(center);
              $("#mapzoom0").val(googlemap.getZoom());
	            //return false;
          });

        }
    }

        //mapInit();
    }
});
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
  if($('.datepicker').length && $('#firstday0').length && $('#lastday0').length){
    //$('.class-sunday').css('color','red !important');
    //$('.class-saturday').css('color','blue !important');
    $('#firstday0').datepicker({
        format: "yyyy年mm月dd日",
        language: "ja",
        daysOfWeekHighlighted: "0,6",
        useCurrent: false
        }
    );
    $('#lastday0').datepicker({
        format: "yyyy年mm月dd日",
        language: "ja",
        daysOfWeekHighlighted: "0,6",
        useCurrent: false
        }
    );
    var $FD = $('#firstday0');
    var $LD = $('#lastday0');
    $FD.change(function(){
        $LD.val($FD.datepicker('getFormattedDate'));
        $LD.datepicker('update');
    });
    $('.class-saturday').css('color','blue');
}});
////////////////////////////////////////////////////////////////////////////////////////
$(function(){
    if($('.theday').length){
        selectTheDateSetCreate();
        $('#firstday0').change(selectTheDateSetCreate);
        $('#lastday0').change(selectTheDateSetCreate);
        $('#edittheday0').change(selectTheDateSetCreate);
/////////////////////////////////////
        function selectTheDateSetCreate(){
            var $firstday = $('#firstday0').val();
            var $lastday = $('#lastday0').val();
            var $firstdayParse = dateToParse($firstday);
            var $lastdayParse = dateToParse($lastday);
            var $difference = ($lastdayParse - $firstdayParse)/1000/60/60/24;
            if($('#edittheday0').length){
                var $theday = $('#edittheday0').val();
                var $thedayParse = dateToParse($theday);
                var $editdifference = ($thedayParse - $firstdayParse)/1000/60/60/24;
            }
            var $oneday = 1000*60*60*24;
            $('#theday0').empty();
            for($d=0;$d<=$difference;$d++){
                var $optionday = new Date($firstdayParse + ($oneday * $d));
                var $optionYear = $optionday.getFullYear();
                var $optionMonth = $optionday.getMonth() + 1;
                var $optionDay = $optionday.getDate();
                if($('#edittheday0').length && $d==$editdifference){
                    $('#theday0').append('<option selected  value="'+$optionYear+'-'+("0"+$optionMonth.toString()).slice(-2)+'-'+("0"+$optionDay.toString()).slice(-2)+'">'+$optionYear+'年'+("0"+$optionMonth.toString()).slice(-2)+'月'+("0"+$optionDay.toString()).slice(-2)+'日'+'</option>');
                }else{
                    $('#theday0').append('<option value="'+$optionYear+'-'+("0"+$optionMonth.toString()).slice(-2)+'-'+("0"+$optionDay.toString()).slice(-2)+'">'+$optionYear+'年'+("0"+$optionMonth.toString()).slice(-2)+'月'+("0"+$optionDay.toString()).slice(-2)+'日'+'</option>');
                }
            }
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
    if($('.imageThumbnailField').length && $('.myLogForm').length){
        var thumbFCnt = $('.imageThumbnailField').length;
        for($i=0;$i<thumbFCnt;$i++){
            var thumbF = $('#imageThumbnailField'+$i);
            $('#myLogForm'+$i).on('change', 'input[type="file"]', function(event) {
                //thumbF.css('background-color','silver');
                thumbF.empty();
                var files = event.target.files;
                var $inputFileLength = files.length;//Math.min(20,files.length);
                for (var i = 0 ; i<$inputFileLength; i++) {
                    var f = files[i];
                    var reader = new FileReader;
                    reader.onload = (function(){
                        return function(e){
                            var imageAppend = $('<div class="col-xs-6" />').append('<img class="img-responsive" src="'+ e.target.result +'" style="margin-top:10px;margin-bottom:10px;" />');
                            thumbF.append(imageAppend);
                        }
                    })(f);
                if(i==$inputFileLength-1){
                    thumbF.append('<p>スマホなどの画像は保存の際に自動で向きを調節します</p>');
                  }
                reader.readAsDataURL(f);
                }
            });
        }
    }
});
////////////////////////////////////////////////////////////////////
$(function(){
    if($('#createRateField0').length){
            $.fn.raty.defaults.path = "/raty/lib/images";
            rateF = $('#createRateField0');
                //rateF.raty({score: parseInt($('#oldScore').val())});
                rateF.raty();
    }
});
