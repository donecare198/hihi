@extends('wow.master')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ราคาบัตร <i class="fa fa-btc"></i>
                            </th>
                            <th>แต้มที่ได้ <i class="fa fa-money"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>50 บาท</td>
                            <td><span class="label label-default"><i class="fa fa-money"></i> 60</span> </td>
                        </tr>
                        <tr>
                            <td>90 บาท</td>
                            <td><span class="label label-warning"><i class="fa fa-money"></i> 100</span> </td>
                        </tr>
                        <tr>
                            <td>150 บาท</td>
                            <td><span class="label label-danger"><i class="fa fa-money"></i> 160</span> </td>
                        </tr>
                        <tr>
                            <td>300 บาท</td>
                            <td><span class="label label-info"><i class="fa fa-money"></i> 400</span> </td>
                        </tr>
                        <tr>
                            <td><b>500</b> บาท</td>
                            <td><span class="label label-primary"><i class="fa fa-money"></i> 650</span> </td>
                        </tr>
                        <tr>
                            <td><b>1000</b> บาท</td>
                            <td><span class="label label-success"><i class="fa fa-money"></i> 1050</span> </td>
                        </tr>
                    </tbody>
                </table>
                <div class="panel-body text-right"> <a href="/exchange" class="btn btn-primary btn-sm">แลกวีไอพี</a> </div>
            </div>
            <div class="panel panel-default">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>รหัสบัตร</th>
                            <th>ราคาบัตร</th>
                            <th>แต้มที่ได้</th>
                            <th>เวลาที่เงินเข้าระบบ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4"><i>ไม่มี</i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="alert alert-info" role="alert"> แต้มคงเหลือของคุณ <i class="fa fa-money"></i> <b>0</b> แต้ม </div>
            <div class="alert alert-danger" role="alert"> หากมีการมั่วรหัสบัตร์ เราจะทำการแบน IP ทันทีโดยท่านจะไม่สามารถใช้งานเว็บไซต์ได้อีก!! </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-8 col-xs-offset-2"> <img src="/images/truemoney.jpg" alt="" style="max-width: 100%"> </div>
                    </div>
                    <div id="topup" role="form" novalidate="novalidate" class="bv-form">
                        <input type="hidden" value="{{Auth::guard('home')->user()->_id}}" name="ref1" id="ref1" maxlength="50">
                        <input type="hidden" value="{{Auth::guard('home')->user()->name}}" name="ref2" id="ref2" maxlength="50">
                        <input type="hidden" value="{{Auth::guard('home')->user()->name}}" name="ref2" id="ref3" maxlength="50">
                        <div class="form-group">
                            <label for="username">ผู้เติมเงิน</label>
                            <input type="text" class="form-control" disabled="" value="{{Auth::guard('home')->user()->name}}" />
                        </div>
                        <div class="form-group">
                            <label for="tmn_password">รหัสบัตรทรูมันนี่</label>
                            <input type="text" class="form-control input-lg" placeholder="รหัสบัตรทรูมันนี่ 14 หลัก" name="tmn_password" id="tmn_password" maxlength="14" minlength="14" />
                        </div>
                        <button type="submit" onclick="submit_tmnc()" class="btn btn-primary btn-lg">เติมเงิน </button>
                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirm-topup">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">ยืนยันการเติมเงิน</h4> </div>
                <div class="modal-body"> ท่านแน่ใจหรือไม่? ที่จะเติมเงินบัตรเงินสดของท่านจะถูกใช้งานทันทีและไม่สามารถแก้ไขหรือยกเลิกรายการได้ </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-primary confirm">ตกลง</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection