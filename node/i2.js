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
const model_token = mongoose.model('tokens',new mongoose.Schema({fbid:String,access_token:String,name:String,gender:String,locale:String,avatar:Boolean,live:Number,use:Number,useragent:String,updated_at:String,created_at:String}),'tokens')
const model_viplike = mongoose.model('viplike',new mongoose.Schema({fbid:String,thoigian:String,limit:String,goi:String,reaction:Array,thongbao:String,fbid_notification:String,active:Number,updated_at:Date,created_at:Date}),'viplike')
const model_task_viplike = mongoose.model('task_viplike',new mongoose.Schema({fbid:String,postid:String,actionid:String,hoanthanh:Number,loi:String,active:String,story:String,limit:Number,goi:Number,reaction:Array,updated_at:String,created_at:String}),'task_viplike')
const useragent = mongoose.model('useragent',new mongoose.Schema({text:String,sudung:Number}),'useragent')

app.use( bodyParser.json({limit: '50mb'}) );
app.use(bodyParser.urlencoded({extended: true,limit: '50mb'})); 
app.use(function (req, res, next) {
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE');
    res.setHeader('Access-Control-Allow-Headers', 'X-Requested-With,content-type');
    res.setHeader('Access-Control-Allow-Credentials', true);
    next();
});

//
app.get('/',function(req, res, next){
    getUserAgent(1);
   res.send('Lực đẹp trai ^^'); 
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
app.post('/check-token',function(req, res, next){
    let data = req.body;
    let $this = res;
    let response_token = [];
    let access_token = JSON.parse(data.list);
    if(access_token.length > 0){
        for(i = 0;i <= access_token.length;i++){
            check_token(access_token[i],function(body,token){
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
            });
        }
    }else{
        res.end();
    }
})

app.post('/add-token',function(req, res, next){
    let data = req.body;let $this = res;let response_token = [];let live = 0;let die = 0;let access_token = JSON.parse(data.list);
    if(access_token.length > 0){
        for(i = 0;i <= access_token.length;i++){
            check_token(access_token[i],function(body,token){
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
                                    getUserAgent(1,function(agent){
                                        model_token.create({fbid:body['id'],access_token:token,name:body['name'],gender:body['gender'],locale:body['locale'],avatar:b['data']['is_silhouette'],live:1,use:0,useragent:agent.text,updated_at:Date.now(),created_at:Date.now()},function(){
                                            console.log(111111111)
                                        });    
                                    })                                    
                                }else{
                                    model_token.findOneAndUpdate({fbid: body['id']}, {$set:{name:body['name'],access_token:token,gender:body['gender'],locale:body['locale'],live:1,updated_at:Date.now()}}, {new: true}, function(err, doc){});
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
    useragent.findOneAndUpdate({},{$inc:{sudung:inc}}).sort({sudung: 1}).then(function(result){
        callback(result)
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
    request.get('https://graph.facebook.com/'+vipdata['fbid']+'/feed?fields=id,story,created_time,privacy&limit=12&access_token=EAAAAUaZA8jlABAMW1acuN8RiSDQQXQzChKP450rKdCCj9Rnm0aKlnFZCOu3ZAPRnaEf8ZCPKhmeaKmvDo7DBMIiDUgHqHCdZBLRz9Y5ZBZAxtwGPxvWo7kKsMPhxphgCr7xJEaRHJE8CyXlcnoAmzbdi9ViGabx8fLegrZBZAW2D9WAZDZD',function(error, response, body){
        body = JSON.parse(body);
        let j2 = 0;
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
                        model_task_viplike.create({fbid:arr['viplike']['fbid'],postid:arr['feed']['id'],actionid:arr['feed']['id'],hoanthanh:0,loi:0,active:1,story:arr['feed']['story'],limit:arr['viplike']['limit'],goi:arr['viplike']['goi'],reaction:arr['viplike']['reaction'],updated_at:now.getTime(),created_at:now.getTime()})
                        action_like(arr);
                    }
                    j2++;
                })
            }
        }
    })
}
function action_like(data){
    model_token.findOneAndUpdate({live:1},{$set:{updated_at:Date.now()}}).sort({updated_at: 1}).then(function(result){
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
                        model_token.findOneAndUpdate({_id:result['id']}, {$set:{live:0}}, {new: true}, function(err, doc){});
                        console.log('token die')
                        action_like(data);
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
                        model_token.findOneAndUpdate({_id:result['id']}, {$set:{live:3,updated_at:Date.now()}}, {new: true}, function(err, doc){});
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