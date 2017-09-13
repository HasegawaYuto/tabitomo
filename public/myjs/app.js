$(function(){
    $ABC = $('#avatarBeforeChangeArea');
    $AAC = $('#avatarAfterChangeArea');
    $ABC.css('height',$ABC.css('width'));
    $ABC.css({'background-position':'center',
              'background-repeat':'no-repeat',
              'background-size':'cover'
        });
    $AAC.css('height',$ABC.css('width'));
    $AAC.css({'background-position':'center',
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
