<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $data['task'] = TaskVipLike::where(['active'=>1,'loi'=>'0'])->count();
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
}
