<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client as Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request as Request2;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\TaskVipLike;
use App\Viplike;
use App\Token;
use App\UserAgent;
use App\LogLikes;

class ApiController extends Controller
{
    function TestNotification(Request $request){
            $client = new Client(['http_errors' => false]);
            $res = $client->request('POST', 'https://api.likedao.biz/send-messenger', 
            [
                'form_params' =>[
                    'fbid' => '100006684784400',
                    'key' => 'lucdz',
                    'message' => '[Test] Admin - Thông báo:
                    
--------------
            
VIPID 100006684784400 của bạn đã hết hạn. Vui lòng gia hạn dịch vụ để tiếp tục sử dụng !!!
            
--------------
            
            '.date('d-m-Y H:i',time())
                ]
            ]);
            $stCode = $res->getStatusCode();
            if (200 === $stCode) {
              return Response()->json(json_decode($res->getBody()));
            }else {
              return array('success'=>false,'type'=>'error','message'=>'Có lỗi xảy ra không thể gửi tin nhắn vui lòng thử lại sau !!!','error_code'=>$stCode);
            }
        
    }
    function Likes(){
        $check = Token::where('live',1)->select(['access_token'])->get();
        foreach($check as $c){
            echo $c['access_token'].'<br />';
        }
        die;
        $check = TaskVipLike::where('created_at','>',strtotime(Carbon::today()))->get();
        foreach($check as $c){
            $token = Token::orderBy('updated_at','desc')->first();
            $token->updated_at = Carbon::now();
            $token->save();
            $type = json_decode($c->reaction,true);
            $client = new Client(['http_errors' => false]);
            $res = $client->request('POST', 'https://graph.facebook.com/'.$c->postid.'/reactions?type='.$type[array_rand($type,1)].'&access_token='.$token->access_token);
            echo $res->getBody();
        }
    }
    
    function sendLikes(){
        $run = function($data,$logIn){
            $success = 0;
            $error = 0;
            $log_likes = [];
            if((int)$data->hoanthanh >= (int)$data->goi){
                TaskVipLike::where('_id',$data->_id)->update(['active'=>0]);
                return false;
            }else if(((int)$data->goi - (int)$data->hoanthanh) > (int)$data->limit){
                $limit = (int)$data->limit;
            }else{
                $limit = (int)$data->goi - (int)$data->hoanthanh;
            }
            $token = Token::select(['access_token'])->where('live',1)->whereNotIn('fbid',$logIn)->orderBy('updated_at','asc')->limit($limit)->lockForUpdate()->get();
            if(sizeof($token) > 0){
                foreach($token as $t){
                    $t->updated_at = Carbon::now();
                    $t->increment('use',1);
                    $t->save();
                    $type = json_decode($data->reaction,true)[array_rand(json_decode($data->reaction,true),1)];
                    $token_id[] = array($t->_id,$t->fbid,$type);
                    $links[] = 'https://graph.facebook.com/'.$data->actionid.'/reactions?type='.$type.'&access_token='.$t->access_token;
                }
                $client = new Client();
                foreach ($links as $key=>$link) {
                    $requests[] = new Request2('POST', $link,['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36']);
                }
            
                $responses = Pool::batch($client, $requests, array(
                    'concurrency' => 200,
                    'fulfilled' => function ($response, $index) use (&$data,&$token_id,&$success,&$error,&$log_likes) {
                        //echo $response->getBody();
                        $log_likes[] = array('fbid'=>$token_id[$index]['1'], 'actionid'=>$data->actionid, 'type'=>$token_id[$index]['2'],'updated_at'=>Carbon::now(),'created_at'=>Carbon::now());
                        $success++;
                    },
                    'rejected' => function ($reason, $index) use (&$data,&$token_id,&$success,&$error) {
                        //echo $reason->getResponse()->getBody(true).'<br />';
                        if(strpos($reason->getResponse()->getBody(true),'does not exist')){
                            TaskVipLike::where('_id',$data->_id)->update(['loi'=>1]);
                        }else if(strpos($reason->getResponse()->getBody(true),'The action attempted has been deemed')){
                            Token::where('_id',$token_id[$index]['0'])->update(['live'=>3]);
                        }else if(strpos($reason->getResponse()->getBody(true),'Error validating access token: The user is enrolled in a blocking, logged-in checkpoint')){
                            Token::where('_id',$token_id[$index]['0'])->update(['live'=>0]);
                        } 
                        $error++;
                        // this is delivered each failed request
                    },
        
                ));
                if(sizeof($log_likes) > 0){
                    LogLikes::insert($log_likes);    
                }
                
                TaskVipLike::where('_id',$data->_id)->increment('hoanthanh',$success);
                var_dump(['success'=>$success,'error'=>$error]).'<br />';
            }
        };
            
        $task = TaskVipLike::where(['active'=>1,'loi'=>0])->orderBy('updated_at','asc')->lockForUpdate()->limit(10)->get();
        foreach($task as $t){
            $t->updated_at = Carbon::now();
            $t->save();
            $logIn = array();
            $log = LogLikes::select('fbid')->where('actionid',$t->actionid)->get();
            if(sizeof($log) > 0){
                foreach($log as $l){
                    $logIn[] = $l->fbid;
                }
            }
            $run($t,$logIn);
        }
    }
    function getTaskVipLike(){
        echo date('d-m-Y H:i:s',time()).'<br />';
        $viplike = Viplike::where('active',1)->orderBy('updated_at','asc')->lockForUpdate()->limit(10)->get();
        foreach($viplike as $v){
            $v->updated_at = Carbon::now();
            $v->save();
            $this->getFeed($v);
        }
        echo '<br />'.date('d-m-Y H:i:s',time());
    }
    function getFeed($vipid){
        $res = 0;
        $token = Token::select(['access_token','updated_at','_id'])->where('live',1)->orderBy('updated_at','asc')->limit(1)->lockForUpdate()->first();
        $token->updated_at = Carbon::now();
        $token->save();
        $token_id = $token->_id;
        $links[] = 'https://graph.facebook.com/'.$vipid->fbid.'/feed?limit=12&fields=id,story,created_time,privacy&access_token='.$token->access_token;
        $client = new Client();
        foreach ($links as $key=>$link) {
            $requests[] = new Request2('GET', $link,['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36']);
        }
        $responses = Pool::batch($client, $requests, array(
            'concurrency' => 999,
            'fulfilled' => function ($response, $index) use (&$res,$vipid,$token_id){
                $data = json_decode($response->getBody())->data;
                $date = strtotime(date('d-m-Y 00:00:00',time()));
                foreach($data as $d){
                    if($date < strtotime($d->created_time)){
                        $taskid = TaskVipLike::where('postid',$d->id)->first();
                        if(!$taskid){
                            $last_task = TaskVipLike::create(array('reaction'=>$vipid->reaction, 'fbid'=>$vipid->fbid, 'postid'=>$d->id, 'actionid'=>$d->id, 'hoanthanh'=>1,'story'=>(isset($d->story) ? $d->story : ''),'limit'=>$vipid->limit, 'loi'=>0, 'active'=>1,'goi'=>($vipid->goi * 100) + (($vipid->goi * 100)/100*rand(1,20))));
                            $this->action_like($last_task);
                        }                        
                    }
                }
            },
            'rejected' => function ($reason, $index) use (&$res,$vipid,$token_id){
                if(strpos($reason->getResponse()->getBody(true),'does not exist')){
                    $vip = Viplike::select(['active'])->where('fbid',$vipid->fbid)->first();
                     if($vip->active == 2){
                        $vip->active = 0;
                        $vip->save();
                     }else{
                        $vip->active = 2;
                        $vip->save();
                        $this->getFeed($vipid);
                     }
                }else if(strpos($reason->getResponse()->getBody(true),'The action attempted has been deemed')){
                    Token::where('_id',$token_id)->update(['live'=>3]);
                    $this->getFeed($vipid);
                }else if(strpos($reason->getResponse()->getBody(true),'Error validating access token: The user is enrolled in a blocking, logged-in checkpoint')){
                    Token::where('_id',$token_id)->update(['live'=>0]);
                    $this->getFeed($vipid);
                }
            },    
        ));
    }
    function action_like($data){
        $token = Token::where('live',1)->orderBy('updated_at','asc')->limit(1)->lockForUpdate()->first();
        $type = json_decode($data->reaction,true)[array_rand(json_decode($data->reaction,true),1)];
        $client = new Client(['http_errors' => false]);
        $res = $client->request('POST', 'https://graph.facebook.com/'.$data->actionid.'/reactions?type='.$type.'&access_token='.$token->access_token);
        $stCode = $res->getStatusCode();
        if (200 != $stCode) {
            $text = json_decode($res->getBody(),true)['error']['message'];
            if(strpos($text,'does not exist')){
                TaskVipLike::where('_id',$data->_id)->update(['loi'=>1]);
            }else if(strpos($text,'(#200) Permissions error')){
                if($data->loi == 0){
                    $data = TaskVipLike::where('_id',$data->_id)->update(['loi'=>2,'actionid'=>explode('_',$data->actionid)[1]]);
                    $this->action_like($data);
                }
                if($data->loi == 2){
                    $data = TaskVipLike::where('_id',$data->_id)->update(['loi'=>1,'active'=>0]);
                }
            }else if(strpos($text,'The action attempted has been deemed')){
                Token::where('_id',$token->_id)->update(['live'=>3]);
            }else if(strpos($text,'Error validating access token: The user is enrolled in a blocking, logged-in checkpoint')){
                Token::where('_id',$token->_id)->update(['live'=>0]);
            }
        }
    }
    public static function getPostId($link){
        //$link = $request->link;
        if(strpos($link,'https://www.facebook.com/') !== false){
            $link = $link;
        }else{
            $link = 'https://www.facebook.com/'.$link;
        }
        $client = new Client(['http_errors' => false]);
        $res = $client->request('GET', $link);
        $text = $res->getBody();
        preg_match('/feed_subtitle_([0-9\:\;]{0,})"/',$text,$matches);
        if(sizeof($matches) > 0){
            if(strpos($matches[1],':') !== false){
                $postid = explode(':',$matches[1]);
                $postid = $postid[0];
            }else if(strpos($matches[1],';') !== false){
                $postid = explode(';',$matches[1]);
                $postid = $postid[0].'_'.$postid[1];
            }else{
                return array('success'=>false,'type'=>'error','message'=>'Không thể tìm thấy post id vui lòng thử lại');
            }
        }else{
            return array('success'=>false,'type'=>'error','message'=>'Không thể tìm thấy post id vui lòng thử lại');
        }
        return array('success'=>true,'type'=>'success','message'=>'Đã xong','postid'=>$postid);
    }
    function getUserAgent(){
        $useragent = UserAgent::orderBy('use','asc')->limit(1)->lockForUpdate()->first();
        $useragent->use = $useragent->use + 1;
        $useragent->save();
        return $useragent->text;
    }
}
