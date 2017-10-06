$(function(){
    if($('.recruitmentMap').length){
        var Mapcnt = $('.recruitmentMap').length;
        for($i=0;$i<Mapcnt;$i++){
        var centerLat = parseFloat($('#recruitmentLat'+$i).val());
        var centerLng = parseFloat($('#recruitmentLng'+$i).val());
        var circRadius = parseFloat($('#recruitmentRadius'+$i).val());
        var centerPosition = {lat:centerLat, lng:centerLng};
        var googlemap = new google.maps.Map(document.getElementById("recruitmentMap"+$i),
                {
                  zoom : 5,
                  center : centerPosition,
                  mapTypeId: google.maps.MapTypeId.TERRAIN,//
                  mapTypeControl: false,//
                  fullscreenControl: true,
                  streetViewControl: false,//
                  scrollwheel: true,//
                  zoomControl: true
                });
        Marker = new google.maps.Marker({
                position: centerPosition,
                map: googlemap
                });
        Circle = new google.maps.Circle({
                    center: centerPosition,
                    map: googlemap,
                    radius: circRadius,
                    fillColor: '#FF0000', 		// 塗りつぶし色
                    fillOpacity: 0.5,
                    strokeColor: '#FF0000',		// 外周色
                    strokeOpacity: 1,	// 外周透過度（0: 透明 ⇔ 1:不透明）
                    strokeWeight: 1,
                    //editable: true
                  });
        var minX = centerLng-(circRadius/111325);
        var maxX = centerLng+(circRadius/111325);
        var minY = centerLat-(circRadius/111136);
        var maxY = centerLat+(circRadius/111136);
        var sw = new google.maps.LatLng(maxY, minX);
        var ne = new google.maps.LatLng(minY, maxX);
        var bounds = new google.maps.LatLngBounds(sw, ne);
        googlemap.fitBounds(bounds);
        }
    }
});
$(function(){
    if($('#Relimitdate').length){
        $('#Relimitdate').datepicker({
            format: "yyyy年mm月dd日",
            language: "ja",
            daysOfWeekHighlighted: "0,6",
            useCurrent: false
            }
        );
    }
    if($('#GuestGuideSpotMap').length){
        $('#GuestGuidePost').on('show.bs.modal',function(event){
            //var $URL = $('#GuestGuideForm').attr('action');
            //$('.modal-header').html($URL);
            $('#spotdata').empty();
            /*
            var button = $(event.relatedTarget);
            var userid = button.data('userid');
            var man = button.data('man');
            var originalurl = $('#GuestGuideForm').attr('action');
            var editreplace = 'user/'+userid+'/'+man+'post';
            var editurl = originalurl.replace(/(user\/)(.*?)(post)/,editreplace);
            $("#GuestGuideForm").attr("action",editurl);
            if(man=='guide'){
                //$('.modal-header').html('ガイド募集');
                $('.modal-header').html(editurl);
            }else{
                //$('.modal-header').html('ゲスト募集');
                $('.modal-header').html(editurl);
            }*/
        });
        $('#GuestGuidePost').on('shown.bs.modal', function(event){
            var button = $(event.relatedTarget);
            var userid = button.data('userid');
            var man = button.data('man');
            var originalurl = $('#GuestGuideForm').attr('action');
            var editreplace = 'user/'+userid+'/'+man+'post';
            var editurl = originalurl.replace(/(user\/)(.*?)(post)/,editreplace);
            $("#GuestGuideForm").attr("action",editurl);
            if(man=='guide'){
                $('.modal-header').html('ガイド募集');
                //$('.modal-header').html(editurl);
            }else{
                $('.modal-header').html('ゲスト募集');
                //$('.modal-header').html(editurl);
            }
            var centerPosition = {lat:35, lng: 136};
            var googlemap = new google.maps.Map(document.getElementById("GuestGuideSpotMap"),
                    {
                      zoom : 5,
                      center : centerPosition,
                      mapTypeId: google.maps.MapTypeId.TERRAIN,//
                      mapTypeControl: false,//
                      fullscreenControl: true,
                      streetViewControl: false,//
                      scrollwheel: true,//
                      zoomControl: true
                    });
            var Marker = new Array;
            var Circle = new Array;
            var cnt = -1;
            google.maps.event.addListener(googlemap,'click', function(e){
                    cnt++;
                    Marker[cnt] = new google.maps.Marker({
                            position: e.latLng,
                            draggable: true,
                            map: googlemap
                            });
                    Circle[cnt] = new google.maps.Circle({
	                            center: e.latLng,
	                            map: googlemap,
                              radius: parseInt(parseFloat(20)*1000),
                              fillColor: '#FF0000', 		// 塗りつぶし色
	                            fillOpacity: 0.5,
                              strokeColor: '#FF0000',		// 外周色
	                            strokeOpacity: 1,	// 外周透過度（0: 透明 ⇔ 1:不透明）
	                            strokeWeight: 1,
                              editable: true
                            });
                    Circle[cnt].bindTo("center", Marker[cnt], "position");
                    $('#cnt').val(cnt);
                    $('.modal-header').html(cnt);
                    $('#spotdata').append('<input type="hidden" value="'+e.latLng.lat()+'" name="centerLat[]" id="centerLat'+cnt+'">');
                    $('#spotdata').append('<input type="hidden" value="'+e.latLng.lng()+'" name="centerLng[]" id="centerLng'+cnt+'">');
                    $('#spotdata').append('<input type="hidden" value="20000" name="circleRadius[]" id="circleRadius'+cnt+'">');
                    google.maps.event.addListener(Marker[cnt],'drag',function(){
                      var marker = Marker[cnt].getPosition();
                      var circle = Circle[cnt].getRadius();
                      $('#centerLat'+cnt).val(marker.lat());
                      $('#centerLng'+cnt).val(marker.lng());
                      $('#circleRadius'+cnt).val(circle);
                    });
                    google.maps.event.addListener(Circle[cnt],'radius_changed',function(){
                        var marker = Marker[cnt].getPosition();
                        var circle = Circle[cnt].getRadius();
                        $('#centerLat'+cnt).val(marker.lat());
                        $('#centerLng'+cnt).val(marker.lng());
                        $('#circleRadius'+cnt).val(circle);
                    });
                });
        });
    }
});

$(function(){
    if($('#fixScene0').length){
        var originalImg = $('#photosField').html();
        $('#fixScene0').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var $sceneID = button.data('userid')+'-'+button.data('titleid')+'-'+button.data('sceneid');
            $('#photosField').html(originalImg).find('img').each(function(){
                var $photoSceneId = $(this).attr('sceneID');
                if($photoSceneId != $sceneID){
                    $(this).remove();
                }
            });
            if($('#photosField > img').length){
                $('#photosField').prepend('<div><small>消去する画像をクリック</small></div>');
            }
        });
        $('#fixScene0').on('shown.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var oldScene = button.data('scene');
            var sceneid = button.data('sceneid');
            var sceneid = button.data('sceneid');
            var titleid = button.data('titleid');
            var userid = button.data('userid');
            var titleStr = button.data('title');
            var oldLat = button.data('lat');
            var oldLng = button.data('lng');
            var oldScore = button.data('score');
            var oldPublish = button.data('publish');
            var oldComment = button.data('comment');
            var oldTheday = button.data('oldtheday');
            var editstyle = button.data('editstyle');
            var $firstday = button.data('firstday');
            var $lastday = button.data('lastday');
            var $theday = oldTheday;
            $('#firstday0').val($firstday);
            $('#lastday0').val($lastday);
            //var titleId = $('#titleIdAction').val();
            $('#editstyle').val(editstyle);
            $('#editTitleName').val(titleStr);
            if(editstyle == 'fix'){
                $('#NewScene0').val(oldScene);
                $('#comment0').val(oldComment);
                $('.modal-title').html('シーンの編集');
                $('#sceneEditSubmit').val('更新');
            }else{
                $('#NewScene0').val('');
                $('#NewScene0').attr('placeholder',oldScene);
                $('#comment0').val('');
                $('#comment0').attr('placeholder',oldComment);
                $('.modal-title').html('シーンの追加');
                $('#sceneEditSubmit').val('追加');
            }
            $('#ido0').val(oldLat);
            $('#keido0').val(oldLng);
            $('#oldScore').val(oldScore);
            $('#edittheday0').val(oldTheday);
            if(oldPublish=='public'){
                $('#radioPublic').attr('checked','checked');
                $('#radioPrivate').removeAttr('checked');
            }
            if(oldPublish=='private'){
                $('#radioPrivate').attr('checked','checked');
                $('#radioPublic').removeAttr('checked');
            }

            if($('#photosField').length){
                var num = 0;
                $('#photosField img').on('click',function(){
                    $(this).data("click",++num);
                    var clickcnt = $(this).data("click");
                    var $no = $(this).attr('photoID');
                    if(clickcnt % 2 ==1){
                        $(this).css({'filter':'alpha(opacity=20)',
                                      '-moz-opacity':'0.2',
                                      'opacity':'0.2'});
                        $('#deletePhotoNo'+$no).val('true');
                    }else{
                        $(this).css({'filter':'alpha(opacity=100)',
                                      '-moz-opacity':'1',
                                      'opacity':'1'});
                        $('#deletePhotoNo'+$no).val('false');
                    }
                    //$('#deletePhotoDivNo'+$no).html($('#deletePhotoNo'+$no).val());
                    return false;
                });
            }


            var originalurl = $('#myLogForm0').attr('action');
            var editreplace = 'user/'+userid+'/mylog/title/'+titleid+'/'+sceneid+'/edit';
            var editurl = originalurl.replace(/(user\/)(.*?)(\/edit)/,editreplace);
            $("#myLogForm0").attr("action",editurl);
            //$('.modal-title').html(editurl);

            function dateToParse(date){
                date=date.replace('月','/');
                date=date.replace('年','/');
                date=date.replace('日','');
                date=Date.parse(date);
                return date;
            }
            //var $firstday = $('#firstday0').val();
            //var $lastday = $('#lastday0').val();
            //var $theday = $('#edittheday0').val();
            var $firstdayParse = dateToParse($firstday);
            var $lastdayParse = dateToParse($lastday);
            var $thedayParse = dateToParse($theday);
            var $difference = ($lastdayParse - $firstdayParse)/1000/60/60/24;
            var $editdifference = ($thedayParse - $firstdayParse)/1000/60/60/24;
            var $oneday = 1000*60*60*24;
            $('#theday0').empty();
            //$('#theday0').append('<option value="0000-00-00">0000年00月00日</option>');
            //$('#theday0').append('<option value="0000-00-01" selected="selected">0000年00月01日</option>');
            for($d=0;$d<=$difference;$d++){
                var $optionday = new Date($firstdayParse + ($oneday * $d));
                var $optionYear = $optionday.getFullYear();
                var $optionMonth = $optionday.getMonth() + 1;
                var $optionDay = $optionday.getDate();
                var $optionvalue = $optionYear+'-'+("0"+$optionMonth.toString()).slice(-2)+'-'+("0"+$optionDay.toString()).slice(-2);
                var $optionstr = $optionYear+'年'+("0"+$optionMonth.toString()).slice(-2)+'月'+("0"+$optionDay.toString()).slice(-2)+'日';
                if($d==$editdifference){
                    $('#theday0').append('<option selected="selected" value="'+$optionvalue+'">'+$optionstr+'</option>');
                }else{
                    $('#theday0').append('<option value="'+$optionvalue+'">'+$optionstr+'</option>');
                }
            }


            if($('#editRateField0').length){
                    $.fn.raty.defaults.path = "/raty/lib/images";
                    rateF = $('#editRateField0');
                        rateF.raty({score: parseInt($('#oldScore').val())});
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
$(function(){///オススメ更新
    if($('.showRaty').length){
        $.fn.raty.defaults.path = "/raty/lib/images";
        var $showRatyCnt = $('.showRaty').length;
        for($i=0;$i<$showRatyCnt;$i++){
            $('#showRatyDiv'+$i).raty({ readOnly: true, score: parseInt($('#showRaty'+$i).val()) });
        }
    }
});
////////////////////////////////////////////////////////////////
$(function(){///オススメ表示
    if($('.showRatyAve').length){
        $.fn.raty.defaults.path = "/raty/lib/images";
        var $showRatyAveCnt = $('.showRatyAve').length;
            $('#showRatyAveDiv').raty({ readOnly: true, score: parseInt($('#showRatyAve').val()*2)/2 });
    }
});
////////////////////////////////////////////////////////////////////////
$(function(){///グーグルマップ表示
    if($('.googlemapSpot').length){
        var $gmScnt = $('.googlemapSpot').length;
        for($i=0;$i<$gmScnt;$i++){
            var $latval = parseFloat($('#googlemapLat'+$i).val());
            var $lngval = parseFloat($('#googlemapLng'+$i).val());
            var centerPosition = {lat: $latval, lng: $lngval};
            var googlemap = new google.maps.Map(document.getElementById("googlemapSpotID"+$i),
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
            //google.maps.event.addDomListener(window, "resize", function() {
    	      //      google.maps.event.trigger(googlemap, "resize");
    	      //       googlemap.setCenter(centerPosition);
            //  });
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
$(function(){///アバター画像変更サムネイル
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
$(function(){///アップロード画像サムネイル
    if($('#createPhotoSpotSetArea0').length){
        var $pSS = $('#createPhotoSpotSetArea0');
        $pSS.css('height','40vh');
        mapInit();
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
        var marker = new google.maps.Marker({
                        position: centerPosition,
                        map: googlemap
                    });
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
$(function(){//生年月日うるう年
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
$(function(){/////////////都道府県セレクトボックス
    if($('#cityselect').length && $('#prefselect').length){
        var $city = $('#cityselect');
        var original = $city.html();
        $('#prefselect').change(function() {
            var val1 = $(this).val();
            $city.html(original).find('option').each(function() {
                var val2 = $(this).data('val');
                if (val1 != val2) {
                    $(this).not(':first-child').remove();
                }
            });
            if ($(this).val() == "") {
                $city.attr('disabled', 'disabled');
            } else {
            $city.removeAttr('disabled');
            }
        });
}});
//////////////////////////////////////////////////////////////////////////////
$(function(){/////カレンダー表示
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
/////////////////////////////////////
        function selectTheDateSetCreate(){
            var $firstday = $('#firstday0').val();
            var $lastday = $('#lastday0').val();
            var $firstdayParse = dateToParse($firstday);
            var $lastdayParse = dateToParse($lastday);
            var $difference = ($lastdayParse - $firstdayParse)/1000/60/60/24;
            var $oneday = 1000*60*60*24;
            $('#theday0').empty();
            for($d=0;$d<=$difference;$d++){
                var $optionday = new Date($firstdayParse + ($oneday * $d));
                var $optionYear = $optionday.getFullYear();
                var $optionMonth = $optionday.getMonth() + 1;
                var $optionDay = $optionday.getDate();
                    $('#theday0').append('<option value="'+$optionYear+'-'+("0"+$optionMonth.toString()).slice(-2)+'-'+("0"+$optionDay.toString()).slice(-2)+'">'+$optionYear+'年'+("0"+$optionMonth.toString()).slice(-2)+'月'+("0"+$optionDay.toString()).slice(-2)+'日'+'</option>');
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
