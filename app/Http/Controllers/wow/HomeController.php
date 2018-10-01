<?php

namespace App\Http\Controllers\wow;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client as Client;
use Session;
use DB;
use Illuminate\Support\Facades\Auth;
use App\TokenFollows;
use App\TokenLikes;
use App\Home;

class HomeController extends Controller
{
    function __construct(){
        $this->middleware(function ($request, $next) {
            if(!session()->has('auto')){
                $auto_config = DB::collection('setting_w')->where('type','auto_config')->first();
                session()->put('auto',(object)$auto_config);
            }
            return $next($request);
        });
    }
    function index(){
        return view('wow.index');
    }
    function likes(){
        if(!session()->has('likes')){
            session()->flush();
            return Redirect('/likes');
        }
        $log = DB::collection('log_likes_w')->where('fbid',Auth::guard('home')->user()->fbid)->orderBy('time','desc')->get();
        return view('wow.auto.likes')->with('data',json_encode($log));
    }
    function buyfollow(){
        return view('wow.follow.buyfollow');
    }
    function follow(Request $request){
        if(!session()->has('follows')){
            session()->flush();
            return Redirect('/follows');
        }
        $log = DB::collection('log_follows_w')->where('fbid',Auth::guard('home')->user()->fbid)->orderBy('time','desc')->get();
        return view('wow.follow.follows')->with('data',json_encode($log));
    }
    function exchange(){
        return view('wow.topup.exchange');
    }
    function topup(){
        return view('wow.topup.topup');
    }
    function auto(){
        return view('wow.follow.auto');
    }
    function reactions(){
        $log = DB::collection('log_likes_w')->where('fbid',Auth::guard('home')->user()->fbid)->orderBy('time','desc')->get();
        return view('wow.auto.reactions')->with('data',json_encode($log));
    }
    function getToken(Request $request){
        $sig = function($email,$password,$app){
            /*
        	"generate_machine_id" => "1",
        	"generate_session_cookies" => "1",
        	"locale" => "en_US",
            */
        	$data = array(
            	"api_key" => $app['api_key'],
            	"credentials_type" => "password",
            	"email" => $email,
            	"format" => "JSON",
            	"method" => "auth.login",
            	"password" => $password,
            	"return_ssl_resources" => "0",
            	"v" => "1.0"
            );

        	ksort($data);					
        	$args = '';									
        	foreach ($data as $key => $value){
        		$args .= $key.'='.$value;
        	}
        	$data['sig'] = md5($args.$app["secret"]);
            $query = http_build_query($data);
            return $query;
        };
        $apps = array(
            "iphone"=>array(
            "api_key"=>"3e7c78e35a76a9299309885393b02d97",
            "secret"=>"c1e620fa708a1d5696fb991c1bde5662"),
            
            "android"=>array(
            "api_key"=>"882a8490361da98702bf97a021ddc14d",
            "secret"=>"62f8ce9f74b12f84c123cc23437a4a32")
            );
        $app = $apps[$request->app];
        $username = $request->username;
        $password = $request->password;
        return $link = "https://api.facebook.com/restserver.php?".$sig($username,$password,$app);
    }
    function login(Request $request){
        $access_token = $request->access_token;
        $client = new Client(['http_errors' => false]);
        $res = $client->request('GET', 'https://graph.facebook.com/me?access_token='.$access_token);
        $info = json_decode($res->getBody(),true);
        if(isset($info['id']) && !isset($info['category']) && !strpos(@$info['email'],'@tfbnw.net')){
            $user = Home::where('fbid',$info['id'])->first();
            if(!$user){
                Home::create(array(
                    'fbid'=>$info['id'],
                    'name'=>$info['name'],
                    'money'=>0,
                    'roles'=>'member'
                ));
            }else{
                Home::where('fbid',$info['id'])->update(array(
                    'name'=>$info['name']
                ));
            }
            $user = Home::where('fbid',$info['id'])->first();
            if($request->type == 'likes'){
                $token_likes = TokenLikes::where('fbid',$info['id'])->first();
                if($token_likes){
                    $token_likes->live = 1;
                    $token_likes->access_token = $access_token;
                    $token_likes->name = $info['name'];
                    $token_likes->save();
                }else{
                    $token_likes = TokenLikes::create([
                        'fbid'=>$info['id'],
                        'name'=>$info['name'],
                        'access_token'=>$access_token,
                        'gender'=>$info['gender'],
                        'locale'=>$info['locale'],
                        'live'=>1
                    ]);
                }
                $request->session()->put('likes',$token_likes);
            }
            if($request->type == 'follows'){
                $token_follows = TokenFollows::where('fbid',$info['id'])->first();
                if($token_follows){
                    $token_follows->live = 1;
                    $token_follows->access_token = $access_token;
                    $token_follows->name = $info['name'];
                    $token_follows->save();
                }else{
                    $token_follows = TokenFollows::create([
                        'fbid'=>$info['id'],
                        'name'=>$info['name'],
                        'access_token'=>$access_token,
                        'gender'=>$info['gender'],
                        'locale'=>$info['locale'],
                        'live'=>1
                    ]);
                }
                $request->session()->put('follows',$token_follows);
            }
            
            Auth::guard('home')->login($user);
            return Response()->json(['success'=>'true','type'=>'success','message'=>'Đăng nhập thành công!!!','action'=>'location.reload();']);
        }else{
            return Response()->json(['success'=>'false','type'=>'error','message'=>'Token không hợp lệ vui lòng thử lại!!!']);
        }
    }
    function logout(){
        if(Auth::guard('home')->check()){
            session()->flush();
            Auth::guard('home')->logout();    
        }
        return redirect('/');
    }
}
