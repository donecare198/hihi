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
            <span>View File:</span><span id="daquet" style="color: red;">0</span>
        </div>
        <button class="btn btn-danger" id="btn-loc" onclick="load()">Bắt đầu lọc</button>
    </div>
</div>
<div class="form-group">
    <textarea id="result" class="form-control" rows="25"></textarea>
</div>
<script>
$(document).ready(()=>{
    toastr.options = {
      "closeButton": true,
      "debug": true,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "0",
      "timeOut": "0",
      "extendedTimeOut": "0",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }
})

$(document).ajaxStop(function() {
    $('#btn-loc').html('Bắt đầu lọc');
    toastr.success('Đã chạy xong');
});
function load(url = ''){
    $('#btn-loc').html('<i class="fa fa-refresh fa-spin" style="font-size:24px;padding: 0 50px;"></i>');
    var groupid = $('#groupid').val();
    var access_token = $('#token').val();
    if(groupid == '' || token == ''){
        toastr.warning('Xin vui lòng nhập đầy đủ thông tin');
        $('#btn-loc').html('Bắt đầu lọc');
        return false;
    }
    $('#daquet').html('<a href="https://likedao.biz/member_group/'+groupid+'.txt" target="_blank">'+groupid+'.txt</a>');
    if(url == ''){
        var url = 'https://graph.facebook.com/'+groupid+'/members?access_token='+access_token+'&limit=1000'
    }
    $.post('https://likedao.biz/getMember',{groupid:groupid,url:url,_token:token});
    $.getJSON(url)
    .done((data)=>{
        if(data.paging.next){
           load(data.paging.next);
        }
    })
}
</script>
@endsection