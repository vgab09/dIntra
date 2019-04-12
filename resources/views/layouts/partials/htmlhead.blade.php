<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>uIntra - @yield('title', env('APP_NAME')) </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', '')">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <link rel="apple-touch-icon" href="{{asset('images/apple-icon.png')}}">
    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">

    <link href="{{ mix('/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ mix('/css/fontawesome.css') }}" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <link href="{{ mix('/css/style.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ mix('/css/chosen/chosen.min.css') }}" rel="stylesheet" type="text/css"/>

    @stack('stylesheet')
    @stack('headJavascript')
</head>