<!doctype html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Language" content="en" />
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="theme-color" content="#4188c9">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <link rel="icon" href="./favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" />
    <title>Homepage - tabler.github.io - a responsive, flat and full featured admin template</title>
    <link rel="stylesheet" href="/cdn/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="/cdn/bootstrap/css/bootstrap.min.css" />
    <script src="/js/jquery-3.2.1.min.js"></script>
    <script src="/cdn/bootstrap/js/bootstrap.min.js"></script>   
    <link rel="stylesheet" href="/cdn/bootstrap/css/todc-bootstrap.min.css" />
    <link rel="stylesheet" href="{{asset('assets/css/wow-lucdz.css')}}" />
  </head>
  <body class="">
    @include('wow.layouts.header')
    @yield('content')
    @include('wow.layouts.footer')
  </body>
</html>