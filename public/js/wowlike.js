var csrf_token = $('[name="csrf-token"]').attr('content');
var show_load = ()=>{
    $('#loading').css('display','block');
}
var hide_load = ()=>{
    $('#loading').css('display','none');
}
$(document).ready(function(){
    $('#feed-refresh').click(()=>{
        refresh_feed();
    });
    
    $('#feed-category a').on('click',()=>{
        refresh_feed();
    });
    $('input[name="access_token"]').on('change keyup',function(){
        let access_token = $('input[name="access_token"]').val();
        if(access_token.trim() == ''){
            $('input[name="access_token"]').parent().addClass('has-error');
            $('input[name="access_token"]').parent().removeClass('has-success');
            $('#login-with-token-form button').attr('disabled','disabled');
        }else{
            $('#login-with-token-form button').removeAttr('disabled','disabled');
            $('input[name="access_token"]').parent().removeClass('has-error');
            $('input[name="access_token"]').parent().addClass('has-success');
        }
    });
    $('#login-with-token-form').submit((e)=>{
        e.preventDefault();
        var access_token = $('input[name="access_token"]').val();
        var type = $('#type').val();
        if((access_token.trim()).length == 0){
            $('input[name="access_token"]').parent().parent().addClass('has-error');
            $('.help-block').css('display','block');
            return false;
        }else{
            $('input[name="access_token"]').parent().parent().removeClass('has-error');
            $('.help-block').css('display','none');
        }
        show_load();
        $.post('/login',{access_token:access_token,type:type,_token:csrf_token})
        .done((data)=>{
            show_toastr(data);
        })
        .fail((data)=>{
            toastr.error('Vui lòng thử lại');
        })
        .always((data)=>{
            hide_load();
            if(data.action){
                eval(data.action);
            }
        })
    });
});

function refresh_feed(){
    $('#feed').fadeOut(500);
    var access_token = $('#access_token').val();
    $.get('https://graph.facebook.com/me/feed?field=full_picture&type=HAHA&access_token='+access_token+'&limit=20',(data)=>{
        if(data.data){
            vv.$data.feed = data.data;
            setTimeout(()=>{
                $('.timeago').timeago();
            },100);
        }
        if(data.error){
        }
    }).fail((data)=>{
        if((data.error.message).indexOf('Error validating access token: The user is enrolled in a blocking, logged-in checkpoint') != -1){
            $.get('/logout',function(){
                location.reload();
            });
        }
    })
    $('#feed').fadeIn(2000);
}
function getToken(){
    var username = $('#username').val();
    var password = $('#password').val();
    var app = $('#fbapp').val();
    $.get('/gettoken',{'username':username,'password':password,'app':app},(data)=>{
        $('#result').html('<iframe src="'+data+'"></iframe>');
    });
}
function show_toastr(data){
    toastr[data.type](data.message);
}
function noti_hihi(){
    if($('#toast-container').length > 0){
        $('#toast-container').append('<div class="toast noti-hihi toast-info" aria-live="polite" style="padding-left: 5px;background-image: unset !important;vertical-align: middle;"><div class="toast-progress" style="width: 0%;"></div><button type="button" class="toast-close-button" role="button" style=" display: none; ">×</button><div class="toast-message"><i class="fa fa-spinner fa-spin fa-2x " style=" vertical-align: middle; "></i><span> ระบบกำลังทำงาน โปรอย่าปิดหน้าต่างนี้</span></div></div>');
    }else{
        $('body').append('<div id="toast-container" class="toast-top-right"><div class="toast noti-hihi toast-info" aria-live="polite" style="padding-left: 5px;background-image: unset !important;vertical-align: middle;"><div class="toast-progress" style="width: 0%;"></div><button type="button" class="toast-close-button" role="button" style=" display: none; ">×</button><div class="toast-message"><i class="fa fa-spinner fa-spin fa-2x " style=" vertical-align: middle; "></i><span> ระบบกำลังทำงาน โปรอย่าปิดหน้าต่างนี้</span></div></div></div>');
    }    
}