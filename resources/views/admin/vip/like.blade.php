@extends('admin.master')
@section('content')
<div class="panel panel-primary">
    <div class="panel-heading">Thêm Vip ID</div>
    <div class="panel-body"> 
        <div class="content-1000 form-horizontal">
            <div class="form-group">
                <label class="col-md-2 control-label">FBID</label>
                <div class="col-md-10"><input type="text" id="fbid" class="form-control" /></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">FB Name</label>
                <div class="col-md-10"><input type="text" id="fbname" class="form-control" /></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Thời gian</label>
                <div class="col-md-10">
                    <select id="thoigian" class="form-control">
                        <option value="1">1 Tháng</option>
                        <option value="2">2 Tháng</option>
                        <option value="3">3 Tháng</option>
                        <option value="4">4 Tháng</option>
                        <option value="5">5 Tháng</option>
                        <option value="6">6 Tháng</option>
                        <option value="7">7 Tháng</option>
                        <option value="8">8 Tháng</option>
                        <option value="9">9 Tháng</option>
                        <option value="10">10 Tháng</option>
                        <option value="11">11 Tháng</option>
                        <option value="12">12 Tháng</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Cảm xúc / lần chạy</label>
                <div class="col-md-10"><input type="number" min="50" value="50" max="500" id="limit" class="form-control" /></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">FBID Nhận thông báo(Không bắt buộc)</label>
                <div class="col-md-10">
                    <input type="text" id="fbid_notification" class="form-control" />
                    <label><input type="checkbox" id="check_notification" /> Nhận thông khi VIP hết hạn</label>
                    <a href="javascript:void(0)" class="btn-success" data-action="TestNotification" title="Test thông báo"> Click test thông báo</a>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Gói cảm xúc</label>
                <div class="col-md-10">
                    <select id="goi" class="form-control">
                        <option value="1">100 CX</option>
                        <option value="2">200 CX</option>
                        <option value="3">300 CX</option>
                        <option value="4">400 CX</option>
                        <option value="5">500 CX</option>
                        <option value="6">600 CX</option>
                        <option value="7">700 CX</option>
                        <option value="8">800 CX</option>
                        <option value="9">900 CX</option>
                        <option value="10">1000 CX</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Loại cảm xúc</label>
                <div class="col-md-10 reaction">
                    <div class="icon_reaction active">
                        <img src="{{url('images/like.gif')}}" data-type="LIKE" alt="LIKE" title="LIKE" />
                    </div>
                    <div class="icon_reaction">
                        <img src="{{url('images/love.gif')}}" data-type="LOVE" alt="LOVE" title="LOVE" />
                    </div>
                    <div class="icon_reaction">
                        <img src="{{url('images/haha.gif')}}" data-type="HAHA" alt="HAHA" title="HAHA" />
                    </div>
                    <div class="icon_reaction">
                        <img src="{{url('images/wow.gif')}}" data-type="WOW" alt="WOW" title="WOW" />
                    </div>
                    <div class="icon_reaction">
                        <img src="{{url('images/sad.gif')}}" data-type="SAD" alt="SAD" title="SAD" />
                    </div>
                    <div class="icon_reaction">
                        <img src="{{url('images/angry.gif')}}" data-type="ANGRY" alt="ANGRY" title="ANGRY" />
                    </div>
                </div>
            </div>
            <div class="form-group text-center">
                <button class="btn btn-danger" data-viplike-action="install">Cài VIP</button>
            </div>
        </div>                
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">Quản Lý VIP ID</div>
    <div class="panel-body"> 
        <table id="vipid" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>FBID - FB Name</th>
                    <th>Gói</th>
                    <th>Thời Gian</th>
                    <th>Ngày Thêm</th>
                    <th>Ngày Hết Hạn</th>
                    <th>Trạng Thái</th>
                    <th>Công Cụ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $key=>$d)
                    <tr>
                        <td style="text-align: center;">{{$key+1}}</td>
                        <td data-id="{{$d->_id}}"><a href="https://fb.com/{{$d->fbid}}" target="_blank">{{$d->fbid}}</a> - {{$d->fbname}}</td>
                        <td>
                            {{$d->goi * 100}} CX
                        </td>
                        <td>{{$d->thoigian}} Tháng</td>
                        <td>{{ Carbon\Carbon::parse($d->created_at)->format('d-m-Y') }}</td>
                        <td>{{ Carbon\Carbon::parse($d->created_at->modify("+$d->thoigian month"))->format('d-m-Y') }}</td>
                        <td>
                            @if($d->active == 1)<span class="text-success">Hoạt động</span>
                            @else
                            <span class="text-danger">Không Hoạt Động</span>
                            @endif
                        </td>
                        <td>
                            <a href="/admin/viplike/{{$d->id}}" class="btn btn-success">Sửa</a>
                            <a href="javascript:void(0)" onclick="delete_vipid({{$d->fbid}},'{{$d->_id}}')" class="btn btn-danger">Xóa</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>            
    </div>
</div>
<script type="text/javascript">
function delete_vipid(uid,id){
    var x = confirm('Bạn có chắc chắn muốn xóa vipid '+uid+' khỏi hệ thống không?')
    if(x == true){
        $.post('/admin/viplike/delete',{id:id,_token:token})
        .done((data)=>{
            show_toastr(data);
            eval(data.action);
        })
        .fail((data)=>{
            show_toastr(data);
        })
    }else{
        return false;
    }
}
$(document).on('change','#fbid',function(){
    $('#fbname').val('loading...');
    var url = $(this).val();
    if(url.indexOf('https://www.facebook.com/') == -1){
        url = 'https://www.facebook.com/'+url;
    }
    $.get('https://api.likedao.biz/getfbid?link='+url,function(data){
        if(data.success == true){
            $('#fbid').val(data.fbid);
            $('#fbname').val(data.name);
        }else{
            toastr.error(data.message);            
        }
    })
});
</script>
@endsection