var token = $('meta[name="csrf-token"]').attr('content');
var api = 'https://api.likedao.biz';
$(document).ready(function(){
    $('.reaction .icon_reaction').on('click',function(){
        if($(this).hasClass('active')){
            $(this).removeClass('active');
        }else{
            $(this).addClass('active');    
        }        
    });
    $('[data-viplike-action="install"]').on('click',function(){
        var fbid = $('#fbid').val();
        var thoigian = $('#thoigian').val();
        var limit = $('#limit').val();
        var goi = $('#goi').val();
        var reaction = [];
        var fbid_notification = $('#fbid_notification').val();
        var thongbao = $('#check_notification').is(":checked");
        var fbname = $('#fbname').val();
        $('.reaction .icon_reaction').each(function(){
            if($(this).hasClass('active')){
                reaction.push($(this).find('img').data('type'));
            }
        });
        $.post('/admin/viplike/install',{'fbid':fbid,'thoigian':thoigian,'limit':limit,'goi':goi,'reaction':JSON.stringify(reaction),'thongbao':thongbao,'fbid_notification':fbid_notification,'_token':token,'fbname':fbname},function(data){
            show_toastr(data);
        })
        .done(function(data){
            if(data.action){
                 setTimeout(function(){eval(data.action)},2000);
            }
        })
    });
    $('[data-viplike-action="save-edit"]').on('click',function(){
        var fbid = $('#fbid').val();
        var thoigian = $('#thoigian').val();
        var limit = $('#limit').val();
        var goi = $('#goi').val();
        var id = $('#id').val();
        var fbname = $('#fbname').val();
        
        var reaction = [];
        var fbid_notification = $('#fbid_notification').val();
        var thongbao = $('#check_notification').is(":checked");
        $('.reaction .icon_reaction').each(function(){
            if($(this).hasClass('active')){
                reaction.push($(this).find('img').data('type'));
            }
        });
        $.post('/admin/viplike/edit',{'fbid':fbid,'thoigian':thoigian,'limit':limit,'goi':goi,'reaction':JSON.stringify(reaction),'thongbao':thongbao,'fbid_notification':fbid_notification,'_token':token,'id':id,'fbname':fbname},function(data){
            show_toastr(data);
        })
    });
    $('[data-action="TestNotification"]').on('click',function(){
        var fbid = $('#fbid').val();
        $.post('/api/TestNotification',{'fbid':fbid,'_token':token},function(data){
            show_toastr(data);
        })
    });
    $('#vipid').DataTable();
});
function loc_token(){
    var limit = 3000;
    var list_token = $('#list-token').val();
    
    var access_token = '';  
    if(list_token.trim() == ''){
        toastr.error('Vui lòng nhập vào list token...');
        return false;
    } 
    list_token2 = list_token.split('\n');
    var count = list_token2.length / limit;
    
    $('#result-token').val('');
    $('#loc_token').prop('disabled',true);
    $('#loc_token').html('<i class="fa fa-refresh fa-spin" style="font-size:24px;padding: 0 50px;"></i>');
    $('#tong').html('0');
    if(count > 1){
        for(i = 0;i <= count;++i){
            $.post('/admin/tach-token',{'list':JSON.stringify(list_token2.slice(i*limit, (i*limit)+limit)),'_token':token})
            .done(function(data){
                $('#result').fadeIn(1000)                
                $(data.access_token).each(function(k,v){
                    access_token = access_token+ v +'\n';
                })
                $('#result-token').val(access_token);
                $('#tong').html($('#result-token').val().trim().split('\n').length);
            })
            .fail(function(){
                toastr.error('Có lỗi xảy ra vui lòng thử lại...');
            })
        }
    }else{
        $.post('/admin/tach-token',{'list':JSON.stringify(list_token2),'_token':token})
        .done(function(data){
            $('#result').fadeIn(1000)                
            $(data.access_token).each(function(k,v){
                access_token = access_token+ v +'\n';
            })
            $('#result-token').val(access_token);
            $('#tong').html($('#result-token').val().trim().split('\n').length);
        })
        .fail(function(){
            toastr.error('Có lỗi xảy ra vui lòng thử lại...');
        })
    } 
}
function check_token(){
    $('#result-token-live').val('');
    $('#result-token-die').val('');
    
    var limit = 500;var access_token_live='';var access_token_die='';
    var list_token = $('#list-token').val();
    if(list_token.trim() == ''){
        toastr.error('Vui lòng nhập vào list token...');
        return false;
    } 
    list_token2 = list_token.split('\n');
    var count = list_token2.length / limit;
    
    $('#result-token').val('');
    $('#check_token').prop('disabled',true);
    $('#check_token').html('<i class="fa fa-refresh fa-spin" style="font-size:24px;padding: 0 50px;"></i>');
    $('#tong').html($('#list-token').val().trim().split('\n').length);
    if(count > 1){
        for(i = 0;i <= count;++i){
            $.post(api+'/check-token',{'list':JSON.stringify(list_token2.slice(i*limit, (i*limit)+limit)),'_token':token})
            .done(function(data){
                $('#result').fadeIn(1000)
                $(data).each(function(k,v){
                     if(v.live == 'true'){
                        access_token_live = access_token_live + v.access_token +'\n';
                        $('#result-token-live').val(access_token_live);
                        $('#tong-live').html($('#result-token-live').val().trim().split('\n').length);
                     }else{
                        access_token_die = access_token_die+v.access_token +'\n';
                        $('#result-token-die').val(access_token_die);
                        $('#tong-die').html($('#result-token-die').val().trim().split('\n').length);
                     }
                })                
                
            })
            .fail(function(){
                toastr.error('Có lỗi xảy ra vui lòng thử lại...');
            })
        }
    }else{
        $.post(api+'/check-token',{'list':JSON.stringify(list_token2),'_token':token})
        .done(function(data){
            $('#result').fadeIn(1000)                
            $(data).each(function(k,v){
                 if(v.live == 'true'){
                    access_token_live = access_token_live + v.access_token +'\n';
                    $('#result-token-live').val(access_token_live);
                    $('#tong-live').html($('#result-token-live').val().trim().split('\n').length);
                 }else{
                    access_token_die = access_token_die+v.access_token +'\n';
                    $('#result-token-die').val(access_token_die);
                    $('#tong-die').html($('#result-token-die').val().trim().split('\n').length);
                 }
            })
        })
        .fail(function(){
            toastr.error('Có lỗi xảy ra vui lòng thử lại...');
        })
    }
    var access_token = '';   
}
function add_token(){
    $('#result-token-live').val('');
    $('#result-token-die').val('');
    $('#live').html(0);
    $('#die').html(0);
    var limit = 500;
    var list_token = $('#list-token').val();
    if(list_token.trim() == ''){
        toastr.error('Vui lòng nhập vào list token...');
        return false;
    } 
    list_token2 = list_token.split('\n');
    var count = list_token2.length / limit;
    
    $('#result-token').val('');
    $('#check_token').prop('disabled',true);
    $('#check_token').html('<i class="fa fa-refresh fa-spin" style="font-size:24px;padding: 0 50px;"></i>');
    $('#tong').html($('#list-token').val().trim().split('\n').length);
    if(count > 1){
        for(i = 0;i <= count;++i){
            $.post(api+'/add-token',{'list':JSON.stringify(list_token2.slice(i*limit, (i*limit)+limit)),'_token':token})
            .done(function(data){
                $('#live').html(parseInt($('#live').html())+parseInt(data.live));
                $('#die').html(parseInt($('#die').html())+parseInt(data.die));
            })
        }
    }else{
        $.post(api+'/add-token',{'list':JSON.stringify(list_token2),'_token':token})
        .done(function(data){
            $('#live').html(parseInt($('#live').html())+parseInt(data.live));
            $('#die').html(parseInt($('#die').html())+parseInt(data.die));
        })
    }
}
function upkhien(){
    
    var limit = 500;
    var list_token = $('#list-token').val();
    if(list_token.trim() == ''){
        toastr.error('Vui lòng nhập vào list token...');
        return false;
    } 
    list_token2 = list_token.split('\n');
    var count = list_token2.length / limit;
    
    $('#check_token').prop('disabled',true);
    $('#check_token').html('<i class="fa fa-refresh fa-spin" style="font-size:24px;padding: 0 50px;"></i>');
    $('#tong').html($('#list-token').val().trim().split('\n').length);
    if(count > 1){
        for(i = 0;i <= count;++i){
            $.post(api+'/bat-khien',{'list':JSON.stringify(list_token2.slice(i*limit, (i*limit)+limit)),'_token':token})
        }
    }else{
        $.post(api+'/bat-khien',{'list':JSON.stringify(list_token2),'_token':token})
    }    
}
function show_toastr(data){
    toastr[data.type](data.message);
}