<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client as Client;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\TaskVipLike;
use App\Viplike;
use App\Token;

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
    function LoadPostInFeed(){
        $vipid = Viplike::where('active',1)->orderBy('updated_ad','asc')->limit(5)->get();
        
        foreach($vipid as $vid){
            $check = TaskVipLike::where('created_at','>',strtotime(Carbon::today()))->count();
            if($check < 12){
                $client = new Client(['http_errors' => false]);
                $res = $client->request('GET', 'https://graph.facebook.com/'.$vid['fbid'].'/feed?fields=id,story,created_time,privacy&limit=12&access_token=EAAAAUaZA8jlABAI7cNLZBQv4rnvvVUR0EIIpPiUDiZCZCxKAJNSe54tVDtzUw3FOvZC9QwF48738DrrQuvbDx5juT8b54LFhiN4nvZC13Nzf1p8aEt93Kz0EpvGjbAbOJItMmSVGdidJTRums9bjkBLASL9K7ZAgORtWZBfuZBxas4QZDZD');
                $stCode = $res->getStatusCode();
                if (200 === $stCode) {
                    foreach(json_decode($res->getBody(),true)['data'] as $pid){
                        TaskVipLike::firstOrCreate(['fbid'=>$vid['fbid'], 'postid'=>$pid['id'],'hoanthanh'=>0,'story'=>@$pid['story'],'limit'=>$vid['limit'],'goi'=>$vid['goi'],'reaction'=>$vid['reaction'],'time'=>strtotime($pid['created_time']),'updated_at'=>strtotime(Carbon::now()),'created_at'=>strtotime(Carbon::now())]);
                    }
                }else {
                  return array('success'=>false,'type'=>'error','message'=>'Có lỗi xảy ra không thể load feed vui lòng thử lại sau !!!','error_code'=>$stCode);
                }
            }
        }
    }
    function Likes(){
        $check = Token::select(['access_token'])->get();
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
    function token(){
        $check = Token::select('access_token')->get();
        return $check;
    }
}
