@extends('admin.master')
@section('content')
<?php
$time_now= date('c',time());
DB::collection('user_meta')->where('fbid',$data['fbid'])->where('time_expired','<',$time_now)->where('active',1)->update(['active'=>0]);
$like = DB::collection('user_meta')->where('fbid',$data['fbid'])->where('type','like')->where('active',1)->orderBy('created_at','desc')->first();
$follow = DB::collection('user_meta')->where('fbid',$data['fbid'])->where('type','follow')->where('active',1)->orderBy('created_at','desc')->first();



?>
<div class="panel panel-primary">
    <div class="panel-heading">Sửa Vip ID</div>
    <input type="hidden" value="{{$data['_id']}}" id="id" />
    <div class="panel-body"> 
        <div class="content-1000 form-horizontal">
            <div class="form-group">
                <label class="col-md-2 control-label">FBID</label>
                <div class="col-md-10"><input type="text" value="{{$data['fbid']}}" id="fbid" class="form-control" disabled="" /></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Name</label>
                <div class="col-md-10"><input type="text" value="{{$data['name']}}" id="name" class="form-control" disabled="" /></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Money</label>
                <div class="col-md-10"><input type="text" value="{{$data['money']}}" disabled="" class="form-control" /></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Add Money</label>
                <div class="col-md-10"><input type="number" value="0" id="money" class="form-control" /></div>
            </div>
            <div class="form-group text-center">
                <button class="btn btn-danger" data-action="edit-user">Save</button>
            </div>
        </div>                
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">ADD VIP LIKES</div>
            <input type="hidden" value="{{$data['_id']}}" id="id" />
            <div class="panel-body"> 
                <div class="content-1000 form-horizontal">
                    <div class="form-group">
                        <label class="col-md-2 control-label">FBID</label>
                        <div class="col-md-10"><input type="text" value="{{$data['fbid']}}" class="form-control" disabled="" /></div>
                    </div>
                    @if($like)
                    <div class="form-group">
                        <label class="col-md-2 control-label">Time Expired</label>
                        <div class="col-md-10"><input type="text" value="{{date('d-m-Y H:i',strtotime($like['time_expired']))}}" disabled="" class="form-control" /></div>
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="col-md-2 control-label">Time(Day)</label>
                        <div class="col-md-10"><input type="text" id="time_like" value="0" class="form-control" /></div>
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-danger" data-action="addlike">Add Vip</button>
                    </div>
                </div>                
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">ADD VIP FOLLOWS</div>
            <input type="hidden" value="{{$data['_id']}}" id="id" />
            <div class="panel-body"> 
                <div class="content-1000 form-horizontal">
                    <div class="form-group">
                        <label class="col-md-2 control-label">FBID</label>
                        <div class="col-md-10"><input type="text" value="{{$data['fbid']}}" class="form-control" disabled="" /></div>
                    </div>
                    @if($follow)
                    <div class="form-group">
                        <label class="col-md-2 control-label">Time Expired</label>
                        <div class="col-md-10"><input type="text" value="{{date('d-m-Y H:i',strtotime($follow['time_expired']))}}" disabled="" class="form-control" /></div>
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="col-md-2 control-label">Time(Day)</label>
                        <div class="col-md-10"><input type="text" value="0" id="time_follow" class="form-control" /></div>
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-danger" data-action="addfollow">Add Vip</button>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>
<script>
$(document).on('click','[data-action="edit-user"]',function(){
    $(this).attr('disabled','disabled');
    var fbid = $('#fbid').val();
    var money = $('#money').val();
    $.post('/admin/edit-user',{fbid:fbid,money:money,_token:token})
    .done(function(data){
        show_toastr(data);
        location.reload();
    })
    .fail(function(data){
        show_toastr(data.responseJSON);
    })
    .always(function(){
        $('[data-action="edit-user"]').removeAttr('disabled');
    })
    
});

$(document).on('click','[data-action="addlike"]',function(){
    $(this).attr('disabled','disabled');
    var fbid = $('#fbid').val();
    var day = $('#time_like').val();
    $.post('/admin/addlike',{fbid:fbid,day:day,_token:token})
    .done(function(data){
        show_toastr(data);
        location.reload();
    })
    .fail(function(data){
        show_toastr(data.responseJSON);
    })
    .always(function(){
        $('[data-action="addlike"]').removeAttr('disabled');
    })
    
});
$(document).on('click','[data-action="addfollow"]',function(){
    $(this).attr('disabled','disabled');
    var fbid = $('#fbid').val();
    var day = $('#time_follow').val();
    $.post('/admin/addfollow',{fbid:fbid,day:day,_token:token})
    .done(function(data){
        show_toastr(data);
        location.reload();
    })
    .fail(function(data){
        show_toastr(data.responseJSON);
    })
    .always(function(){
        $('[data-action="addfollow"]').removeAttr('disabled');
    })
    
});

</script>
@endsection