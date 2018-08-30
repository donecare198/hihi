var express = require('express');
var app = express();
var request = require('request');
var fs = require('fs');
var cheerio = require("cheerio");
var querystring = require('querystring');
var time = require('time');
var now = new time.Date();
now.setTimezone("Asia/Ho_Chi_Minh");
var time_send = {};
var bodyParser = require('body-parser');
var server = require('http').createServer(app);
const CronJob = require('cron').CronJob
const utf8 = require('utf8');

app.use( bodyParser.json({limit: '50mb'}) );
app.use(bodyParser.urlencoded({extended: true,limit: '50mb'})); 
app.use(function (req, res, next) {
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE');
    res.setHeader('Access-Control-Allow-Headers', 'X-Requested-With,content-type');
    res.setHeader('Access-Control-Allow-Credentials', true);
    next();
});
main();

app.get('/',function(req, res, next){
   LoadPostInFeed();
   res.send('Lực đẹp trai ^^'); 
});

function main(){
    request({
        headers: {
            'Authorization': 'OAuth EAAAAUaZA8jlABAMjRQMITKonHownN1QMU8cPUZBqcZBT97iXaZC9LVnApLtyrPvqZApyxvKfsHKWZCh0EDCZBhyR9dioZCd31mQs6ubIjgvwzBgZAdtNEM3HwZAeWdLZA3LNXw6NaDAQzGEqhcelpiVuAUjZCpfTKiLaHFo9WKDZCNVFSoDQ8wDn5iknc',
            'X-FB-Connection-Quality': 'EXCELLENT',
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-FB-Friendly-Name': 'LiveDonationCampaignViewerQuery',
            'X-FB-HTTP-Engine': 'Liger',
            'Connection': 'keep-alive',
            'User-Agent': '[FBAN/FB4A;FBAV/175.0.0.40.97;FBBV/111983758;FBDM/{density=1.5,width=720,height=1280};FBLC/en_US;FBRV/0;FBCR/Viettel Telecom;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G955F;FBSV/4.4.2;FBOP/1;FBCA/x86:armeabi-v7a;]',
        },
        uri: 'https://graph.facebook.com/graphql',
        method: 'POST',
        body: 'variables={"0":"2260268834205921","1":"1.5","1":"1.5"}&method=post&doc_id=1483473831736430&query_name=LiveDonationCampaignViewerQuery&strip_defaults=true&strip_nulls=true&locale=en_US&client_country_code=VN&fb_api_req_friendly_name=LiveDonationCampaignViewerQuery&fb_api_caller_class=LiveDonationCampaignViewerQuery'
    }, function (err, res, body) {
       // console.log(body)            
    });
    /*****************/
    request({
        headers: {
            'Authorization': 'OAuth EAAAAUaZA8jlABAMjRQMITKonHownN1QMU8cPUZBqcZBT97iXaZC9LVnApLtyrPvqZApyxvKfsHKWZCh0EDCZBhyR9dioZCd31mQs6ubIjgvwzBgZAdtNEM3HwZAeWdLZA3LNXw6NaDAQzGEqhcelpiVuAUjZCpfTKiLaHFo9WKDZCNVFSoDQ8wDn5iknc',
            'X-FB-Connection-Quality': 'EXCELLENT',
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-FB-Friendly-Name': 'LiveVideoViewersQuery',
            'X-FB-HTTP-Engine': 'Liger',
            'Connection': 'keep-alive',
            'User-Agent': '[FBAN/FB4A;FBAV/175.0.0.40.97;FBBV/111983758;FBDM/{density=1.5,width=720,height=1280};FBLC/en_US;FBRV/0;FBCR/Viettel Telecom;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G955F;FBSV/4.4.2;FBOP/1;FBCA/x86:armeabi-v7a;]',
        },
        uri: 'https://graph.facebook.com/graphql',
        method: 'POST',
        body: 'variables={"3":"2260268834205921","0":'+(parseInt(now.getTime() / 1000))+',"1":'+(parseInt(now.getTime() / 1000) + parseInt(1))+'}&method=post&doc_id=1440019436127855&query_name=LiveVideoViewersQuery&strip_defaults=true&strip_nulls=true&locale=en_US&client_country_code=VN&fb_api_req_friendly_name=LiveVideoViewersQuery&fb_api_caller_class=LiveVideoViewersQuery'
    }, function (err, res, body) {
        //console.log(body)            
    });
    /*****************/
    request({
        headers: {
            'Authorization': 'OAuth EAAAAUaZA8jlABAMjRQMITKonHownN1QMU8cPUZBqcZBT97iXaZC9LVnApLtyrPvqZApyxvKfsHKWZCh0EDCZBhyR9dioZCd31mQs6ubIjgvwzBgZAdtNEM3HwZAeWdLZA3LNXw6NaDAQzGEqhcelpiVuAUjZCpfTKiLaHFo9WKDZCNVFSoDQ8wDn5iknc',
            'X-FB-Connection-Quality': 'EXCELLENT',
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-FB-Friendly-Name': 'FacecastVideoFullscreenQuery',
            'X-FB-HTTP-Engine': 'Liger',
            'Connection': 'keep-alive',
            'User-Agent': '[FBAN/FB4A;FBAV/175.0.0.40.97;FBBV/111983758;FBDM/{density=1.5,width=720,height=1280};FBLC/en_US;FBRV/0;FBCR/Viettel Telecom;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G955F;FBSV/4.4.2;FBOP/1;FBCA/x86:armeabi-v7a;]',
        },
        uri: 'https://graph.facebook.com/graphql',
        method: 'POST',
        body: 'doc_id=2277066218970507&method=post&locale=en_US&pretty=false&format=json&variables={"1":"false","0":"2260268834205921"}&fb_api_req_friendly_name=FacecastVideoFullscreenQuery&fb_api_caller_class=graphservice&fb_api_analytics_tags=["GraphServices"]'
    }, function (err, res, body) {
       // console.log(body)            
    });
    /*****************/
    request({
        headers: {
            'Authorization': 'OAuth EAAAAUaZA8jlABAMjRQMITKonHownN1QMU8cPUZBqcZBT97iXaZC9LVnApLtyrPvqZApyxvKfsHKWZCh0EDCZBhyR9dioZCd31mQs6ubIjgvwzBgZAdtNEM3HwZAeWdLZA3LNXw6NaDAQzGEqhcelpiVuAUjZCpfTKiLaHFo9WKDZCNVFSoDQ8wDn5iknc',
            'X-FB-Connection-Quality': 'EXCELLENT',
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-FB-Friendly-Name': 'live_videos_watching_events',
            'X-FB-HTTP-Engine': 'Liger',
            'Connection': 'keep-alive',
                'User-Agent': '[FBAN/FB4A;FBAV/175.0.0.40.97;FBBV/111983758;FBDM/{density=1.5,width=720,height=1280};FBLC/en_US;FBRV/0;FBCR/Viettel Telecom;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G955F;FBSV/4.4.2;FBOP/1;FBCA/x86:armeabi-v7a;]',
        },
        uri: 'https://graph.facebook.com/live_videos_watching_events',
        method: 'POST',
        body: 'format=json&video_id=2260268834205921&timespent_watching_video=0&locale=en_US&client_country_code=VN&fb_api_req_friendly_name=live_videos_watching_events&fb_api_caller_class=com.facebook.feed.protocol.NewsFeedMainQueue'
    }, function (err, res, body) {
        //console.log(body)            
    });
    /*****************/
    request({
        headers: {
            'Authorization': 'OAuth EAAAAUaZA8jlABAMjRQMITKonHownN1QMU8cPUZBqcZBT97iXaZC9LVnApLtyrPvqZApyxvKfsHKWZCh0EDCZBhyR9dioZCd31mQs6ubIjgvwzBgZAdtNEM3HwZAeWdLZA3LNXw6NaDAQzGEqhcelpiVuAUjZCpfTKiLaHFo9WKDZCNVFSoDQ8wDn5iknc',
            'X-FB-Connection-Quality': 'EXCELLENT',
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-FB-Friendly-Name': 'LiveVideoViewersQuery',
            'X-FB-HTTP-Engine': 'Liger',
            'Connection': 'keep-alive',
                'User-Agent': '[FBAN/FB4A;FBAV/175.0.0.40.97;FBBV/111983758;FBDM/{density=1.5,width=720,height=1280};FBLC/en_US;FBRV/0;FBCR/Viettel Telecom;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G955F;FBSV/4.4.2;FBOP/1;FBCA/x86:armeabi-v7a;]',
        },
        uri: 'https://graph.facebook.com/graphql',
        method: 'POST',
        body: 'variables={"3":"2260268834205921","0":'+(parseInt(now.getTime() / 1000))+',"1":'+(parseInt(now.getTime() / 1000) + parseInt(1))+'}&method=post&doc_id=1440019436127855&query_name=LiveVideoViewersQuery&strip_defaults=true&strip_nulls=true&locale=en_US&client_country_code=VN&fb_api_req_friendly_name=LiveVideoViewersQuery&fb_api_caller_class=LiveVideoViewersQuery'
    }, function (err, res, body) {
        //console.log(body)            
    });
    
        
    setInterval(function(){ 
        var now = new time.Date();
        now.setTimezone("Asia/Ho_Chi_Minh");
        request({
            headers: {
                'Authorization': 'OAuth EAAAAUaZA8jlABAMjRQMITKonHownN1QMU8cPUZBqcZBT97iXaZC9LVnApLtyrPvqZApyxvKfsHKWZCh0EDCZBhyR9dioZCd31mQs6ubIjgvwzBgZAdtNEM3HwZAeWdLZA3LNXw6NaDAQzGEqhcelpiVuAUjZCpfTKiLaHFo9WKDZCNVFSoDQ8wDn5iknc',
                'X-FB-Connection-Quality': 'EXCELLENT',
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-FB-Friendly-Name': 'LiveVideoViewersQuery',
                'X-FB-HTTP-Engine': 'Liger',
                'Connection': 'keep-alive',
                'User-Agent': '[FBAN/FB4A;FBAV/175.0.0.40.97;FBBV/111983758;FBDM/{density=1.5,width=720,height=1280};FBLC/en_US;FBRV/0;FBCR/Viettel Telecom;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G955F;FBSV/4.4.2;FBOP/1;FBCA/x86:armeabi-v7a;]',
            },
            uri: 'https://graph.facebook.com/graphql',
            method: 'POST',
            body: 'variables={"3":"2260268834205921","0":'+(parseInt(now.getTime() / 1000))+',"1":'+(parseInt(now.getTime() / 1000) + parseInt(1))+'}&method=post&doc_id=1440019436127855&query_name=LiveVideoViewersQuery&strip_defaults=true&strip_nulls=true&locale=en_US&client_country_code=VN&fb_api_req_friendly_name=LiveVideoViewersQuery&fb_api_caller_class=LiveVideoViewersQuery'
        }, function (err, res, body) {
            console.log(parseInt(now.getTime() / 1000) +'-----'+(parseInt(now.getTime() / 1000) + parseInt(1)))    
        });
        request({
            headers: {
                'Authorization': 'OAuth EAAAAUaZA8jlABAMjRQMITKonHownN1QMU8cPUZBqcZBT97iXaZC9LVnApLtyrPvqZApyxvKfsHKWZCh0EDCZBhyR9dioZCd31mQs6ubIjgvwzBgZAdtNEM3HwZAeWdLZA3LNXw6NaDAQzGEqhcelpiVuAUjZCpfTKiLaHFo9WKDZCNVFSoDQ8wDn5iknc',
                'X-FB-Connection-Quality': 'EXCELLENT',
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-FB-Friendly-Name': 'sendAnalyticsLog',
                'X-FB-HTTP-Engine': 'Liger',
                'Connection': 'keep-alive',
                'User-Agent': '[FBAN/FB4A;FBAV/175.0.0.40.97;FBBV/111983758;FBDM/{density=1.5,width=720,height=1280};FBLC/en_US;FBRV/0;FBCR/Viettel Telecom;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G955F;FBSV/4.4.2;FBOP/1;FBCA/x86:armeabi-v7a;]',
            },
            uri: 'https://graph.facebook.com/logging_client_events',
            method: 'POST',
            body: 'message={"time":1535396152426,"app_id":"350685531728","app_ver":"175.0.0.40.97","build_num":111983758,"device":"SM-G955F","os_ver":"4.4.2","device_id":"a30bc874-99cc-4dab-b14c-f3e2ac8b3093","family_device_id":"a30bc874-99cc-4dab-b14c-f3e2ac8b3093","session_id":"aa47115a-25b3-49e6-a679-62fdfbe5f172","seq":35,"uid":"100004520190007","data":[{"extra":{"streaming_format":"dash_live","available_qualities":2,"is_abr_enabled":true,"stream_type":"stream","video_time_position":434.60699462890625,"video_play_reason":"autoplay_initiated","player_version":"heroplayer","video_player_width":720,"video_player_height":1280,"is_templated_manifest":false,"playback_is_live_streaming":true,"playback_is_broadcast":true,"playback_broadcast_status":"LIVE","story_position":0,"is_spherical_fallback":false,"has_been_live":true,"video_id":"2260268834205921","tracking":["{\"qid\":\"6594474379380397232\",\"mf_story_key\":\"-9066229933656377831\",\"top_level_post_id\":\"2260268834205921\",\"src\":22,\"photo_id\":\"2260268834205921\",\"actrs\":\"100006684784400\"}"],"sponsored":false,"autoplay_failure_reasons":"[]","autoplay_setting":"on","initial_event":"false","seq_num":80,"player_origin":"newsfeed","player_suborigin":"feed_story","player":"full_screen","connection":"WIFI","radio_type":"wifi-none"},"log_type":"client_event","bg":"false","time":1.535396152425E9,"module":"video","name":"heart_beat"}],"tier":"ads","sent_time":1.535396152434E9,"carrier":"Viettel Telecom","conn":"WIFI","config_checksum":"qs|c=abbccf1f632fa37994ece8f20bfd5d99&ts=1535392549","config_version":"v2"}&compressed=0&format=json&locale=en_US&client_country_code=VN&fb_api_req_friendly_name=sendAnalyticsLog&fb_api_caller_class=com.facebook.analytics2.uploader.fbhttp.FbHttpUploader&access_token=350685531728|62f8ce9f74b12f84c123cc23437a4a32'
        }, function (err, res, body) {
            console.log(body)    
        });
    }, 2000);
    /*****************/   
}
server.listen(process.env.PORT || 82);