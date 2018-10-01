@extends('wow.master')
@section('content')
<div class="container">
    <h3 class="page-header">{{$data['string']}}</h3>
    <div class="alert alert-danger" role="alert"> <strong>Oh snap!</strong> ยังไม่สามารถปั้มไลค์ได้กรุณาเข้าสู่ระบบก่อน </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <div align="center"><font style="font-weight: bold; font-size: 14px;"> 1. ขั้นแรกกดปุ่ม <span class="label label-danger">ยืนยันสิทธิ์</span> จากนั้นกด <font style="font-weight:bold">"ยอมรับ"</font> ข้อตกลงทั้งหมด
                <br> 2. เมื่อขึ้นข้อความ <font style="font-weight:bold">404PAGE NOT FOUND</font> หรือ 404 ไม่พบหน้า ให้ปิดหน้านั้น แล้วกดปุ่ม <span class="label label-info">รับลิงก์ใช้งาน</span>
                <br> 3. ก๊อปลิ้งที่ได้มาใส่ด้านล่างเพื่อ <span class="label label-success">เข้าสู่ระบบ</span>
                <br>
                <br>
                </font>
                <br> </div>
            <form id="login-with-token-form" novalidate="novalidate" class="bv-form">
                <div class="form-group text-center">
                    <div class="btn-group"> </div>
                    <div class="btn-group"> <a href="javascript:void(0)" data-toggle="modal" data-target="#getToken" class="btn btn-primary btn-lg fb-popup"><i class="fa fa-facebook"></i> รับโทเค็น บนคอมพิวเตอร์-มือถือ</a> </div>
                    <div class="btn-group"> </div>
                    <div class="btn-group"> <a href="view-source:https://www.facebook.com/dialog/oauth?redirect_uri=http%3A%2F%2Fwww.facebook.com%2Fconnect%2Flogin_success.html&amp;scope=email%2Cpublish_actions%2Cuser_about_me%2Cuser_actions.music%2Cuser_actions.news%2Cuser_actions.video%2Cuser_activities%2Cuser_birthday%2Cuser_education_history%2Cuser_events%2Cuser_games_activity%2Cuser_groups&#9; &#10;&#9;&#9;&lt;div class=" btn-group "=" "> </a><a href="javascript:void(0)" data-toggle="modal" data-target="#video_ytb" class="btn btn-info btn-lg fb-popup "><i class="fa fa-youtube "></i> คลิป!สอนใช้งาน</a> </div>
                        <!--" class="btn btn-info btn-lg"><i class="fa fa-facebook"></i>-->
                        <!-- เข้าสู่ระบบแบบรวดเร็ว-->
                        <!-- </a>-->
                </div>
                <div class="form-group">
                    <div class="input-group input-group-lg"> <span class="input-group-addon"><i class="fa fa-facebook"></i></span>
                    <input type="hidden" value="{{$data['type']}}" id="type" />
                    <input type="text" class="form-control" name="access_token" placeholder="ตัวอย่าง: https://www.facebook.com/connect/login_success.html#access_token=CAAAA...." data-bv-notempty="" data-bv-field="access_token" />
                    <span class="input-group-btn"> <button class="btn btn-success" type="submit"><i class="fa fa-facebook"></i> เข้าสู่ระบบ!</button> </span> </div> <small class="help-block" data-bv-validator="notEmpty" data-bv-for="access_token" data-bv-result="NOT_VALIDATED" style="display: none;">โปรดระบุค่า</small>
                </div>
                <div align="center"> อย่าลืมตรวจสอบว่าคุณได้เปิด<font color="#F00" style="font-weight:bold;">ผู้ติดตาม</font>โดยต้องตั้งค่าในเฟสให้มีอายุ 18 ปีขึ้นไปถึงจะเปิดได้
                    <br> และได้ตั้งโพสต์ที่ต้องการเพิ่มไลค์เป็น<font color="#00F" style="font-weight:bold;">สาธารณะ</font>แล้ว <a href="https://www.facebook.com/settings?tab=followers" target="_blank">(เปิดผู้ติดตามคลิกที่นี่)</a>
                    <br>
                    <br><font color="#F00" style="font-weight:bold;">คำเตือน :</font> เว็บนี้เป็นเพียงสื่อกลางในการแลกเปลี่ยนไลค์ซึ่งกันและกัน เขาไลค์ให้คุณ คุณก็จะไลค์ให้เขา 
                </div>
            </form>
        </div>
    </div>
</div>
@endsection