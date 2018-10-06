@extends('wow.master')
@section('content')
<?php
    use App\Http\Controllers\CaptchaController;
    $captcha = new CaptchaController();
    $vip = DB::collection('user_meta')->where(['active'=>1,'fbid'=>Auth::guard('home')->user()->fbid,'type'=>'like'])->first();
?>
<input type="hidden" id="access_token" value="{{session()->get('likes')->access_token}}" />
<div class="container" id="app">
    <div class="row">
        <div class="col-sm-5">
            <div class="alert alert-info" role="alert">
                คุณเป็น <b><i class="fa fa-user"></i> สมาชิกธรรมดา</b> สามารถไลค์ได้สูงสุด
                <b>50</b> ไลค์ ดีเลย์ <b>2200</b> วินาที
            </div>
            <div class="alert alert-danger" role="alert">
                <b><i class="fa fa-star"></i> VIP</b> สามารถไลค์ได้สูงสุด
                <b>200</b> ไลค์ ดีเลย์ <b>120</b> วินาที <a href="/exchange">คลิก!</a>
            </div>
            <div class="alert alert-success hidden-xs" role="alert">
                จำนวนไลค์ขณะนี้ 98<b>009 ไลค์</b>
            </div>
            <div id="next" class="alert alert-danger" style="display: none" role="alert">
                คุณจะไลค์ได้อีกใน
                <time id="countdown"></time>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    <form id="likes" novalidate="novalidate" class="bv-form">
                        <button type="submit" class="bv-hidden-submit" style="display: none; width: 0px; height: 0px;"></button>
                        <div class="form-group">
                            <label for="fb_id">ไอดีหรือลิงก์ สเตตัส / รูปภาพ</label>

                            <div class="form-group">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-addon"><i class="fa fa-facebook"></i></span>
                                    <input type="text" id="fb_id" class="form-control" name="fb_id" />
                                </div>
                                <small class="help-block" style="display: none;">ข้อมูลไม่ถูกต้อง</small><small class="help-block" style="display: none;">โปรดระบุค่า</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gender">ระบุเพศ</label>

                            <div class="form-group">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-addon">Sex</span>
                                    <select style="-webkit-appearance: menulist-button;" id="gender" class="form-control" name="gender">
                                        <option value="all">ทุกเพศ</option>
                                        <option value="female">ผู้หญิง</option>
                                        <option value="male">ผู้ชาย</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        @if(!$vip)
                        <div class="form-group">
                            <label for="max">รหัสป้องกัน</label> <b><a href="https://www.youtube.com/watch?v=UUUeTMQKMwc&amp;feature=youtu.be&amp;t=1m37s" target="_blank">(วิธีใส่รหัส)</a></b>
                            <div class="row">
                                <div class="col-md-4">
                                    <img id="lucdepzai" data-src="<?=$captcha->setCaptcha()?>" />
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="code" placeholder="ใส่ตัวเลข 5 หลักที่เห็น">
                                </div>
                            </div>
                        </div>
                        @endif
                        <button class="btn btn-primary likes" v-on:click="likes" data-toggle="tooltip" data-placement="right" title="" data-original-title="ต้องเป็นโพสสาธารณะเท่านั้นถึงเพิ่มไลค์ได้"><i class="fa fa-heart"></i> Like</button>
                        <div class="text-right">
                            <a href="/exchange" class="btn btn-default btn-sm">แลกวีไอพี</a>
                        </div>
                    </form>
                </div>
            </div>
            <div>
                @if($vip)
                <div id="premium-countdown" class="hidden-xs">
                    <h3><i class="fa fa-star"></i> VIP จะหมดในอีก</h3>
                    <div class="countdown">
                        <ul>
                            <li><span class="days">@{{days}}</span>
                                <p class="days_ref">วัน</p>
                            </li>
                            <li class="seperator">.</li>
                            <li><span class="hours">@{{hours}}</span>
                                <p class="hours_ref">ชั่วโมง</p>
                            </li>
                            <li class="seperator">:</li>
                            <li><span class="minutes">@{{minutes}}</span>
                                <p class="minutes_ref">นาที</p>
                            </li>
                            <li class="sepe rator">:</li>
                            <li><span class="seconds">@{{seconds}}</span>
                                <p class="seconds_ref">วินาที</p>
                            </li>
                        </ul>
                    </div>
                </div>
                @endif
                <div class="list-group list-log hidden-xs">
                    <div class="list-group-item">
                        <b>ประวัติ</b>
                    </div>
                    <div class="list-group-item" v-if="log.length == 0">
                        <i>ไม่มี</i>
                    </div>
                    <div class="list-group-item" v-for="l in log">
						<div class="row">
							<div class="col-xs-6 text-left"><i class="fa fa-thumbs-up"></i>
								ปั้มไลค์สำเร็จ <b>@{{l.success}}</b> ไลค์
							</div>
							<div class="col-xs-6 text-right">
								<time class="timeago" :datetime="l.time" :title="l.time"></time>
							</div>
						</div>
					</div>
                </div>

            </div>
        </div>
        <div class="col-sm-7">
            <ul id="feed-category" class="nav nav-tabs nav-tabs-google" role="tablist">
                <li>
                    <a>
                        <button id="feed-refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i> รีเฟรช</button>
                    </a>
                </li>
                <li class="active"><a href="" role="tab" data-toggle="tab" data-type="all">โพสทั้งหมด</a>
                </li>
                <li><a href="" role="tab" data-toggle="tab" data-type="status">สถานะ</a>
                </li>
                <li><a href="" role="tab" data-toggle="tab" data-type="photo">รูปภาพ</a>
                </li>
                <li><a href="" role="tab" data-toggle="tab" data-type="video">วิดีโอ</a>
                </li>
            </ul>
            <div id="feed" class="row-fluid">
                <div v-for="f in feed" :key="f.id">
                    <div v-if="f.privacy.value == 'EVERYONE'" class="callout callout-primary">
                        <div class="row-fluid" style="margin-bottom: 10px">
                            <div class="media">
                                <a class="pull-left" :href="'https://www.facebook.com/'+f.from.id" target="_blank">
                                    <img class="media-object" :src="'//graph.facebook.com/'+f.from.id+'/picture?type=square'" />
                                </a>
                                <div class="media-body">
                                    <h4 class="media-heading"><a :href="'//facebook.com/'+f.id" target="_blank">@{{f.from.name}}</a>
                                        <time class="timeago" :datetime="f.created_time" :title="f.created_time"></time>
                                    </h4>
                                    <div class="row" v-if="f.picture">
                                        <div class="col-xs-4 feed-img">
                                            <a href="" target="_blank">
                                                <img :src="f.picture" alt="" data-toggle="tooltip" data-placement="top" title="" data-original-title="">
                                            </a>
                                        </div>
                                    </div>
                                    <p v-if="f.message">@{{f.message}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <button class="btn btn-primary btn-sm likes"  v-on:click="likes_feed" :data-fb-id="f.id"><i class="fa fa-heart"></i>
                            Like
                            </button>
                            <button type="button" class="btn btn-link btn-sm" v-if="f.likes">@{{f.likes.count}} ถูกใจ</button>
                        </div>
                    </div>
                    <div class="callout callout-warning" v-else>
                        <div class="alert alert-warning alert-dismissible" role="alert">
                            <strong>Warning!</strong> ไม่สามารถปั้มไลค์ได้ (ไม่ใช่โพสสาธารณะ)
                        </div>
                    
                        <div class="row-fluid" style="margin-bottom: 10px">
                            <div class="media">
                                <a class="pull-left" :href="'https://www.facebook.com/'+f.from.id" target="_blank">
                                    <img class="media-object" :src="'//graph.facebook.com/'+f.from.id+'/picture?type=square'">
                                </a>
                    
                                <div class="media-body">
                                    <h4 class="media-heading"><a :href="'//facebook.com/'+f.id" target="_blank">@{{f.from.name}}</a>
            							<time class="timeago" :datetime="f.created_time" :title="f.created_time"></time>
            						</h4>
                                    <div class="row" v-if="f.picture">
                                        <div class="col-xs-4 feed-img">
                                            <a href="" target="_blank">
                                                <img :src="f.picture" alt="" data-toggle="tooltip" data-placement="top" title="" data-original-title="">
                                            </a>
                                        </div>
                                    </div>
                                    <p v-if="f.message">@{{f.message}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <button class="btn btn-default btn-sm" disabled=""><i class="fa fa-close"></i> Like</button>
                            <button type="button" class="btn btn-link btn-sm" v-if="f.likes">@{{f.likes.count}} ถูกใจ</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

$(document).ready(function(){
        $("#premium-countdown").countdown("{{date('Y-m-d H:i:s',strtotime($vip['time_expired']))}}", function(e) {
            vv.$data.days = e.offset.daysToMonth;
            vv.$data.hours = e.offset.hours;
            vv.$data.minutes = e.offset.minutes;
            vv.$data.seconds = e.offset.seconds;
          });
});
var vv=new Vue({
    el: '#app',
    data: {
        a: 1,
        feed: [],
        log: [],
        days : 0,
        hours : 0,
        minutes : 0,
        seconds : 0,
    },
    computed:{
    
    },
    methods: {
        likes: (e)=>{
            e.preventDefault();
            $('.likes').prop('disabled',true);
            var captcha = $('#code').val();
            var fb_id = $('#fb_id').val();
            var gender = $('#gender').val();
            if(fb_id == ''){
                $('#fb_id').parent().parent().addClass('has-error');
                $('.likes').prop('disabled',false);
                return false;
            }else{
                $('#fb_id').parent().parent().removeClass('has-error');
            }
            
            noti_hihi();
            if(captcha == ''){
                $('#code').parent().addClass('has-error');
                $('.likes').prop('disabled',false);
               // return false;
            }else{
                $('#code').parent().removeClass('has-error');   
            }
            $.post('/likes',{'captcha':captcha,'fbid':fb_id,'gender':gender,'_token':csrf_token})
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
                $('.likes').prop('disabled',false);
                $('#lucdepzai').attr('src',$('#lucdepzai').data('src')+new Date().getTime());
            })
        },
        likes_feed: (e)=>{
            e.preventDefault();
            $('.likes').prop('disabled',true);
            var captcha = $('#code').val();
            var fb_id = $(e.target).data('fb-id');
            var gender = $('#gender').val();
            
            noti_hihi();
            if(captcha == ''){
                $('#code').parent().addClass('has-error');
                $('.likes').prop('disabled',false);
            }else{
                $('#code').parent().removeClass('has-error');   
            }
            $.post('/likes',{'captcha':captcha,'fbid':fb_id,'gender':gender,'_token':csrf_token})
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
                $('.likes').prop('disabled',false);
                $('#lucdepzai').attr('src',$('#lucdepzai').data('src')+new Date().getTime());
            })
        }
    },
    mounted(){
        refresh_feed();
        this.log = {!!$data!!};
        $('#lucdepzai').attr('src', $('#lucdepzai').data('src'))
    }
});
</script>
@endsection