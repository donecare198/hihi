@extends('admin.master')
@section('content')
<div class="container">
    <div class="form-group">
        <label>Nhập vào Access Token</label>
        <input type="text" id="token" class="form-control" placeholder="Access Token" />
    </div>
    <div class="form-group">
        <label>Group ID</label>
        <input type="text" id="groupid" class="form-control" placeholder="Group ID" />
    </div>
    <div class="form-group">
        <div class="">
            <span>Đã Quét:</span><span id="daquet" style="color: red;">0</span>
        </div>
        <button class="btn btn-danger" id="btn-loc" onclick="load()">Bắt đầu lọc</button>
    </div>
</div>
<div class="form-group">
    <textarea id="result" class="form-control" rows="25"></textarea>
</div>
<script>
$(document).ready(()=>{
    
})

$(document).ajaxStop(function() {
    $('#btn-loc').html('Bắt đầu lọc');
    toastr.success('Đã chạy xong');
});
function load(url = ''){
    $('#btn-loc').html('<i class="fa fa-refresh fa-spin" style="font-size:24px;padding: 0 50px;"></i>');
    var groupid = $('#groupid').val();
    var token = $('#token').val();
    if(groupid == '' || token == ''){
        toastr.warning('Xin vui lòng nhập đầy đủ thông tin');
        $('#btn-loc').html('Bắt đầu lọc');
        return false;
    }
    if(url == ''){
        url = 'https://graph.facebook.com/'+groupid+'/members?access_token='+token+'&limit=1000&field=id';
    }
    $.getJSON(url)
    .done((data)=>{
        $('#daquet').html(parseInt($('#daquet').html()) + parseInt((data.data).length))
        $.each(data.data,(k,v)=>{
            $('#result').append(v.id+'\n')
        })
        if(data.paging.next){
            load(data.paging.next);
        }
    })
}
</script>
@endsection