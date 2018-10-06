<!doctype html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta http-equiv="Content-Language" content="en" />
    <meta name="msapplication-TileColor" content="#2d89ef" />
    <meta name="theme-color" content="#4188c9" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="HandheldFriendly" content="True" />
    <meta name="MobileOptimized" content="320" />
    <meta name="csrf-token" content="{{csrf_token()}}" />
    <link rel="icon" href="./favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" />
    <title>Homepage - tabler.github.io - a responsive, flat and full featured admin template</title>
    <link rel="stylesheet" href="/cdn/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="/cdn/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/cdn/toastr/toastr.min.css" />
    <link rel="stylesheet" href="/cdn/bootstrap/css/todc-bootstrap.min.css" />
    <link rel="stylesheet" href="{{asset('assets/css/wow-lucdz.css')}}?v=<?=rand(99999,99999999999999)?>" />
    <script src="/js/jquery-3.2.1.min.js"></script>
    <script src="/js/wowlike.js?v=<?=rand(99999,99999999999999)?>"></script>
    <script src="/cdn/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timeago/1.4.0/jquery.timeago.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="/cdn/toastr/toastr.min.js" type="text/javascript"></script>
    <script src="/js/countdown.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://www.tmtopup.com/topup/3rdTopup.php?uid=218164"></script> 
  </head>
  <body class="">
    <script>
        $(document).ready(()=>{
            $('#navbar-main').find('.active').remove('.active');
            $.each($('#navbar-main ul li a'),(k,v)=>{
                var nav = $(v).attr('href');
                var path = '/<?=Request::path()?>';
                if(path == '/likes' || path == '/reactions'){
                    $(v).parent().addClass('active');
                    return false;
                }else if(path == nav){
                    $(v).parent().addClass('active');
                    return false;
                }
                
            });
        });
    </script>
    @include('wow.layouts.header')
    @yield('content')
    @include('wow.layouts.footer')
    <!-- Modal -->
      <div class="modal fade" id="getToken" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">GET TOKEN</h4>
            </div>
            <div class="modal-body">
                <div class="input-group form-group col-md-12">
                    <span class="input-group-addon addon-register" id="basic-addon1">Username</span>
                    <input type="text" class="form-control" id="username" placeholder="Username / Email" aria-label="Re Password" aria-describedby="basic-addon1">
                </div>
                <div class="input-group form-group col-md-12">
                    <span class="input-group-addon addon-register" id="basic-addon1">Password</span>
                    <input type="password" class="form-control" id="password" placeholder="**********" aria-label="Re Password" aria-describedby="basic-addon1">
                </div>
                <div class="input-group form-group col-md-12">
                    <span class="input-group-addon addon-register" id="basic-addon1">Select App</span>
                    <select type="password" class="form-control" id="fbapp" placeholder="**********" aria-label="Re Password" aria-describedby="basic-addon1">
                         <option value="android">Facebook for Android</option>
			             <option value="iphone">Facebook for iPhone</option>
                    </select>
                </div>
                <div class="text-center">
                    <button class="btn btn-danger" onclick="getToken()">GET</button>
                </div>
                <div id="result"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    <!-- Modal -->
      <div class="modal fade" id="video_ytb" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">สอนปั้มไลค์ บนมือถือ</h4>
            </div>
            <div class="modal-body">
                <iframe width="100%" height="450" src="https://www.youtube.com/embed/BRhkLk4ctsk" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <style>
        #getToken{
            background: #333333a6;
        }
        #result iframe{
            width: 100%;
            border: 1px solid #dddd;
            box-shadow: 0px 0px 5px 0px #350;
            margin-top: 35px;
        }
        .ui-dialog{
                left: 40% !important;
                top: 25%;
        }
      </style>
  </body>
</html>