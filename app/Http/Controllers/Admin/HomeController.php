<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    function __construct(){
        $this->middleware('admin');
    }
    function index(){
        return view('admin.index');
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
