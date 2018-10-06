<?php

namespace App\Http\Controllers\wow;

use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    function setting(){
        $config = DB::collection('setting_w')->where('type','auto_config')->first();
        return view('admin.wow.setting')->with('data',['auto'=>$config]);
    }
    function save_setting(Request $request){
        $config = DB::collection('setting_w')->where('type','auto_config')->update($request->All());
        if($config){
            return ['message'=>'Đã lưu thành công!!!','type'=>'success','success'=>'true'];
        }else{
            return ['message'=>'Có lỗi xảy ra không thể lưu!!!','type'=>'error','success'=>'false'];
        }
    }
    function user($id, Request $request){
        $users = DB::collection('user')->orderBy('created_at','desc')->paginate(100);
        return view('admin.wow.user',['users'=>$users]);
    }
}
