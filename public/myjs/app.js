$(function(){
    $ABC = $('#avatarBeforeChangeArea');
    $AAC = $('#avatarAfterChangeArea');
    $menuABC = $('#menuavatarBeforeChangeArea');
    $ABC.css('height',$ABC.css('width'));
    $menuABC.css('height',$menuABC.css('width'));
    $ABC.css({'background-position':'center',
              'background-repeat':'no-repeat',
              'background-size':'cover'
        });
    $AAC.css('height',$ABC.css('width'));
    $AAC.css({'background-position':'center',
              'background-repeat':'no-repeat',
              'background-size':'cover'
        });
    $menuABC.css({'background-position':'center',
              'background-repeat':'no-repeat',
              'background-size':'cover'
        });

$('#avatarForm').on('change', 'input[type="file"]', function(e) {
            var file = e.target.files[0],
                reader = new FileReader(),
                $preview = $("#avatarAfterChangeArea");
                t = this;

            // 画像ファイル以外の場合は何もしない
            if(file.type.indexOf("image") < 0){
              return false;
            }

            // ファイル読み込みが完了した際のイベント登録
            reader.onload = (function(file) {
              return function(e) {
                //既存のプレビューを削除
                $preview.empty();
                // .prevewの領域の中にロードした画像を表示するimageタグを追加
                $preview.css('background-image','url("' + e.target.result + '")')
              };
            })(file);

            reader.readAsDataURL(file);
          });
});

////////////////////////////////////////////////////////////////////////

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

$(function(){
    var $month = $('#manthSelectBox');
    var $day = $('#daySelectBox');
    var $year = $('#yearSelectBox');
    var originaldays = $day.html();

    $month.change(function(){
        var valy = $year.val();
        var valm = $(this).val();
        if(valm == "02" || valm == "04" || valm == "06" || valm == "09" || valm == "11"){
          $day.html(originaldays).find('option').each(function() {
              var vald = $(this).data('val');
              if (vald == "31") {
                  $(this).not(':first-child').remove();
                }
          });
        };
        //if(valy!="0000"){

        //};
    });
});
