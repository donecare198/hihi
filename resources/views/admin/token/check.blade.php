@extends('admin.master')
@section('content')
<div class="panel panel-primary">
    <div class="panel-heading">List Access Token(1 Token 1 dòng) ( Tổng <span class="badge" id="tong">0</span> )</div>
    <div class="panel-body"> 
        <div class="form-group">
            <textarea id="list-token" placeholder="EAAAAUaZA8jlABAMdx7bB3W5lgqin8y8XgPWpOaKZA8gNBc0o7kswCdt6iGhugRCZBF6zWs02xKRZCLktfkor5P1W2xE89VSK45TNOq51QKGfT9bXgycLkGGjyviPPMlOPU6NN8VhNS6aijbhRH480qGTZBbMqlZC2biuW8wk65oroUCJIXpxVjoOO4bnJz8rkZD" rows="12" class="form-control"></textarea>
        </div>           
        <div class="form-group text-right">
            <button class="btn btn-danger" onclick="check_token()" id="check_token">Tiến Hành Check Access Token <i class="fa fa-arrow-right"></i></button>
        </div>
    </div>
</div>
<div class="panel panel-success" id="result-live">
    <div class="panel-heading">List Access Token Live ( Tổng <span class="badge" id="tong-live">0</span> )</div>
    <div class="panel-body"> 
        <div class="form-group">
            <textarea id="result-token-live" rows="12" class="form-control"></textarea>
        </div>
    </div>
</div>
<div class="panel panel-danger" id="result-die">
    <div class="panel-heading">List Access Token Die ( Tổng <span class="badge" id="tong-die">0</span> )</span></div>
    <div class="panel-body"> 
        <div class="form-group">
            <textarea id="result-token-die" rows="12" class="form-control"></textarea>
        </div>
    </div>
</div>
<script>
$(document).ajaxStop(function() {
    toastr.success('Thành công...!!!!');  
    $('#check_token').prop('disabled',false);
    $('#check_token').html('Tiến Hành Check Access Token <i class="fa fa-arrow-right"></i>');
});
</script>
@endsection