<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>旅とも</title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!--
AIzaSyBQb3VgyoduOCh4x0clSJxw8yuzQvd1Zkw
        <script src="https://code.jquery.com/jquery-1.10.2.min.js" type="text/javascript" language="javascript"></script>
        <script src="../common/js/bootstrap.js"></script>
        <link rel="stylesheet" href="../common/css/bootstrap.css">
-->
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{asset('bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}">
        <script type="text/javascript" src="{{asset('bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js')}}"></script>

        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBQb3VgyoduOCh4x0clSJxw8yuzQvd1Zkw"></script>

        <link rel="stylesheet" href="{{asset('raty/lib/jquery.raty.css')}}">
        <script src="{{asset('raty/lib/jquery.raty.js')}}"></script>

        <script src="{{asset('lazysizes/lazysizes.min.js')}}" async=""></script>

        <script>
            document.addEventListener('lazybeforeunveil', function(e){
            var bg = e.target.getAttribute('data-bg');
            if(bg){
                e.target.style.backgroundImage = 'url(' + bg + ')';
            }
        });
        </script>

        <script src="{{asset('myjs/app.js')}}"></script>
        <link rel="stylesheet" type="text/css" href="{{asset('mycss/app.css')}}">
    </head>
    <body>
        @include('commons.navbar')

        <div class="container">
            @include('commons.error_messages')

            @yield('content')
        </div>
    </body>
</html>
