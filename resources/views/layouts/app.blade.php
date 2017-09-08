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
        <script src="https://code.jquery.com/jquery-1.10.2.min.js" type="text/javascript" language="javascript"></script>
        <script src="../common/js/bootstrap.js"></script>
        <link rel="stylesheet" href="../common/css/bootstrap.css">
-->
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="bootstrap-datepicker/css/bootstrap-datepicker.min.css">
        <script type="text/javascript" src="bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js"></script>

    </head>
    <body>
        @include('commons.navbar')

        <div class="container">
            @include('commons.error_messages')

            @yield('content')
        </div>
    </body>
</html>
