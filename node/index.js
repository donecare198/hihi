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
const mongoose = require('mongoose');
var notification = require('./send_messenger.js')


mongoose.connect('mongodb://localhost/viplike');
const model_token = mongoose.model('tokens',new mongoose.Schema({fbid:String,access_token:String,name:String,gender:String,locale:String,avatar:Boolean,live:Number,use:Number,message:String,updated_at:String,created_at:String}),'tokens')
const model_viplike = mongoose.model('viplike',new mongoose.Schema({fbid:String,thoigian:String,limit:String,goi:String,reaction:String,thongbao:String,fbid_notification:String,active:Number,updated_at:Date,created_at:Date}),'viplike')
const model_task_viplike = mongoose.model('task_viplike',new mongoose.Schema({fbid:String,postid:String,actionid:String,hoanthanh:Number,loi:Number,active:Number,story:String,limit:Number,goi:Number,reaction:String,updated_at:String,created_at:String}),'task_viplike')
const useragent = mongoose.model('useragent',new mongoose.Schema({text:String,sudung:Number,lock:Number}),'useragent')

app.use( bodyParser.json({limit: '50mb'}) );
app.use(bodyParser.urlencoded({extended: true,limit: '50mb'})); 
app.use(function (req, res, next) {
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE');
    res.setHeader('Access-Control-Allow-Headers', 'X-Requested-With,content-type');
    res.setHeader('Access-Control-Allow-Credentials', true);
    next();
});


new CronJob('0 */5 * * * *', function() {
    request.get('https://likedao.biz/api/sendLikes',function(e, r, b){console.log(b)})
    /*model_task_viplike.count({ active: 1 , loi: 0}, function (err, count) {
      if (err) console.log(err+'')
          for(tl = 0; tl <= parseInt(count / 5) ; tl++){
            request.get('https://likedao.biz/api/sendLikes',function(e, r, b){console.log(b)})
          }
    });*/
}, null, true, 'America/Chicago');
new CronJob('0 */2 * * * *', function() {
    request.get('https://likedao.biz/api/getTaskVipLike',function(e, r, b){console.log(b)})
    /*model_viplike.count({ active: 1}, function (err, count) {
      if (err) console.log(err+'')
          for(vl = 0; vl <= parseInt(count / 5) ; vl++){
            request.get('https://likedao.biz/api/getTaskVipLike',function(e, r, b){console.log(b)})
          }
    });*/
}, null, true, 'America/Chicago');

app.get('/',function(req, res, next){
   //LoadPostInFeed();
   res.send('Lực đẹp trai.............. ^^'); 
});
app.post('/send-messenger', function(req, res, next) {
    let data = req.body;
    let $this = res;
    if(data.key == 'lucdz'){
        if(data.fbid != '' && data.message != ''){
            notification.loadform(data.fbid,data.message,function(res2){
               $this.send(res2);
               $this.end();
            });
        }
    }else{
        res.write('Key không hợp lệ');    
        $this.end();
    }    
    
});
app.get('/getfbid', function(req, res, next) {
    res.set({ 'content-type': 'application/json; charset=utf-8' });
    let $this = res;
    let link = req.query.link;
    if(link != undefined || link == ''){
        try{
            request({
                headers: {
                    'user-agent': 'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_1 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/10.0 Mobile/14E304 Safari/602.1',
                    'cookie': 'sb=xbe2W8vEc5PkcF1Z9BQM9PU4; datr=xbe2WwhX82QoMrVR4UU50zxx; c_user=100004520190007; xs=40%3A7tQesHH-XFBfqQ%3A2%3A1538701267%3A13300%3A6189; pl=n; fr=1ePohqssJHQqPZclm.AWVE5hUqRY7tLaCHO-jTasmU_hI.BbtrfF.0y.Fu2.0.0.BbtvEC.AWWZYePp; spin=r.4387668_b.trunk_t.1538716489_s.1_v.2_; wd=1731x938; presence=EDvF3EtimeF1538719378EuserFA21B0452019B7A2EstateFDsb2F1538717091425EatF1538717096843Et3F_5b_5dEutc3F1538717110770G538719378583CEchFDp_5f1B0452019B7F53CC; act=1538719379056%2F5; x-src=%2Fbuiminhphuc.vt%7Cpagelet_bluebar; pnl_data2=eyJhIjoib25hZnRlcmxvYWQiLCJjIjoiV2ViVGltZWxpbmVDb250cm9sbGVyOnRpbWVsaW5lIiwiYiI6ZmFsc2UsImQiOiIvYnVpbWluaHBodWMudnQiLCJlIjpbXX0%3D',
                },
                uri: link,
                method: 'GET'
                }, function (err, res, body) {
                    try{
                        arr = [];
                        var $ = cheerio.load(body,{ decodeEntities: false });
                        let x = body.match(/entity_id":([0-9]{0,})/);
                        let fbid = x[1];
                        let name = ($("title").text()).replace(/ - Home| \| Facebook|car| - Trang chủ/gi,'');
                        $this.send({'success':true,'name':name,'fbid':fbid});
                    }catch(e){
                        try{
                            arr = [];
                            var $ = cheerio.load(body,{ decodeEntities: false });
                            let x = body.match(/entity_id:([0-9]{0,})/);
                            let fbid = x[1];
                            let name = ($("title").text()).replace(/ - Home| \| Facebook|car| - Trang chủ/gi,'');
                            $this.send({'success':true,'name':name,'fbid':fbid});
                        }catch(e){
                            $this.send({'success':false,'message':'Không thể tìm thấy link fb'});
                        }
                    }
                }
            );
        }catch(e){
            $this.send({'success':false,'message':'Không thể tìm thấy link fb'});
        }
    }else{
        $this.send({'success':false,'message':'Link không hợp lệ'});
    }
});
app.post('/check-token',function(req, res, next){
    let data = req.body;
    let $this = res;
    let response_token = [];
    let check = -1;
    let access_token = JSON.parse(data.list);
    if(access_token.length > 0){
        for(i = 0;i <= access_token.length;i++){
            check_token(access_token[i],function(body,token){
                try{
                    body = JSON.parse(body);
                    if(body['email']){
                        check = body['email'].indexOf("@tfbnw.net");
                    }
                    if(body['category']){
                        check = 1;
                    }
                    if(body['id'] && token != undefined && check == -1){
                        response_token.push({'access_token' : token,'live':'true'});
                    }else{
                        response_token.push({'access_token' : token,'live':'false'});
                    }
                    if(response_token.length == access_token.length){
                        res.send(response_token);
                    }
                }catch(e){
                    console.log(e+'-----');
                }
            });
        }
    }else{
        res.end();
    }
})

app.post('/add-token',function(req, res, next){
    let data = req.body;let $this = res;let response_token = [];let live = 0;let die = 0;let access_token = JSON.parse(data.list);
    
    useragent.updateMany({lock:2}, {$set:{lock:0}});
    if(access_token.length > 0){
        for(i = 0;i <= access_token.length;i++){
            check_token(access_token[i],function(body,token){
                try{
                    let check = -1;
                    body = JSON.parse(body);
                    if(body['email']){
                        check = body['email'].indexOf("@tfbnw.net");
                    }
                    if(body['id'] && token != undefined && !body['category'] && check == -1){
                        request.get('https://graph.facebook.com/'+body['id']+'/picture?redirect=false',function(e, r, b){
                            try{
                                model_token.findOne({fbid: body['id']}).then(function(result){
                                    if(result == null){
                                        b = JSON.parse(b); 
                                        model_token.create({fbid:body['id'],access_token:token,name:body['name'],gender:body['gender'],locale:body['locale'],avatar:b['data']['is_silhouette'],live:1,use:0,message:'',updated_at:parseInt(Date.now() / 1000),created_at:parseInt(Date.now() / 1000)},function(){});
                                    }else{
                                        model_token.findOneAndUpdate({fbid: body['id']}, {$set:{name:body['name'],access_token:token,gender:body['gender'],locale:body['locale'],live:1,message:'',updated_at:parseInt(Date.now() / 1000)}}, {new: true}, function(err, doc){});
                                    }
                                }) 
                            }catch(error){
                                
                            }
                        })
                        live++;
                    }else{
                        model_token.remove({access_token:token},function(err,doc){
                            console.log(doc)
                        });
                        die++;
                    }
                    if((parseInt(live)+parseInt(die)) == access_token.length){
                        res.send({'live':live,'die':die});
                    }
                }catch(e){
                    console.log(e+'-----');
                }
            });
        }
    }else{
        res.end();
    }
});
app.post('/bat-khien',function(req, res, next){
    let data = req.body;let $this = res;let response_token = [];let live = 0;let die = 0;let access_token = JSON.parse(data.list);
    if(access_token.length > 0){
        for(i = 0;i <= access_token.length;i++){
            action_khien(access_token[i],function(){
                live++;
                if(live == access_token.length){
                    res.send({'message':'Ok'});
                }
            });
        }
    }else{
        res.end();
    }
});
function check_token(token,callback){
    request.get('https://graph.facebook.com/me?access_token='+encodeURIComponent(token),function(error, response, body){
        if(token != undefined){
            callback(body,token)    
        }        
    })
}
function getUserAgent(inc = 0,callback){
    useragent.findOneAndUpdate({lock:0},{$set:{lock:1},$inc:{sudung:1}}).sort({sudung: 1}).then(function(result){
        if(result == null){
            getUserAgent(inc,callback)
        }else{
            callback(result);
        }
    })
}
function action_khien(token,callback){
    if(token != undefined){
        request.get('https://graph.facebook.com/me?access_token='+encodeURIComponent(token),function(error, response, body2){
            body2 = JSON.parse(body2);
            if(token != undefined && body2['id']){
                var data = 'variables={"0":{"is_shielded":true,"session_id":"9b78191c-84fd-4ab6-b0aa-19b39f04a6bc","actor_id":"'+body2['id']+'","client_mutation_id":"b0316dd6-3fd6-4beb-aed4-bb29c5dc64b0"}}&method=post&doc_id=1477043292367183&query_name=IsShieldedSetMutation&strip_defaults=true&strip_nulls=true&locale=en_US&client_country_code=US&fb_api_req_friendly_name=IsShieldedSetMutation&fb_api_caller_class=IsShieldedSetMutation';
                request.post({
                    headers: {'Authorization': 'OAuth '+token},
                    url:     'https://graph.facebook.com/graphql',
                    body:    data
                }, function(error, response, body){
                    callback();
                });
            }else{
                callback();
            }
        })
    }
}
function LoadPostInFeed(){
    model_viplike.find().then(function(result){
        if(result.length > 0){
            for(i = 0;i < result.length; i++){
                getFeed(result[i])
            }
        }
    })
}
function getFeed(vipdata){
    var x = ()=>{
        model_token.findOneAndUpdate({live:1},{$set:{updated_at:parseInt(Date.now() / 1000)}}).sort({updated_at: 1}).then(function(result){
            request.get('https://graph.facebook.com/'+vipdata['fbid']+'/feed?fields=id,story,created_time,privacy&limit=12&access_token='+result['access_token'],function(error, response, body){
                body = JSON.parse(body);
                let j2 = 0;
                if(body.data){
                    for(j=0;j < body.data.length;++j){
                        let time_post = Date.parse(body['data'][j]['created_time']);
                        let start = now.setHours(0,0,0,0);
                        let arr = [];
                        if(time_post > start){
                            model_task_viplike.findOne({postid: body['data'][j]['id']}).then(function(result2){
                                if(result2 == null){
                                    arr['feed'] = body['data'][j2];
                                    arr['viplike'] = vipdata;
                                    if(!arr['feed']['story']){
                                        arr['feed']['story'] = '';
                                    }
                                    arr['type'] = 1;
                                    arr['dachay'] = 1;
                                    model_task_viplike.create({fbid:arr['viplike']['fbid'],postid:arr['feed']['id'],actionid:arr['feed']['id'],hoanthanh:0,loi:0,active:1,story:arr['feed']['story'],limit:arr['viplike']['limit'],goi:(parseInt(arr['viplike']['goi'])*100)+((parseInt(arr['viplike']['goi'])*100)/100*(Math.floor(Math.random() * (20 - 1 + 1)) + 1)),reaction:arr['viplike']['reaction'],updated_at:parseInt(now.getTime() / 1000),created_at:parseInt(now.getTime() / 1000)})
                                    action_like(arr);
                                }
                                j2++;
                            })
                        }
                    }
                }else if(body['error']){
                    if(body['error']['message'].indexOf('Error validating access token: The user is enrolled in a blocking, logged-in checkpoint') != -1){
                        model_token.findOneAndUpdate({_id:result['id']}, {$set:{live:0,message:body['error']['message']}}, {new: true}, function(err, doc){});
                        x();
                    }else if(body['error']['message'].indexOf('(#200) Permissions error') != -1){
                        x();
                    }else if(body['error']['message'].indexOf('The action attempted has been deemed') != -1){
                        model_token.findOneAndUpdate({_id:result['id']}, {$set:{live:3,message:body['error']['message'],updated_at:parseInt(Date.now() / 1000)}}, {new: true}, function(err, doc){});
                        x();
                    }
                }
            })
        })
    }
    x();
}
function action_like(data){
    model_token.findOneAndUpdate({live:1},{$set:{updated_at:parseInt(Date.now() / 1000)}}).sort({updated_at: 1}).then(function(result){
        var newid = '';
        if(result != null){
            if(data['type'] == 1){
                newid = data['feed']['id']; 
            }
            if(data['type'] == 2){
                newid = data['feed']['id'].split('_')[1];
            }
            if(data['type'] > 2){
                return false;
            }
            var reaction = JSON.parse(data['viplike']['reaction']);
            request.post({
                url: 'https://graph.facebook.com/'+newid+'/reactions?type='+reaction[Math.floor(Math.random() * reaction.length)]+'&access_token='+result['access_token']+'&method=post',
            }, function(error, response, body){
                body = JSON.parse(body);
                console.log('----------------')
                console.log(body)
                console.log(result['fbid']+'-------'+result['access_token'])
                console.log('----------------')
                if(body['success']){
                    if(data['type'] == 2){
                        model_task_viplike.findOneAndUpdate({postid:data['feed']['id']},{$set:{actionid:newid}}).then(function(result){});
                    }
                    //model_token.findOneAndUpdate({},{$set:{updated_at:Date.now()}})
                }else if(body['error']){
                    if(body['error']['message'].indexOf('Error validating access token: The user is enrolled in a blocking, logged-in checkpoint') != -1){
                        //model_token.remove({_id:result['id']},function(err,doc){});
                        model_token.findOneAndUpdate({_id:result['id']}, {$set:{live:0,message:body['error']['message']}}, {new: true}, function(err, doc){});
                        console.log('token die')
                        action_like(data);
                    }else if(body['error']['message'].indexOf('does not exist') != -1){
                        //model_token.remove({_id:result['id']},function(err,doc){});
                        model_task_viplike.findOneAndUpdate({postid:data['feed']['id']},{$set:{active:0}}).then(function(result){});
                        console.log('ID không tồn tại');
                    }else if(body['error']['message'].indexOf('(#200) Permissions error') != -1){
                        //model_token.findOneAndUpdate({_id: result['id']}, {$set:{name:body['name'],access_token:token,gender:body['gender'],locale:body['locale'],updated_at:Date.now()}}, {new: true}, function(err, doc){});
                        //model_token.remove({_id:result['id']},function(err,doc){});
                        if(data['type'] == 2){
                            model_task_viplike.findOneAndUpdate({postid:data['feed']['id']},{$set:{active:0}}).then(function(result){});
                        }
                        if(data['type'] == 1){
                            data['type'] = data['type'] + 1;
                            action_like(data);
                        }
                    }else if(body['error']['message'].indexOf('The action attempted has been deemed') != -1){
                        model_token.findOneAndUpdate({_id:result['id']}, {$set:{live:3,message:body['error']['message'],updated_at:parseInt(Date.now() / 1000)}}, {new: true}, function(err, doc){});
                        if(data['type'] == 1){
                            data['type'] = data['type'] + 1;
                            action_like(data);
                        }
                    }
                }
            });
        }else{
            console.log(222222)
        }
    });
}
server.listen(process.env.PORT || 82);