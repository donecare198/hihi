@extends('admin.master')
@section('content')

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">Setting</div>
            <div class="panel-body"> 
                <div class="content-1000 form-horizontal" id="form_setting">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Member likes</label>
                        <div class="col-md-10"><input type="text" id="member_like" class="form-control" value="{{$data['auto']['member_like']}}" /></div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Member time</label>
                        <div class="col-md-10"><input type="text" id="time_member_like" class="form-control" value="{{$data['auto']['time_member_like']}}" /></div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Vip likes</label>
                        <div class="col-md-10"><input type="text" id="vip_like" class="form-control" value="{{$data['auto']['vip_like']}}" /></div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Vip time</label>
                        <div class="col-md-10"><input type="text" id="time_vip_like" class="form-control" value="{{$data['auto']['time_vip_like']}}" /></div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Member follows</label>
                        <div class="col-md-10"><input type="text" id="member_follow" class="form-control" value="{{$data['auto']['member_follow']}}" /></div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Member follows</label>
                        <div class="col-md-10"><input type="text" id="time_member_follow" class="form-control" value="{{$data['auto']['time_member_follow']}}" /></div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Vip follows</label>
                        <div class="col-md-10"><input type="text" id="vip_follow" class="form-control" value="{{$data['auto']['vip_follow']}}" /></div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Vip follows</label>
                        <div class="col-md-10"><input type="text" id="time_vip_follow" class="form-control" value="{{$data['auto']['time_vip_follow']}}" /></div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Member Likes Perday</label>
                        <div class="col-md-10"><input type="text" id="member_like_perday" class="form-control" value="{{$data['auto']['member_like_perday']}}" /></div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Vip Likes Perday</label>
                        <div class="col-md-10"><input type="text" id="vip_like_perday" class="form-control" value="{{$data['auto']['vip_like_perday']}}" /></div>
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-danger" onclick="update_setting()">Save</button>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>
<script>
    function update_setting(){
        var data = {
                member_like : $('#member_like').val(),
                time_member_like : $('#time_member_like').val(),
                vip_like : $('#vip_like').val(),
                time_vip_like : $('#time_vip_like').val(),
                member_follow : $('#member_follow').val(),
                time_member_follow : $('#time_member_follow').val(),
                vip_follow : $('#vip_follow').val(),
                time_vip_follow : $('#time_vip_follow').val(),
                member_like_perday : $('#member_like_perday').val(),
                vip_like_perday : $('#vip_like_perday').val(),
                _token : token 
            }
            $.post('/admin/setting_w',data,function(data){
                show_toastr(data);
            });
    }
</script>
@endsection