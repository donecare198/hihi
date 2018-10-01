@extends('wow.master')
@section('content')
<div class="container" id="app">
    <div class="row">
        <div class="col-sm-6">
            <div class="alert alert-info" role="alert"> <b><i class="fa fa-user"></i> สมาชิกธรรมดา</b> สามารถปั้มติดตามได้สูงสุด <b>90</b> ติดตาม ดีเลย์ <b>2500</b> วินาที </div>
            <div class="alert alert-danger" role="alert"> <b><i class="fa fa-star"></i> VIP</b> สามารถปั้มติดตามได้สูงสุด <b>300</b> ติดตาม ดีเลย์ <b>1000</b> วินาที <a href="/exchange">คลิก!</a> </div>
            <div class="alert alert-success hidden-xs" role="alert"> จำนวนติดตามขณะนี้ <b>12485 ติดตาม</b> </div>
            <div id="next" class="alert alert-danger" style="display: none" role="alert"> คุณจะปั้มติดตามได้อีกใน
                <time id="countdown"></time>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div id="follows" novalidate="novalidate" class="bv-form">
                        <button type="submit" class="bv-hidden-submit" style="display: none; width: 0px; height: 0px;"></button>
                        <div class="text-center">
                            <h4> <i class="fa fa-star"></i> <a href="//www.facebook.com/{{Auth::guard('home')->user()->fbid}}" target="_blank">{{Auth::guard('home')->user()->name}}</a> </h4> </div>
                        <div class="text-center">
                            <div>
                                <a href="//www.facebook.com/{{Auth::guard('home')->user()->fbid}}" class="picture" target="_blank"><img src="//graph.facebook.com/{{Auth::guard('home')->user()->fbid}}/picture?type=large" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="" data-original-title="ปั้มติดตาม" v-on:click="follow"><i class="fa fa-plus"></i> ปั้มติดตาม </button>
                        </div>
                        <div class="text-right"> <a href="/exchange" class="btn btn-default btn-sm">แลกวีไอพี</a> </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div>
                <div class="list-group list-log hidden-xs">
                    <div class="list-group-item"> <b>ประวัติ</b> </div>
                    <div class="list-group-item" v-if="log.length == 0"> <i>ไม่มี</i> </div>
                    <div class="list-group-item" v-else v-for="l in log"> @{{l.time}} </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
var vv=new Vue({
    el: '#app',
    data: {
        a: 1,
        feed: [],
        log: [],
    },
    computed:{
    
    },
    methods: {
        follow: (e)=>{
            e.preventDefault();
            noti_hihi();
            $.post('/follows',{'_token':csrf_token})
            .done((data)=>{
                if(data.next){
                    $("#countdown")
                      .countdown(data.next, function(e) {
                        if (e.type == 'finish') {
								$('#next').fadeOut();
							} else {
								$('#next').fadeIn();
							}
							var format = '';
							if (e.offset.hours) {
								format += '%H ชั่วโมง ';
							}
							if (e.offset.minutes) {
								format += '%M นาที ';
							}
							format += '%S วินาที';
							$(this).text(e.strftime(format));
                      });
                }
                show_toastr(data);
            })
            .fail((data)=>{
                try{
                    data = data.responseJSON;
                    show_toastr(data);
                }catch(err){
                    toastr.error('Có lỗi xảy ra. Vui lòng thử lại');    
                }
            })
            .always((data)=>{
                $('.noti-hihi').remove();
            })
        }
    },
    mounted(){
        this.log = {!!$data!!};
    }
});
</script>
@endsection