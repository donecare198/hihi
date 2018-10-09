<?php

namespace App\Http\Controllers\wow;


use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client as Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request as Request2;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\ApiController;
use DB;
use Carbon\Carbon;
use App\TokenFollows;
use App\TokenLikes;

class ActionController extends Controller
{
    function __construct(){
        $this->middleware(function ($request, $next) {
            if(!session()->has('auto')){
                $auto_config = DB::collection('setting_w')->where('type','auto_config')->first();
                session()->put('auto',(object)$auto_config);
            }
            $viplike = DB::collection('user_meta')->where(['active'=>1,'fbid'=>Auth::guard('home')->user()->fbid,'type'=>'like'])->first();
            $vipfollow = DB::collection('user_meta')->where(['active'=>1,'fbid'=>Auth::guard('home')->user()->fbid,'type'=>'follow'])->first();
            if(!$viplike){
                $this->max_likes = (int)session()->get('auto')->member_like;
                $this->time_likes = (int)session()->get('auto')->time_member_like;
            }else{
                $this->max_likes = (int)session()->get('auto')->vip_like;
                $this->time_likes = (int)session()->get('auto')->time_vip_like;
            }
            if(!$vipfollow){
                $this->max_follow = (int)session()->get('auto')->member_follow;
                $this->time_follow = (int)session()->get('auto')->time_member_follow;
            }else{
                $this->max_follow = (int)session()->get('auto')->vip_follow;
                $this->time_follow = (int)session()->get('auto')->time_vip_follow;
            }
            
            $this->captcha = new CaptchaController();
            
            return $next($request);
        });
    }
    function likes(Request $request){
        $start = time();
        //$postid = ApiController::getPostId(Input::get('fbid'));
        $postid = ['success'=>true,'postid'=>Input::get('fbid')];
        $code = Input::get('captcha');
        
        $vip = DB::collection('user_meta')->where(['active'=>1,'fbid'=>Auth::guard('home')->user()->fbid,'type'=>'like'])->first();
        if($vip){
            $checkCaptcha = true;
        }else{
            $checkCaptcha = $this->captcha->checkCaptcha($code);    
        }        
        $this->captcha->setCaptcha();        
        if($postid['success'] === true){
            if((Input::has('captcha') && trim(Input::get('captcha')) != '') || $vip){
                if($checkCaptcha){
                    /********/
                    $lastid = (object)DB::collection('log_likes_w')->where('fbid',session()->get('likes')->fbid)->orderBy('time','desc')->first();
                    if(isset($lastid->_id)){//delay auto
                        if((time() - $lastid->time) < $this->time_likes){
                            $next = date('Y/m/d H:i:s',time()+($this->time_likes - (time() - $lastid->time)));
                            return array('success'=>false,'type'=>'error','message'=>'Vui lòng đợi đủ thời gian '.($this->time_likes - (time() - $lastid->time)).'s để tiếp tục','next'=>$next);
                        }
                    }
                    //if(time() - $lastid->time)
                    $success = 0;
                    $error = 0;
                    if(Input::get('gender') == 'all'){
                        $token = TokenLikes::where('live',1)->orderBy('updated_at','asc')->limit($this->max_likes)->lockForUpdate()->get();
                    }else{
                        $token = TokenLikes::where('live',1)->where('gender',Input::get('gender'))->orderBy('updated_at','asc')->limit($this->max_likes)->lockForUpdate()->get();
                    }
                    if(sizeof($token) > 0){
                        foreach($token as $t){
                            $t->updated_at = Carbon::now();
                            $t->save();
                            $token_id[] = array($t->_id,$t->fbid);
                            $links[] = 'https://graph.facebook.com/'.$postid['postid'].'/reactions?type=LIKE&access_token='.$t->access_token;
                        }
                        $client = new Client();
                        foreach ($links as $key=>$link) {
                            $requests[] = new Request2('POST', $link,['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36']);
                        }
                    
                        $responses = Pool::batch($client, $requests, array(
                            'concurrency' => 200,
                            'fulfilled' => function ($response, $index) use (&$token_id,&$success,&$error) {
                                //echo $response->getBody();
                                $success++;
                            },
                            'rejected' => function ($reason, $index) use (&$token_id,&$success,&$error) {
                                //echo $reason->getResponse()->getBody(true).'<br />';
                                if(strpos($reason->getResponse()->getBody(true),'The action attempted has been deemed')){
                                    TokenLikes::where('_id',$token_id[$index]['0'])->update(['live'=>3]);
                                }else if(strpos($reason->getResponse()->getBody(true),'Error validating access token') || strpos($reason->getResponse()->getBody(true),'The access token is invalid')){
                                    TokenLikes::where('_id',$token_id[$index]['0'])->update(['live'=>0]);
                                } 
                                $error++;
                                // this is delivered each failed request
                            },
                
                        ));
                    }
                    /********/
                    $end = time();
                    $response['success'] = true;
                    $response['message'] = 'Thành công '.$success.'! thời gian '.($end - $start);
                    $response['type'] = 'success';
                    $response['like_sucess'] = $success;
                    $response['error'] = $error;
                    $response['next'] = date('Y/m/d H:i:s',time()+ $this->time_likes);
                    DB::collection('log_likes_w')->insert([
                        'fbid' => session()->get('likes')->fbid,
                        'postid' => $postid['postid'],
                        'client_ip' => $request->ip(),
                        'success' => $success,
                        'error' => $error,
                        'time' => date('c',time())
                        
                    ]);
                }else{
                    $response['success'] = false;
                    $response['type'] = 'error';
                    $response['message'] = 'Captcha không chính xác vui lòng thử lại';
                }
            }else{
                $response['success'] = false;
                $response['type'] = 'error';
                $response['message'] = 'Vui lòng nhập captcha';
            }
        }else{
            $response = array('success'=>false,'type'=>'error','message'=>'Postid không hợp lệ vui lòng thử lại');
        }
        return Response()->json($response);
    }
    function reactions(Request $request){
        $start = time();
        //$postid = ApiController::getPostId(Input::get('fbid'));
        $postid = ['success'=>true,'postid'=>Input::get('fbid')];
        $code = Input::get('captcha');
        $type = Input::get('type');
        
        $vip = DB::collection('user_meta')->where(['active'=>1,'fbid'=>Auth::guard('home')->user()->fbid,'type'=>'like'])->first();
        if($vip){
            $checkCaptcha = true;
        }else{
            $checkCaptcha = $this->captcha->checkCaptcha($code);    
        }        
        $this->captcha->setCaptcha();        
        if($postid['success'] === true){
            if((Input::has('captcha') && trim(Input::get('captcha')) != '') || $vip){
                if($checkCaptcha){
                    /********/
                    $lastid = (object)DB::collection('log_likes_w')->where('fbid',session()->get('likes')->fbid)->orderBy('time','desc')->first();
                    if(isset($lastid->_id)){//delay auto
                        if((time() - $lastid->time) < $this->time_likes){
                            $next = date('Y/m/d H:i:s',time()+($this->time_likes - (time() - $lastid->time)));
                            return array('success'=>false,'type'=>'error','message'=>'Vui lòng đợi đủ thời gian '.($this->time_likes - (time() - $lastid->time)).'s để tiếp tục','next'=>$next);
                        }
                    }
                    //if(time() - $lastid->time)
                    $success = 0;
                    $error = 0;
                    if(Input::get('gender') == 'all'){
                        $token = TokenLikes::where('live',1)->orderBy('updated_at','asc')->limit($this->max_likes)->lockForUpdate()->get();
                    }else{
                        $token = TokenLikes::where('live',1)->where('gender',Input::get('gender'))->orderBy('updated_at','asc')->limit($this->max_likes)->lockForUpdate()->get();
                    }
                    if(sizeof($token) > 0){
                        foreach($token as $t){
                            $t->updated_at = Carbon::now();
                            $t->save();
                            $token_id[] = array($t->_id,$t->fbid);
                            $links[] = 'https://graph.facebook.com/'.$postid['postid'].'/reactions?type='.$type.'&access_token='.$t->access_token;
                        }
                        $client = new Client();
                        foreach ($links as $key=>$link) {
                            $requests[] = new Request2('POST', $link,['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36']);
                        }
                    
                        $responses = Pool::batch($client, $requests, array(
                            'concurrency' => 200,
                            'fulfilled' => function ($response, $index) use (&$token_id,&$success,&$error) {
                                //echo $response->getBody();
                                $success++;
                            },
                            'rejected' => function ($reason, $index) use (&$token_id,&$success,&$error) {
                                //echo $reason->getResponse()->getBody(true).'<br />';
                                if(strpos($reason->getResponse()->getBody(true),'The action attempted has been deemed')){
                                    TokenLikes::where('_id',$token_id[$index]['0'])->update(['live'=>3]);
                                }else if(strpos($reason->getResponse()->getBody(true),'Error validating access token') || strpos($reason->getResponse()->getBody(true),'The access token is invalid')){
                                    TokenLikes::where('_id',$token_id[$index]['0'])->update(['live'=>0]);
                                } 
                                $error++;
                                // this is delivered each failed request
                            },
                
                        ));
                    }
                    /********/
                    $end = time();
                    $response['success'] = true;
                    $response['message'] = 'Thành công '.$success.'! thời gian '.($end - $start);
                    $response['type'] = 'success';
                    $response['like_sucess'] = $success;
                    $response['error'] = $error;
                    $response['next'] = date('Y/m/d H:i:s',time()+ $this->time_likes);
                    DB::collection('log_likes_w')->insert([
                        'fbid' => session()->get('likes')->fbid,
                        'postid' => $postid['postid'],
                        'client_ip' => $request->ip(),
                        'success' => $success,
                        'error' => $error,
                        'time' => date('c',time())
                        
                    ]);
                }else{
                    $response['success'] = false;
                    $response['type'] = 'error';
                    $response['message'] = 'Captcha không chính xác vui lòng thử lại';
                }
            }else{
                $response['success'] = false;
                $response['type'] = 'error';
                $response['message'] = 'Vui lòng nhập captcha';
            }
        }else{
            $response = array('success'=>false,'type'=>'error','message'=>'Postid không hợp lệ vui lòng thử lại');
        }
        return Response()->json($response);
    }
    function follow(Request $request){
        $start = time();
        $lastid = (object)DB::collection('log_follows_w')->where('fbid',session()->get('follows')->fbid)->orderBy('time','desc')->first();
        if(isset($lastid->_id)){//delay auto
            if((time() - $lastid->time) < $this->time_follow){
                $next = date('Y/m/d H:i:s',time()+($this->time_follow - (time() - $lastid->time)));
                return array('success'=>false,'type'=>'error','message'=>'Vui lòng đợi đủ thời gian '.($this->time_follow - (time() - $lastid->time)).'s để tiếp tục','next'=>$next);
            }
        }
        //if(time() - $lastid->time)
        $success = 0;
        $error = 0;
        $token = TokenFollows::where('live',1)->orderBy('updated_at','asc')->limit($this->max_follow)->lockForUpdate()->get();
        if(sizeof($token) > 0){
            foreach($token as $t){
                $t->updated_at = Carbon::now();
                $t->save();
                $token_id[] = array($t->_id,$t->fbid);
                $links[] = 'https://graph.facebook.com/'.session()->get('follows')->fbid.'/subscribers?access_token='.$t->access_token;
            }
            $client = new Client();
            foreach ($links as $key=>$link) {
                $requests[] = new Request2('POST', $link,['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36']);
            }
        
            $responses = Pool::batch($client, $requests, array(
                'concurrency' => 200,
                'fulfilled' => function ($response, $index) use (&$token_id,&$success,&$error) {
                    //echo $response->getBody();
                    $success++;
                },
                'rejected' => function ($reason, $index) use (&$token_id,&$success,&$error) {
                    //echo $reason->getResponse()->getBody(true).'<br />';
                    if(strpos($reason->getResponse()->getBody(true),'The action attempted has been deemed')){
                        TokenFollows::where('_id',$token_id[$index]['0'])->update(['live'=>3]);
                    }else if(strpos($reason->getResponse()->getBody(true),'Error validating access token') || strpos($reason->getResponse()->getBody(true),'The access token is invalid')){
                        TokenFollows::where('_id',$token_id[$index]['0'])->update(['live'=>0]);
                    } 
                    $error++;
                    // this is delivered each failed request
                },
    
            ));
        }
        /********/
        $end = time();
        $response['success'] = true;
        $response['message'] = 'Thành công '.$success.'! thời gian '.($end - $start);
        $response['type'] = 'success';
        $response['like_sucess'] = $success;
        $response['error'] = $error;
        $response['next'] = date('Y/m/d H:i:s',time()+ $this->time_follow);
        DB::collection('log_follows_w')->insert([
            'fbid' => session()->get('follows')->fbid,
            'client_ip' => $request->ip(),
            'success' => $success,
            'error' => $error,
            'time' => date('c',time())
            
        ]);
        return Response()->json($response);
    }
}
