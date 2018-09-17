@extends('admin.master')
@section('content')
<div class="panel panel-primary">
    <div class="panel-heading">Sửa Vip ID</div>
    <input type="hidden" value="{{$data->id}}" id="id" />
    <div class="panel-body"> 
        <div class="content-1000 form-horizontal">
            <div class="form-group">
                <label class="col-md-2 control-label">FBID</label>
                <div class="col-md-10"><input type="text" value="{{$data->fbid}}" id="fbid" class="form-control" /></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">FB Name <i style="color: red; cursor: pointer;" class="fa fa-refresh" onclick="updatename()"></i></label>
                <div class="col-md-10"><input type="text" value="{{$data->fbname}}" id="fbname" class="form-control" /></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Thời gian</label>
                <div class="col-md-10">
                    <select id="thoigian" class="form-control">
                        <option value="1" {{$data->thoigian == 1 ? "selected" : ""}} >1 Tháng</option>
                        <option value="2" {{$data->thoigian == 2 ? "selected" : ""}}>2 Tháng</option>
                        <option value="3" {{$data->thoigian == 3 ? "selected" : ""}}>3 Tháng</option>
                        <option value="4" {{$data->thoigian == 4 ? "selected" : ""}}>4 Tháng</option>
                        <option value="5" {{$data->thoigian == 5 ? "selected" : ""}}>5 Tháng</option>
                        <option value="6" {{$data->thoigian == 6 ? "selected" : ""}}>6 Tháng</option>
                        <option value="7" {{$data->thoigian == 7 ? "selected" : ""}}>7 Tháng</option>
                        <option value="8" {{$data->thoigian == 8 ? "selected" : ""}}>8 Tháng</option>
                        <option value="9" {{$data->thoigian == 9 ? "selected" : ""}}>9 Tháng</option>
                        <option value="10" {{$data->thoigian == 10 ? "selected" : ""}}>10 Tháng</option>
                        <option value="11" {{$data->thoigian == 11 ? "selected" : ""}}>11 Tháng</option>
                        <option value="12" {{$data->thoigian == 12 ? "selected" : ""}}>12 Tháng</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Cảm xúc / lần chạy</label>
                <div class="col-md-10"><input type="number" min="50" value="{{$data->limit}}" max="500" id="limit" class="form-control" /></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">FBID Nhận thông báo(Không bắt buộc)</label>
                <div class="col-md-10">
                    <input type="text" id="fbid_notification" value="{{$data->fbid_notification}}" class="form-control" />
                    <label><input type="checkbox" id="check_notification" {{$data->thongbao == "true" ? "checked" : ""}} /> Nhận thông khi VIP hết hạn</label>
                    <a href="javascript:void(0)" class="btn-success" data-action="TestNotification" title="Test thông báo"> Click test thông báo</a>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Gói cảm xúc</label>
                <div class="col-md-10">
                    <select id="goi" class="form-control">
                        <option value="1" {{$data->goi == 1 ? "selected" : ""}} >100 CX</option>
                        <option value="2" {{$data->goi == 2 ? "selected" : ""}} >200 CX</option>
                        <option value="3" {{$data->goi == 3 ? "selected" : ""}} >300 CX</option>
                        <option value="4" {{$data->goi == 4 ? "selected" : ""}} >400 CX</option>
                        <option value="5" {{$data->goi == 5 ? "selected" : ""}} >500 CX</option>
                        <option value="6" {{$data->goi == 6 ? "selected" : ""}} >600 CX</option>
                        <option value="7" {{$data->goi == 7 ? "selected" : ""}} >700 CX</option>
                        <option value="8" {{$data->goi == 8 ? "selected" : ""}} >800 CX</option>
                        <option value="9" {{$data->goi == 9 ? "selected" : ""}} >900 CX</option>
                        <option value="10" {{$data->goi == 10 ? "selected" : ""}} >1000 CX</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-md-2 control-label">Loại cảm xúc</label>
                <div class="col-md-10 reaction">
                    <div class="icon_reaction {{isset($data["reaction"]["LIKE"]) ? "active" : ""}}">
                        <img src="{{url('images/like.gif')}}" data-type="LIKE" alt="LIKE" title="LIKE" />
                    </div>
                    <div class="icon_reaction {{isset($data["reaction"]["LOVE"]) ? "active" : ""}}">
                        <img src="{{url('images/love.gif')}}" data-type="LOVE" alt="LOVE" title="LOVE" />
                    </div>
                    <div class="icon_reaction {{isset($data["reaction"]["HAHA"]) ? "active" : ""}}">
                        <img src="{{url('images/haha.gif')}}" data-type="HAHA" alt="HAHA" title="HAHA" />
                    </div>
                    <div class="icon_reaction {{isset($data["reaction"]["WOW"]) ? "active" : ""}}">
                        <img src="{{url('images/wow.gif')}}" data-type="WOW" alt="WOW" title="WOW" />
                    </div>
                    <div class="icon_reaction {{isset($data["reaction"]["SAD"]) ? "active" : ""}}">
                        <img src="{{url('images/sad.gif')}}" data-type="SAD" alt="SAD" title="SAD" />
                    </div>
                    <div class="icon_reaction {{isset($data["reaction"]["ANGRY"]) ? "active" : ""}}">
                        <img src="{{url('images/angry.gif')}}" data-type="ANGRY" alt="ANGRY" title="ANGRY" />
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-md-2 control-label">Last update:</label>
                <div class="col-md-10 reaction">
                    <span style=" padding-top: 7px; margin-bottom: 0; display: block; "> {{ Carbon\Carbon::parse($data->updated_at)->format('h:i:s d-m-Y') }}</span>
                </div>
            </div>
            <div class="form-group text-center">
                <button class="btn btn-danger" data-viplike-action="save-edit">Lưu lại</button>
            </div>
        </div>                
    </div>
</div>
<script>
    function updatename(){
        $.get('https://api.likedao.biz/getfbid?link=https://www.facebook.com/{{$data->fbid}}',function(data){
            $('#fbname').val(data.name);
        })
    };
</script>
@endsection