@extends('wow.master')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div id="premium-like-countdown" class="alert alert-success" style="display: none" role="alert"> วีไอพีปั้มไลค์จะหมดในอีก
                <time id="countdown"></time>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-heart"></i> แลกวีไอพีปั้มไลค์</h3>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>จำนวนวันที่ได้</th>
                            <th>แต้มที่ต้องใช้</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>3 วัน</td>
                            <td><span class="label label-danger"><i class="fa fa-money"></i> 50</span> แต้ม </td>
                            <td>
                                <button type="button" data-exchange-day="3" data-exchange-point="50" data-exchange-type="like" class="btn btn-primary btn-xs exchange"><i class="fa fa-exchange"></i> แลก </button>
                            </td>
                        </tr>
                        <tr>
                            <td>7 วัน</td>
                            <td><span class="label label-danger"><i class="fa fa-money"></i> 90</span> แต้ม </td>
                            <td>
                                <button type="button" data-exchange-day="7" data-exchange-point="90" data-exchange-type="like" class="btn btn-primary btn-xs exchange"><i class="fa fa-exchange"></i> แลก </button>
                            </td>
                        </tr>
                        <tr>
                            <td>15 วัน</td>
                            <td><span class="label label-danger"><i class="fa fa-money"></i> 150</span> แต้ม </td>
                            <td>
                                <button type="button" data-exchange-day="15" data-exchange-point="150" data-exchange-type="like" class="btn btn-primary btn-xs exchange"><i class="fa fa-exchange"></i> แลก </button>
                            </td>
                        </tr>
                        <tr>
                            <td>30 วัน</td>
                            <td><span class="label label-danger"><i class="fa fa-money"></i> 300</span> แต้ม </td>
                            <td>
                                <button type="button" data-exchange-day="30" data-exchange-point="300" data-exchange-type="like" class="btn btn-primary btn-xs exchange"><i class="fa fa-exchange"></i> แลก </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="panel-body"> <a href="/topup" class="btn btn-primary btn-sm">เติมเงิน</a> </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title"><i class="fa fa-list"></i> <small>ประวัติการแลกวีไอพีปั้มไลค์</small>
                    </div>
                </div>
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>จำนวนวันที่ได้</th>
                            <th>จำนวนแต้มที่ใช้</th>
                            <th>เวลาที่แลก</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3"><i>ไม่มี</i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div id="premium-follow-countdown" class="alert alert-success" style="display: none" role="alert"> วีไอพีปั้มติดตามจะหมดในอีก
                <time id="countdown"></time>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-users"></i> แลกวีไอพีปั้มติดตาม</h3> </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>จำนวนวันที่ได้</th>
                            <th>แต้มที่ต้องใช้</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>3 วัน</td>
                            <td><span class="label label-danger"><i class="fa fa-money"></i> 50</span> แต้ม </td>
                            <td>
                                <button type="button" data-exchange-day="3" data-exchange-point="50" data-exchange-type="follow" class="btn btn-primary btn-xs exchange"><i class="fa fa-exchange"></i> แลก </button>
                            </td>
                        </tr>
                        <tr>
                            <td>7 วัน</td>
                            <td><span class="label label-danger"><i class="fa fa-money"></i> 90</span> แต้ม </td>
                            <td>
                                <button type="button" data-exchange-day="7" data-exchange-point="90" data-exchange-type="follow" class="btn btn-primary btn-xs exchange"><i class="fa fa-exchange"></i> แลก </button>
                            </td>
                        </tr>
                        <tr>
                            <td>15 วัน</td>
                            <td><span class="label label-danger"><i class="fa fa-money"></i> 150</span> แต้ม </td>
                            <td>
                                <button type="button" data-exchange-day="15" data-exchange-point="150" data-exchange-type="follow" class="btn btn-primary btn-xs exchange"><i class="fa fa-exchange"></i> แลก </button>
                            </td>
                        </tr>
                        <tr>
                            <td>30 วัน</td>
                            <td><span class="label label-danger"><i class="fa fa-money"></i> 300</span> แต้ม </td>
                            <td>
                                <button type="button" data-exchange-day="30" data-exchange-point="300" data-exchange-type="follow" class="btn btn-primary btn-xs exchange"><i class="fa fa-exchange"></i> แลก </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="panel-body"> <a href="/topup" class="btn btn-primary btn-sm">เติมเงิน</a> </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title"><i class="fa fa-list"></i> <small>ประวัติการแลกวีไอพีปั้มติดตาม</small>
                    </div>
                </div>
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>จำนวนวันที่ได้</th>
                            <th>จำนวนแต้มที่ใช้</th>
                            <th>เวลาที่แลก</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3"><i>ไม่มี</i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirm-exchange" aria-hidden="true" style="">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">ยืนยันการแลก</h4>
                </div>
                <div class="modal-body">ยืนการการแลกวีไอพีปั้มไลค์ 3 วัน ด้วยแต้ม 50 แต้ม</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-primary confirm">ตกลง</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).on('click','.exchange',function(){
    day = $(this).data('exchange-day');
    point = $(this).data('exchange-point');
    type = $(this).data('exchange-type');
    $('#confirm-exchange').find('.modal-body').html('ยืนการการแลกวีไอพีปั้มไลค์ '+day+' วัน ด้วยแต้ม '+point+' แต้ม')
    $('#confirm-exchange').modal('show');
    /*$.post('/exchange',{day:day,point:point,type:type,_token:csrf_token})
    .done((data)=>{

    })*/
})
$(document).on('click','.confirm',function(){
    $.post('/exchange',{day:day,point:point,type:type,_token:csrf_token})
    .always((data)=>{
        show_toastr(data);
    })
})
</script>
@endsection