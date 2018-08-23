var request = require('request');
var fs = require('fs');
var cheerio = require("cheerio");
var querystring = require('querystring');
const CronJob = require('cron').CronJob
const utf8 = require('utf8');
var bodyParser = require('body-parser')
var jar = 'datr=IzM6W4nVRfjxzKy7lVnT1Ipq; sb=AjM6W5JAyuIoHlmDn-wQy-Gc; c_user=100004520190007; xs=24%3AGv3yM44_6vcv0A%3A2%3A1534509066%3A13300%3A6189; pl=n; spin=r.4226493_b.trunk_t.1534690336_s.1_v.2_; fr=0u3tVUftzvCU7daRf.AWXrnEEI1KpA3Tt-fizES0khLXE.BbOjMC.rE.Ft3.0.0.Bberef.AWXVGusj; act=1534770234980%2F97; wd=1278x923; presence=EDvF3EtimeF1534771122EuserFA21B0452019B7A2EstateFDsb2F1534771123854EatF1534771121257Et3F_5bDiFA2user_3a1B24557438042A2ErF1EoF11EfF13CAcDiFA2user_3a1B02928143589A2ErF1EoF12EfF14C_5dElm3FA2user_3a1B02928143589A2Eutc3F1534771113120G534771122181CEchFDp_5f1B0452019B7F989CC';

module.exports = {
    loadform : function(id,message,callback){
        request({
        headers: {
            'accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'upgrade-insecure-requests': '1',
            'user-agent': 'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_1 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/10.0 Mobile/14E304 Safari/602.1',
            'cookie': jar,
        },
        uri: 'https://mbasic.facebook.com/messages/read/?fbid='+id+'&_rdr',
        method: 'GET'
        }, function (err, res, body) {
            arr = [];
            var $ = cheerio.load(body,{ decodeEntities: false });
            //fs.appendFile('./lol2.html', body+'\n\n\n\n\n', function(err) {});
            if($('#fua').length > 0){
                 $("#composer_form > input").map(function(i, v){
                  arr.push({val: $(v).val(), name: $(v).attr("name")});
                });   
            }else{
                 $("#composer_form input").map(function(i, v){
                  arr.push({val: $(v).val(), name: $(v).attr("name")});
                });
            }
            
            arr = arr.filter(function(v) {
              return v.val && v.val.length;
            });
            var form = module.exports.arrToForm(arr);
            form.body = message;
            if($('title').html() == 'Log into Facebook | Facebook'){
                callback({'success':false,'type':'error','message':'Cookie không hợp lệ. Xin vui lòng báo cho Admin. Xin cảm ơn !!!'});
            }else{
                module.exports.send_message(form,callback)
            }
            
        });
    },
    
    
    send_message: function (form2,callback){
        
        var formData = querystring.stringify(form2);
        var contentLength = formData.length;
        request({ 
            headers: {
                'Content-Length': contentLength,
                'Content-Type': 'application/x-www-form-urlencoded',
                'accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'cookie': jar,
                'referer': 'https://mbasic.facebook.com/messages/read/?fbid=100009557251788&_rdr',
                'user-agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36',
            },
            uri: 'https://mbasic.facebook.com/messages/send/?icm=1&refid=12',
            body: formData,
            method: 'POST'
          }, function (err, res, body) {
            callback({'success':true,'type':'success','message':'Gửi thành công'});
        });
    },
    
    
    arrToForm: function (form) {
      return module.exports.arrayToObject(
        form,
        function(v) {
          return v.name;
        },
        function(v) {
          return v.val;
        }
      );
    },
    
    arrayToObject: function (arr, getKey, getValue) {
      return arr.reduce(function(acc, val) {
        acc[getKey(val)] = getValue(val);
        return acc;
      }, {});
    }
};