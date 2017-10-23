<?php
    $username = 'root';
    $password = '';
    $DB_CONNECTION = 'pgsql';
    $DB_DATABASE = 'dajmgrpqvdjoar';
    $DB_HOST = "ec2-54-235-250-15.compute-1.amazonaws.com options='--client_encoding=UTF8'";
    $DB_PASSWORD = '3604b9e39c1afedc5438cd41de8f68f885cad0cd5494110ac31e1e2f6ae22a64';
    $DB_USERNAME = 'pmgorahtvfbzzp';
    $database = new PDO('pgsql:host='.$DB_HOST.';dbname='.$DB_DATABASE.';', $DB_USERNAME, $DB_PASSWORD);

    $sql = 'DELETE FROM prefs';
    $statement = $database->prepare($sql);
    $statement->execute();
    $statement = null;

    $sql = 'DELETE FROM locations';
    $statement = $database->prepare($sql);
    $statement->execute();
    $statement = null;


    $pref_id = [
      '01'=>'北海道',
      '02'=>'青森県',
      '03'=>'岩手県',
      '04'=>'宮城県',
      '05'=>'秋田県',
      '06'=>'山形県',
      '07'=>'福島県',
      '08'=>'茨城県',
      '09'=>'栃木県',
      '10'=>'群馬県',
      '11'=>'埼玉県',
      '12'=>'千葉県',
      '13'=>'東京都',
      '14'=>'神奈川県',
      '15'=>'新潟県',
      '16'=>'富山県',
      '17'=>'石川県',
      '18'=>'福井県',
      '19'=>'山梨県',
      '20'=>'長野県',
      '21'=>'岐阜県',
      '22'=>'静岡県',
      '23'=>'愛知県',
      '24'=>'三重県',
      '25'=>'滋賀県',
      '26'=>'京都府',
      '27'=>'大阪府',
      '28'=>'兵庫県',
      '29'=>'奈良県',
      '30'=>'和歌山県',
      '31'=>'鳥取県',
      '32'=>'島根県',
      '33'=>'岡山県',
      '34'=>'広島県',
      '35'=>'山口県',
      '36'=>'徳島県',
      '37'=>'香川県',
      '38'=>'愛媛県',
      '39'=>'高知県',
      '40'=>'福岡県',
      '41'=>'佐賀県',
      '42'=>'長崎県',
      '43'=>'熊本県',
      '44'=>'大分県',
      '45'=>'宮崎県',
      '46'=>'鹿児島県',
      '47'=>'沖縄県',
        ];

    foreach($pref_id as $key => $pref_name ){
        $sql = 'INSERT INTO prefs (pref_id,pref_name) Value (:pref_id,:pref_name)';
        $statement = $database->prepare($sql);
        $statement->bindParam(':pref_id', $key);
        $statement->bindParam(':pref_name', $pref_name);
        $statement->execute();
        $statement = null;
    }

    $base_url = 'http://www.land.mlit.go.jp/webland/api/CitySearch?';

    for($pref_id=1;$pref_id<=47;$pref_id++){
    $idPad = str_pad($pref_id,2,0,STR_PAD_LEFT);
    $query = ['area'=>$idPad];

    $response = file_get_contents(
              $base_url . http_build_query($query)
        );
    $response_utf8 = mb_convert_encoding($response, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
    $result = json_decode($response_utf8,true);
    $json_count = count($result["data"]);

    for($city_cnt = 0;$city_cnt<=$json_count-1;$city_cnt++){
    $sql = 'INSERT INTO locations (pref_id,city_id,city_name) Value (:pref_id,:city_id,:city_name)';
    $statement = $database->prepare($sql);
    $statement->bindParam(':pref_id', $idPad);
    $statement->bindParam(':city_id', $result['data'][$city_cnt]['id']);
    $statement->bindParam(':city_name', $result['data'][$city_cnt]['name']);
    $statement->execute();
    $statement = null;
    }}


    $database = null;
?>
