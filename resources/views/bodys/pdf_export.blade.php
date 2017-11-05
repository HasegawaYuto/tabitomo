<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja">
<head>
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta name="Keywords" content="" />
<meta name="Description" content="" />
<title></title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style>
.wrap{
    word-wrap: break-word;
}
</style>
</head>
    <body>
        <div class="container">
        <h4 class="text-center">{{$plan->title}}</h4>
        <?php
            function replaceDateRev($date){
                $datearr = explode('-',$date);
                return $datearr[0].'年'.(int)$datearr[1].'月'.(int)$datearr[2].'日';
            }
        ?>
        <p class="text-right">{{replaceDateRev($plan->updated_at)}}</p>
        <p class="text-right">{{$user->name}}</p>
        <div class="row">
        <div class="col-xs-6 wrap">
            【旅行日】
            @if($plan->firstday != $plan->lastday)
                <p>{{replaceDateRev($plan->firstday)}}～{{replaceDateRev($plan->lastday)}}</p>
            @else
                <p>{{replaceDateRev($plan->firstday)}}</p>
            @endif
            【諸連絡】
        <p>{{$plan->describe}}</p>
        </div>
        <div class="col-xs-6">
            <h5>【道程】</h5>
            @if($plan->point!="")
            <?php
                $points = explode('->',$plan->point);
            ?>
            @foreach($points as $point)
                <?php
                    $place = explode(':::::',$point);
                ?>
                <p>{{$place[0]}}</p>
                <p class="wrap" style="margin-left:20px;">{{$place[3]}}</p>
            @endforeach
            @else
                未設定
            @endif
        </div>
        </div>
        <div id="QRdiv"></div>
        <input type="hidden" id="URL" value="{{$URL}}">
        </div>
    </body>
</html>