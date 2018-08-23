@extends('admin.master')
@section('content')
<div class="panel panel-primary">
    <div class="panel-heading">Tách Access Token từ List</div>
    <div class="panel-body"> 
        <div class="form-group">
            <textarea id="list-token" placeholder="Nhập vào list chứa Access Token cần tách. Cách nhau bằng xuống dòng" rows="12" class="form-control"></textarea>
        </div>           
        <div class="form-group text-right">
            <button class="btn btn-danger" onclick="loc_token()" id="loc_token">Tiến Hành Lọc Access Token <i class="fa fa-arrow-right"></i></button>
        </div>
    </div>
</div>
<div class="panel panel-primary" id="result" style="display: none;">
    <div class="panel-heading">List Access Token được lọc từ List ( Tổng <span class="badge" id="tong">0</span> )</span></div>
    <div class="panel-body"> 
        <div class="form-group">
            <textarea id="result-token" rows="12" class="form-control"></textarea>
        </div>
    </div>
</div>
<script>
$(document).ajaxStop(function() {
    toastr.success('Thành công...!!!!');  
    $('#loc_token').prop('disabled',false);
    $('#tong').html($('#result-token').val().trim().split('\n').length);
    $('#loc_token').html('Tiến Hành Lọc Access Token <i class="fa fa-arrow-right"></i>');
});
</script>
@endsection