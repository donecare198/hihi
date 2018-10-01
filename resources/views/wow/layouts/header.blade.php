<div class="navbar navbar-default navbar-static-top">
    <div id="loading">
        <i style="color: #e74c3c;" class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i>
    </div>
    <div class="container">
        <div class="navbar-header">
            <a href="/" class="navbar-brand"><i class="fa fa-facebook-square"></i> wowlike</a>
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
            <ul class="nav navbar-nav">
                <li>
                    <a href="/"><i class="fa fa-home"></i> หน้าแรก</a>
                </li>
                <li class=""><a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-thumbs-up"></i> ปั้มไลค์ <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="divider"></li>
                        <li>
                            <a href="/likes"><img src="/images/like.gif" width="24"> ปั้มไลค์
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="/reactions"><img src="/images/love.gif" width="24"> ปั้มอีโมจิ
                            </a>
                        </li>
                        <li class="divider"></li>
                    </ul>
                </li>

                <li class="">
                    <a href="/follows"><i class="fa fa-user"></i> ปั้มติดตาม</a>
                </li>

                <li class="">
                    <a href="/buyfollow"><i class="fa fa-caret-square-o-right"></i> จ้างทางเราปั้มติดตาม</a>
                </li>
                <li class="">
                    <a href="/auto"><i class="fa fa-caret-square-o-right"></i> ปั้มไลค์อัตโนมัติ</a>
                </li>
                <li class="">
                    <a href="javascript:void(0)" data-toggle="modal" data-target="#video_ytb" ><i class="fa fa-caret-square-o-right"></i> วิธีใช้งานปั้มไลค์</a>
                </li>
                <li class="">
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="/exchange"><i class="fa fa-exchange"></i> แลกวิไอพี</a>
                </li>
                <li class="">
                    <a href="/topup"><i class="fa fa-credit-card"></i> เติมเงิน</a>
                </li>
                @if(!Auth::guard('home')->check())
                    <li>
                        <a href="/login"><i class="fa fa-sign-in"></i>
    							เข้าสู่ระบบ</a>
                    </li>
                @else
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="">{{Auth::guard('home')->user()->name}} <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/logout" id="logout"><i class="fa fa-sign-out"></i>
                									ออกจากระบบ</a>
                        </li>
                    </ul>
                
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>
