$(function(){
    if($('#planMap').length){
        var centerPosition = {lat:35, lng: 136};
        var googlemap = new google.maps.Map(document.getElementById("planMap"),
            {
              zoom : 5,
              center : centerPosition,
              mapTypeId: google.maps.MapTypeId.ROADMAP,//
              mapTypeControl: false,//
              fullscreenControl: true,
              streetViewControl: false,//
              scrollwheel: true,//
              zoomControl: true
            });
        var directionsDisplay = new google.maps.DirectionsRenderer({
            "map": googlemap,
            "preserveViewport": false,
        });
        var directionsService = new google.maps.DirectionsService();
        var Marker = [],Lats = [],Lngs = [],Waypoints = [],resultMap = [],Keys=[],line=null;
        var MarkerCnt = $('#planData .list-group-item').length;
        $('#titleStr').change(savePlan);
        $('#firstday0').change(savePlan);
        $('#lastday0').change(savePlan);
        $('#planDescribe').change(savePlan);
        if(MarkerCnt > 0){
            for($i=0;$i<MarkerCnt;$i++){
                var spotval = $('#latlng'+$i).val();
                var latlng = spotval.split(',');
                var position = {lat:parseFloat(latlng[0]) ,lng:parseFloat(latlng[1])}
                Marker[$i] = new google.maps.Marker({
		            map: googlemap,
		            position: position,
		            draggable:true,
	            });
	            Keys[$i] = $('#search'+$i).val();
	            Lats[$i] = parseFloat(latlng[0]);
	            Lngs[$i] = parseFloat(latlng[1]);
	            if(1 in Marker){
                    var sw = new google.maps.LatLng(Math.max.apply(null,Lats), Math.min.apply(null,Lngs));
                    var ne = new google.maps.LatLng(Math.min.apply(null,Lats), Math.max.apply(null,Lngs));
                    var bounds = new google.maps.LatLngBounds(sw, ne);
                    googlemap.fitBounds(bounds);
                }
                $('#search'+$i).change(searchPlace);
                $('#delSpot'+$i).css('display','');
                $('#delSpot'+$i).click(delSpot);
                $('#planData').scrollTop($('#planData')[0].scrollHeight);
            }
            drawRoute();
        }
        $('#planAddButton').click(function(){
            var MarkerCnt = $('#planData .list-group-item').length;
            var spotcnt = MarkerCnt+1;
            var phase0 = '<button style="display:none;" type="button" class="btn btn-xs btn-danger" id="delSpot'+MarkerCnt+'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
            var phase1 = phase0+'<label>スポット：<label><input type="text" name="searchWord[]" class="form-control searchBox" id="search'+MarkerCnt+'">';
            var phase2 = '<div class="list-group-item form-group list-group-item-warning" id="spotData'+MarkerCnt+'">'+phase1+'</div>';
            var BMarkerCnt = MarkerCnt-1;
            if(BMarkerCnt in Marker || MarkerCnt == 0){
                $('#planData').append(phase2);
                $('#search'+MarkerCnt).change(searchPlace);
                $('#planData').scrollTop($('#planData')[0].scrollHeight);
            }
        });
        
        function searchPlace(){
            key=$(this).val();
            index = $('#planData .searchBox').index(this);
            var request = {
                query: key,
            };
            service = new google.maps.places.PlacesService(googlemap);
            service.textSearch(request, callback);
        }
        
        function callback(results, status) {
            if (status == google.maps.places.PlacesServiceStatus.OK) {
               var stopcnt = results.length;
               if(stopcnt == 0){
                   alert('Not Found');
               }else{
                   createMarker(results[0]);
               }
            }
        }
        
        function createMarker(place) {
            var pos = place.geometry.location;
            if(index in Marker){
                Marker[index].setPosition(place.geometry.location);
            }else{
	            Marker[index] = new google.maps.Marker({
		            map: googlemap,
		            position: place.geometry.location,
		            draggable:true,
	            });
	            $('#delSpot'+index).css('display','');
	            $('#delSpot'+index).on('click',delSpot);
            }
            Keys[index] = key;
            Lats[index] = Marker[index].getPosition().lat();
            Lngs[index] = Marker[index].getPosition().lng();
            if(Marker.length > 1){
                var sw = new google.maps.LatLng(Math.max.apply(null,Lats), Math.min.apply(null,Lngs));
                var ne = new google.maps.LatLng(Math.min.apply(null,Lats), Math.max.apply(null,Lngs));
                var bounds = new google.maps.LatLngBounds(sw, ne);
                googlemap.fitBounds(bounds);
            }
            drawRoute();
            savePlan();
        }
        
        function drawRoute(){
            if(Marker.length > 1){
                directionsDisplay.setMap(null);
                var done = 0,requestIndex =0;
                var $start = null,$end = null;
                for($i in Marker){
                    if(Marker[$i]!='hogefugapuri'){
                    if($start == null){
                        $start = Marker[$i];
                    }else if(Waypoints.length == 8 || $i == Marker.length-1){
                        $end = Marker[$i];
                        (function(index){
                        var request = {
                            origin: $start.getPosition(),
                            destination: $end.getPosition(),
                            waypoints:Waypoints,
                            travelMode: 'DRIVING'
                            //transitOptions:{
                            //    modes:['RAIL']
                            //}
                        };
                        directionsService.route(request, function(result, status) {
                            if (status == 'OK') {
                                resultMap[index] = result;
                                done++
                            }
                        });
                        })(requestIndex);
                        $start = Marker[$i];
                        Waypoints = [];
                        requestIndex++;
                    }else{
                        Waypoints.push({ location: Marker[$i].getPosition(), stopover: true });
                    }
                    }
                }
        var sid = setInterval(function(){
            if (requestIndex > done) return;
                clearInterval(sid);
            var path = [];
            var result;
            for (var i = 0, len = requestIndex; i < len; i++) {
                result = resultMap[i];
                var legs = result.routes[0].legs;
                for (var li = 0, llen = legs.length; li < llen; li++) {
                    var leg = legs[li];
                    var steps = leg.steps;
                    var _path = steps.map(function(step){ return step.path })
                        .reduce(function(all, paths){ return all.concat(paths) });
                    path = path.concat(_path);
                }
            }
        line = new google.maps.Polyline({
            map: googlemap,
            strokeColor: "#2196f3", // 線の色
            strokeOpacity: 0.8, // 線の不透明度
            strokeWeight: 6, // 先の太さ
            path: path // 描画するパスデータ
        });
        function deletePath(){
            line.setMap(null);
        }
        for($i=0;$i<$('#planData .list-group-item').length;$i++){
            $('#search'+$i).off('change.delPath');
            $('#delSpot'+$i).off('click.delPath');
            $('#search'+$i).on('change.delPath',deletePath);
            $('#delSpot'+$i).on('click.delPath',deletePath);
        }
    }, 1000);
	        }
        }
        
        function savePlan(){
            $.ajaxSetup({
　　              headers: {
　　　               'X-CSRF-TOKEN': $('#planCSRF').val()
　　              }
　             });
　          if(Marker.length){
　              $.ajax({
                  url:$('#addSpots').attr('action'),
                  type:"POST",
                  dataType:"text",
                  data:{
                    'titleid':$('#titleid').val(),
                    'title':$('#titleStr').val(),
                    'firstday':$('#firstday0').val(),
                    'lastday':$('#lastday0').val(),
                    'describe':$('#planDescribe').val(),
                    'lats[]':Lats,
                    'lngs[]':Lngs,
                    'keys[]':Keys
                  },
                  success :function(saveDone){
                      $('#saveInfo').modal('show');
                      setTimeout(function(){
                        $('#saveInfo').modal('hide');
                      },800);
                  },
                  error : function(XMLHttpRequest, textStatus, errorThrown) {
    　　　　              alert('保存に失敗');
    　　　　    　　　  }
                });
　          }else{
　          $.ajax({
                  url:$('#addSpots').attr('action'),
                  type:"POST",
                  dataType:"text",
                  data:{
                    'titleid':$('#titleid').val(),
                    'title':$('#titleStr').val(),
                    'firstday':$('#firstday0').val(),
                    'lastday':$('#lastday0').val(),
                    'describe':$('#planDescribe').val(),
                    'lats[]':Lats,
                    'lngs[]':Lngs,
                    'keys[]':Keys
                  },
                  success :function(saveDone){
                      $('#saveInfo').modal('show');
                      setTimeout(function(){
                        $('#saveInfo').modal('hide');
                      },800);
                  },
                  error : function(XMLHttpRequest, textStatus, errorThrown) {
    　　　　              alert('保存に失敗');
    　　　　    　　　  }
                });
　          }
        }
        
        function delSpot(){
            var no = $('#planData button').index(this);
            $('#planData .list-group-item').eq(no).css('display','none');
            Marker[no].setMap(null);
            Marker[no] = 'hogefugapuri';
            //Lats.splice(no,1);
            //Lngs.splice(no,1);
            Keys[no] = 'hogefugapuri';
            drawRoute();
            savePlan();
        }
    }
});
///////////////////////////////////////////////////////////
$(function(){
    if($('#QRdiv').length){
    $('#QRdiv').qrcode({
	text: window.location.href,
	size:50
    });
    }
});
///////////////////////////////////////////////
$(function(){
var topBtn=$('#ToPageTop');
topBtn.hide();
if($(document).height()>$(window).height()){
    topBtn.show();
}
window.addEventListener("orientationchange", function() {
    if($(document).height()>$(window).height()){
        topBtn.show();
    }else{
        topBtn.hide();
    }
});
window.addEventListener("resize", function() {
    if($(document).height()>$(window).height()){
        topBtn.show();
    }else{
        topBtn.hide();
    }
});
topBtn.click(function(){
  $('body,html').animate({
  scrollTop: 0},500);
  return false;
});

});
$(function(){
    if($('#searchMap').length){
        $('.tabArea a[href = "#tab3"]').on('shown.bs.tab', function(){
            if($('#centerLat').val()!="0" && $('#centerLng').val()!="0"){
            var centerPosition = {lat: parseFloat($('#centerLat').val()), lng: parseFloat($('#centerLng').val())};
            }else{
            var centerPosition = {lat: 36, lng: 136};
            }
            if($('#circleRadius').val()!="0"){
                var circRadius = $('#circleRadius').val();
            }else{
                var circRadius = 60000;
            }
            var googlemap = new google.maps.Map(document.getElementById("searchMap"),
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
            var Marker = new google.maps.Marker({
                    position: centerPosition,
                    draggable: true,
                    map: googlemap
                    });
            var Circle = new google.maps.Circle({
                      center: centerPosition,
                      map: googlemap,
                      radius: parseInt(circRadius),
                      fillColor: '#FF0000', 		// 塗りつぶし色
                      fillOpacity: 0.5,
                      strokeColor: '#FF0000',		// 外周色
                      strokeOpacity: 1,	// 外周透過度（0: 透明 ⇔ 1:不透明）
                      strokeWeight: 1,
                      editable: true
                    });
            Circle.bindTo("center", Marker, "position");
            google.maps.event.addListener(Marker,'dragend',function(){
                  var marker = Marker.getPosition();
                  var circle = Circle.getRadius();
                  $('#centerLat').val(marker.lat());
                  $('#centerLng').val(marker.lng());
                  $('#circleRadius').val(circle);
                  Marker.setMap(googlemap);
                  googlemap.panTo(new google.maps.LatLng(marker.lat(), marker.lng()));
                });
            google.maps.event.addListener(Circle,'radius_changed',function(){
                  var marker = Marker.getPosition();
                  var circle = Circle.getRadius();
                  $('#centerLat').val(marker.lat());
                  $('#centerLng').val(marker.lng());
                  $('#circleRadius').val(circle);
                });
            google.maps.event.addListener(googlemap,'click', function(e){
                    Marker.position = e.latLng;
                    var circle = Circle.getRadius();
                    $('#centerLat').val(e.latLng.lat());
                    $('#centerLng').val(e.latLng.lng());
                    $('#circleRadius').val(circle);
                    Circle.bindTo("center", Marker, "position");
                    Marker.setMap(googlemap);
                    googlemap.panTo(new google.maps.LatLng(e.latLng.lat(), e.latLng.lng()));
                });
            google.maps.event.addDomListener(window, "resize", function() {
    	             var center = Marker.getPosition();//googlemap.getCenter();
    	             google.maps.event.trigger(googlemap, "resize");
    	             googlemap.setCenter(center);
              });
        });
    }
});
/////////////////////////////////////////////////////
$(function(){//生年月日うるう年
  if($('#monthSelect1').length && $('#daySelect1').length && $('#yearSelect1').length){
    var $month = $('#monthSelect1');
   var $day = $('#daySelect1');
   var $year = $('#yearSelect1');
   var originaldays = $day.html();
   $month.change(leapcheck1);
   $year.change(leapcheck1);
   function leapcheck1(){
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
}
if($('#monthSelect2').length && $('#daySelect2').length && $('#yearSelect2').length){
    var $month = $('#monthSelect2');
   var $day = $('#daySelect2');
   var $year = $('#yearSelect2');
   var originaldays = $day.html();
   $month.change(leapcheck2);
   $year.change(leapcheck2);
   function leapcheck2(){
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
}
if($('#monthSelect3').length && $('#daySelect3').length && $('#yearSelect3').length){
    var $month = $('#monthSelect3');
   var $day = $('#daySelect3');
   var $year = $('#yearSelect3');
   var originaldays = $day.html();

   $month.change(leapcheck3);
   $year.change(leapcheck3);

   function leapcheck3(){
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
                             if(vald == "29"){$(this).not(':last-child').remove();}
                           }
                       }
                 } else {
                     if(vald == "29"){$(this).not(':last-child').remove();}
                 }
             }
        }
        //$('#daySelect3 option:last-child').val($('#daySelect3 option:first-child').val());
   });
 }
}
});
$(function(){
    if($('#firstdayT').length){
        $('#firstdayT').datepicker({
            format: "yyyy年mm月dd日",
            language: "ja",
            daysOfWeekHighlighted: "0,6",
            useCurrent: false
            }
        );
        $('#lastdayT').datepicker({
            format: "yyyy年mm月dd日",
            language: "ja",
            daysOfWeekHighlighted: "0,6",
            useCurrent: false
            }
        );
    }
});
////////////////////////////////////////////////////////////
$(function(){///////メッセージ
    if($('#messageboad').length){
        $('#messageboad').on('show.bs.modal',function(event){
            var theE = $(event.relatedTarget);
            var partnerid = theE.data('partner');
            var messageid = theE.data('messageid');
            var urlbefore = $('#sendform').attr('action');
            var editreplace = 'message/'+partnerid+'/send';
            var urlafter = urlbefore.replace(/(message\/)(.*?)(\/send)/,editreplace);
            $("#sendform").attr("action",urlafter);
            $('#themessage').val('');
            $("#newTimestamp").val('0000-00-00 00:00:00');
            var loadurlbefore = $('#loadform').attr('action');
            var loadreplace = 'message/'+partnerid+'/show';
            var loadurlafter = loadurlbefore.replace(/(message\/)(.*?)(\/show)/,loadreplace);
            $("#loadform").attr("action",loadurlafter);
            $('.messageShow').empty();
            $('#partnerId').val(partnerid);
              $.ajaxSetup({
　　              headers: {
　　　               'X-CSRF-TOKEN': $('#MessageCsrfTokenGet').val()
　　              }
　             });
            loadMessage();
            function loadMessage(){
                $.ajax({
                  url:$('#loadform').attr('action'),
                  type:"POST",
                  dataType:"json",
                  data:{
                    'id':$('#partnerId').val(),
                    'chtimestamp':$("#newTimestamp").val()
                  },
                  success :function(json){
                      var beforeCnt = $('.forCnt').length;
                      var jsonsize = json.length;
                      var h = new Array;
                      var m = new Array;
                      var createtime = new Array;
                      for(i=0;i<jsonsize;i++){
                        var fixi = i+beforeCnt;
                        if(json[i].message!=""){
                          var timestamp = new Date(json[i].created_at);
                          timestamp.toString();
                          h[i] = timestamp.getHours();
                          m[i] = timestamp.getMinutes();
                          createtime[i] = h[i] + ':' + ('0' + m[i]).slice(-2);
                        if(json[i].user_id!=$('#partnerId').val()){
                            $('.messageShow').append('<div class="text-right"><div class="wrap irekoR">'+json[i].message+'</div><p class="smallp" id="message'+fixi+'">'+createtime[i]+'</p></div>');
                        }else{
                            $('.messageShow').append('<div class="text-left" id="message'+fixi+'"><div class="wrap irekoL">'+json[i].message+'</div><p class="smallp">'+createtime[i]+'</p></div>');
                        }
                      }}
                    if(jsonsize!=0){
                      $("#newTimestamp").val(json[jsonsize-1].created_at);
                      $('.messageShow').scrollTop($('.messageShow')[0].scrollHeight);
                    }
                  },
                  error : function(XMLHttpRequest, textStatus, errorThrown) {
    　　　　              alert(textStatus + ":" + errorThrown);
    　　　         }
                });
            }
        var setTimer = setInterval(function(){loadMessage();},3000);
        $('#messageboad').on('hidden.bs.modal',function(){
            clearInterval(setTimer);
        });
        var badge = $('#newMessageHas'+messageid);
        badge.html('新着なし');
        if(badge.hasClass('btn-danger')){
            badge.removeClass('btn-danger');
            badge.addClass('btn-default');
        }
        });
        $('#messageSubmit').on('click',function(){
            if($('#themessage').val()!=""){
            $.ajax({
              url:$('#sendform').attr('action'),
              type:"POST",
              dataType:"json",
              data:{
                'id':$('#partnerId').val(),
                'message':$('#themessage').val(),
                'chtimestamp':$("#newTimestamp").val()
              },
              success :function(json){
                  /*
                  var beforeCnt = $('.forCnt').length;
                  var jsonsize = json.length;
                  var h = new Array;
                  var m = new Array;
                  var createtime = new Array;
                  for(i=0;i<jsonsize;i++){
                    var fixi = i+beforeCnt;
                    if(json[i].message!=""){
                      var timestamp = new Date(json[i].created_at);
                      timestamp.toString();
                      h[i] = timestamp.getHours();
                      m[i] = timestamp.getMinutes();
                      createtime[i] = h[i] + ':' + ('0' + m[i]).slice(-2);
                    if(json[i].user_id!=$('#partnerId').val()){
                        $('.messageShow').append('<div class="text-right"><div class="wrap irekoR">'+json[i].message+'</div><p class="smallp" id="message'+fixi+'">'+createtime[i]+'</p></div>');
                    }else{
                        $('.messageShow').append('<div class="text-left" id="message'+fixi+'"><div class="wrap irekoL">'+json[i].message+'</div><p class="smallp">'+createtime[i]+'</p></div>');
                    }
                  }}
                  $('.messageShow').scrollTop($('.messageShow')[0].scrollHeight);
                  $("#newTimestamp").val(json[jsonsize-1].created_at);
                  */
              },
              error : function(XMLHttpRequest, textStatus, errorThrown) {
　　　　              alert(textStatus + ":" + errorThrown);
　　　         }
            });
            $('#themessage').val('');
          }
        });
    }
});
$(function(){////マッチングデータ
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
$(function(){//////////////マッチングデータ登録
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
            $('#spotdata').empty();
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
                $('.modal-header').html('ガイド募集<button type="button" class="btn btn-primary btn-xs" data-dismiss="modal">閉じる</button>');
            }else{
                $('.modal-header').html('ゲスト募集<button type="button" class="btn btn-primary btn-xs" data-dismiss="modal">閉じる</button>');
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
                    var marker = new google.maps.Marker({
                            position: centerPosition,
                            draggable: true,
                            map: googlemap
                            });
                    var circle = new google.maps.Circle({
	                            center: centerPosition,
	                            map: googlemap,
                              radius: parseInt(parseFloat(40)*1000),
                              fillColor: '#FF0000', 		// 塗りつぶし色
	                            fillOpacity: 0.5,
                              strokeColor: '#FF0000',		// 外周色
	                            strokeOpacity: 1,	// 外周透過度（0: 透明 ⇔ 1:不透明）
	                            strokeWeight: 1,
                              editable: true
                            });
                    circle.bindTo("center", marker, "position");
                    google.maps.event.addListener(marker,'drag',function(){
                      var markerPosi = marker.getPosition();
                      var circleRad = circle.getRadius();
                      $('#centerLat').val(markerPosi.lat());
                      $('#centerLng').val(markerPosi.lng());
                      $('#circleRadius').val(circleRad);
                    });
                    google.maps.event.addListener(circle,'radius_changed',function(){
                        var markerPosi = marker.getPosition();
                      var circleRad = circle.getRadius();
                      $('#centerLat').val(markerPosi.lat());
                      $('#centerLng').val(markerPosi.lng());
                      $('#circleRadius').val(circleRad);
                    });
            google.maps.event.addListener(googlemap,'click', function(e){
                        marker.position = e.latLng;
                        circle.bindTo("center", marker, "position");
                        marker.setMap(googlemap);
                      var circleRad = circle.getRadius();
                      $('#centerLat').val(e.latLng.lat());
                      $('#centerLng').val(e.latLng.lng());
                      $('#circleRadius').val(circleRad);
                });
        });
    }
});

$(function(){/////////////////////シーン更新
    if($('#fixScene0').length){
        var originalImg = $('#photosField').html();
        $('#fixScene0').on('hidden.bs.modal', function (event){
            $('#photosField').html(originalImage);
        });
        $('#fixScene0').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var $sceneID = button.data('sceneid');
            if($('#photosField > img').length){
            $('#photosField').html(originalImg).find('img').each(function(){
                var $photoSceneId = $(this).attr('sceneID');
                if($photoSceneId != $sceneID){
                    $(this).remove();
                }
            });
            if($('#photosField > img').length){
                $('#photosField').prepend('<div><small>消去する画像をクリック</small></div>');
            }
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
            var oldGenre = button.data('genre');
            var $theday = oldTheday;
            $('#firstday0').val($firstday);
            $('#lastday0').val($lastday);
            //var titleId = $('#titleIdAction').val();
            $('#editstyle').val(editstyle);
            $('#editTitleName').val(titleStr);
            if(editstyle == 'fix'){
                $('#NewScene0').val(oldScene);
                $('#comment0').val(oldComment);
                $('.modal-title').html('シーンの編集<button type="button" class="btn btn-primary btn-xs" data-dismiss="modal">閉じる</button>');
                $('#sceneEditSubmit').val('更新');
            }else{
                $('#NewScene0').val('');
                $('#NewScene0').attr('placeholder',oldScene);
                $('#comment0').val('');
                $('#comment0').attr('placeholder',oldComment);
                $('.modal-title').html('シーンの追加<button type="button" class="btn btn-primary btn-xs" data-dismiss="modal">閉じる</button>');
                $('#sceneEditSubmit').val('追加');
            }
            $('#ido0').val(oldLat);
            $('#keido0').val(oldLng);
            $('#oldScore').val(oldScore);
            $('#edittheday0').val(oldTheday);
            $('input[name="genre[]"]').each(function(){
               $(this).prop('checked',false); 
            });
            var charr = oldGenre.split('-'); 
            var Allcharr = ['A','B','C','D','E','F'];
            var Allcharrlen = Allcharr.length;
            for($i=0;$i<Allcharrlen;$i++){
                if($.inArray(Allcharr[$i],charr)!=-1){
                    $('input[name="genre[]"][value="'+ Allcharr[$i] +'"]').prop('checked',true);
                }
            }
            if(oldPublish=='public'){
                $("input[name='publish'][value='public']").prop('checked',true);
            }
            if(oldPublish=='private'){
                 $("input[name='publish'][value='private']").prop('checked',true);
            }
            if(oldPublish=='friend'){
                 $("input[name='publish'][value='friend']").prop('checked',true);
            }

            if($('#photosField').length && $('#photosField > img').length){
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
            var $firstdayParse = dateToParse($firstday);
            var $lastdayParse = dateToParse($lastday);
            var $thedayParse = dateToParse($theday);
            var $difference = ($lastdayParse - $firstdayParse)/1000/60/60/24;
            var $editdifference = ($thedayParse - $firstdayParse)/1000/60/60/24;
            var $oneday = 1000*60*60*24;
            $('#theday0').empty();
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

/*
            if($('#editRateField0').length){
                    $.fn.raty.defaults.path = "/";
                    rateF = $('#editRateField0');
                        rateF.raty({score: parseInt($('#oldScore').val())});
            }
*/
            if($('#editPhotoSpotSetArea0').length){
                var $pSS = $('#editPhotoSpotSetArea0');
                //$pSS.css('height','40vh');
        //////////////////////////////////////////////
                mapInit();
        ///////////////////////////
                function mapInit() {//
                //if($('#ido0').val()!="" && $('#keido0').val()!=""){
                    var centerPosition = {lat: parseFloat($('#ido0').val()), lng: parseFloat($('#keido0').val())};
                //}else{
                //    var centerPosition = {lat: 36, lng: 136};
                //}
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
/*
$(function(){///オススメ更新
    if($('.showRaty').length){
        $.fn.raty.defaults.path = "/";
        var $showRatyCnt = $('.showRaty').length;
        for($i=0;$i<$showRatyCnt;$i++){
            $('#showRatyDiv'+$i).raty({ readOnly: true, score: parseInt($('#showRaty'+$i).val()) });
        }
    }
});
////////////////////////////////////////////////////////////////
$(function(){///オススメ表示
    if($('.showRatyAve').length){
        $.fn.raty.defaults.path = "/";
        var $showRatyAveCnt = $('.showRatyAve').length;
            $('#showRatyAveDiv').raty({ readOnly: true, score: parseInt($('#showRatyAve').val()*2)/2 });
    }
});
*/
////////////////////////////////////////////////////////////////////////
$(function(){///グーグルマップ複数表示
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
        }
    }
});
//////////  アバター変更時
$(function(){///アバター画像変更サムネイル
    if($('#avatarAfterChangeArea').length){
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
$(function(){///シーン登録グーグルマップ
    if($('#createPhotoSpotSetArea0').length){
        var $pSS = $('#createPhotoSpotSetArea0');
        //$pSS.css('height','40vh');
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
                        $("#ido0").val(e.latLng.lat());
                        $("#keido0").val(e.latLng.lng());
                        marker.setMap(googlemap);
                        googlemap.panTo(new google.maps.LatLng(e.latLng.lat(), e.latLng.lng()));
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
            $.fn.raty.defaults.path = "/";
            rateF = $('#createRateField0');
                //rateF.raty({score: parseInt($('#oldScore').val())});
                rateF.raty();
    }
});
