<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Schema;
use App\Token;
use App\Viplike;
use App\TaskVipLike;
use App\LogLikes;

class HomeController extends Controller
{
    function __construct(){
        $this->middleware('admin');
    }
    function index(){
        $data['token'] = Token::where('live',1)->count();
        $data['viplike'] = Viplike::where('active',1)->count();
        $data['task'] = TaskVipLike::where(['active'=>1,'loi'=>0])->count();
        $data['log_like'] = LogLikes::count();
        return view('admin.index')->with('data',$data);
    }
    function addUserAgent(){
        $agent = file_get_contents('/var/www/viplike/database/seeds/useragent.txt');
        $agent = explode(PHP_EOL,$agent);
        foreach($agent as $a){
            if($a != ''){
                try{
                    DB::collection('useragent')->insert([
                        'text' => mb_convert_encoding($a, 'UTF-8'),
                        'sudung' => 0,
                        'lock' => 0,
                    ]);
                }catch(Exception $error){
                    var_dump($error);
                }
            }
        }
    }
    function getUserAgent(){
        $result = DB::collection('useragent')->orderBy('sudung',1)->lockForUpdate()->first();
        return DB::collection('useragent')->where('_id',$result['_id'])->update(['sudung'=>$result['sudung'] + 1]);
    }
//    $_CONFIG['TMN'][50]['point'] = 120;					// Point ที่ได้รับเมื่อเติมเงินราคา 50 บาท
//    $_CONFIG['TMN'][90]['point'] = 200;					// Point ที่ได้รับเมื่อเติมเงินราคา 90 บาท
//    $_CONFIG['TMN'][150]['point'] = 350;				// Point ที่ได้รับเมื่อเติมเงินราคา 150 บาท
//    $_CONFIG['TMN'][300]['point'] = 800;				// Point ที่ได้รับเมื่อเติมเงินราคา 300 บาท
//    $_CONFIG['TMN'][500]['point'] = 1200;				// Point ที่ได้รับเมื่อเติมเงินราคา 500 บาท
//    $_CONFIG['TMN'][1000]['point'] = 2000;	
    function install_setting(){
        if(!Schema::hasTable('setting_w')){
            $db = DB::collection('setting_w')->insert([
            'member_like' => 50,
            'time_member_like' => 100,
            'vip_like' => 200,
            'time_vip_like' => 50,
            'member_follow' => 20,
            'time_member_follow' => 20,
            'vip_follow' => 20,
            'time_vip_follow' => 20,
            'member_like_perday' => 20,
            'vip_like_perday' => 25,
            'type' => 'auto_config'
        ]);
        
            return ['message'=>'Lực đẹp trai!!!!'];
        }else{
            return ['message'=>'Lực đẹp trai!!!!'];
        }
    }
}
