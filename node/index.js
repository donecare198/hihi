var express = require('express');
var app = express();
var request = require('request');
var fs = require('fs');
var cheerio = require("cheerio");
var querystring = require('querystring');
var time_send = {};
var bodyParser = require('body-parser');
var server = require('http').createServer(app);
const CronJob = require('cron').CronJob
const utf8 = require('utf8');
const mongoose = require('mongoose');
var notification = require('./send_messenger.js')


mongoose.connect('mongodb://localhost/viplike');
const model_token = mongoose.model('tokens',new mongoose.Schema({fbid:String,access_token:String,name:String,gender:String,locale:String,avatar:Boolean,updated_at:Date,created_at:Date}))


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
                if(body['id']){
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
                                    model_token.create({fbid:body['id'],access_token:token,name:body['name'],gender:body['gender'],locale:body['locale'],avatar:b['data']['is_silhouette'],updated_at:Date.now(),created_at:Date.now()});
                                }else{
                                    model_token.findOneAndUpdate({fbid: body['id']}, {$set:{name:body['name'],access_token:token,gender:body['gender'],locale:body['locale'],updated_at:Date.now()}}, {new: true}, function(err, doc){});
                                }
                            }) 
                        }catch(error){
                            
                        }
                    })
                    live++;
                }else{
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
})
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
})
function check_token(token,callback){
    request.get('https://graph.facebook.com/me?access_token='+encodeURIComponent(token),function(error, response, body){
        if(token != undefined){
            callback(body,token)    
        }        
    })
}
function action_khien(token,callback){
    if(token != undefined){
        request.get('https://graph.facebook.com/me?access_token='+encodeURIComponent(token),function(error, response, body2){
            body2 = JSON.parse(body2);
            console.log(body2)
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
server.listen(process.env.PORT || 82);