<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use App\Admin;
use App\Viplike;

class HomeController extends Controller
{
    function index(){
            //return '<script>alert("Ngọc ăn cứt =)))");</script><div style=" color: red; text-align: center; font-size: 50px; margin-top: 50px; ">Ngọc ăn cứt!!!</div>';
        //return Viplike::create(['fbid'=>'fbid', 'thoigian'=>'fbid', 'cronlike'=>'fbid','limit'=>'fbid','goi'=>'fbid','thongbao'=>'fbid','camxuc'=>'fbid','active'=>'fbid']);
          //return User::create(['name'=>'hihi', 'email'=>'hihi', 'password'=>'']);
          //auth()->login(User::first());
          //Auth::guard('admin')->login(Admin::first());
         // return dd(Auth::guard('admin')->user());
          //return auth()->login(User::first());
         //return view('wow.index');
    }
    function getMember(Request $request){
        $this->getuid($request->url,$request->groupid);
        return 1;
    }
    function getuid($url,$fbid){
        $id = json_decode(file_get_contents(urldecode($url)),true);
        foreach($id['data'] as $i){
            file_put_contents('/var/www/viplike/public/member_group/'.$fbid.'.txt', $i['id'].PHP_EOL, FILE_APPEND | LOCK_EX);    
        }
    }
}
