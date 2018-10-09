<?php

namespace App\Http\Controllers\wow;

use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Support\Facades\Input;
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
    function user(){
        if(Input::has('keyword')){
            $users = DB::collection('user')->where('name', 'like', '%' . Input::get('keyword') . '%')->orWhere('fbid', 'like', '%' . Input::get('keyword') . '%')->orderBy('created_at','desc')->paginate(100);
        }else{
            $users = DB::collection('user')->orderBy('created_at','desc')->paginate(100);
        }
        return view('admin.wow.user',['users'=>$users]);
    }
    function view_user($id){
        $users = DB::collection('user')->where('_id',$id)->first();
        return view('admin.wow.view_user',['data'=>$users]);
    }
    function addLike(Request $request){
        $fbid = $request->fbid;
        $like = DB::collection('user_meta')->where('fbid',$fbid)->where('type','like')->where('active',1)->orderBy('created_at','desc')->first();
        if($like){
            $qr = DB::collection('user_meta')->where('fbid',$fbid)->where('type','like')->update(['time_expired'=>date('c',strtotime("+".$request->day." day", strtotime($like['time_expired'])))]);
        }else{
            $qr = DB::collection('user_meta')->insert([
                'fbid'=>$fbid,
                'type'=>'like',
                'money'=>0,
                'time_expired'=>date('c',strtotime("+".$request->day." day", time())),
                'created_at'=>date('c',time()),
                'active'=>1
            ]);
        }
        if(!$qr){
            return Response::json(array('success'=>'true','type'=>'error','message'=>'Lỗi không xác định. Vui lòng thử lại !'),404);
        }else{
            return Response::json(array('success'=>'true','type'=>'success','message'=>'Thêm thành công !'));
        }
    }
    function addFollow(Request $request){
        $fbid = $request->fbid;
        $follow = DB::collection('user_meta')->where('fbid',$fbid)->where('type','follow')->where('active',1)->orderBy('created_at','desc')->first();
        if($follow){
            $qr = DB::collection('user_meta')->where('fbid',$fbid)->where('type','follow')->update(['time_expired'=>date('c',strtotime("+".$request->day." day", strtotime($follow['time_expired'])))]);
        }else{
            $qr = DB::collection('user_meta')->insert([
                'fbid'=>$fbid,
                'type'=>'follow',
                'money'=>0,
                'time_expired'=>date('c',strtotime("+".$request->day." day", time())),
                'created_at'=>date('c',time()),
                'active'=>1
            ]);
        }
        if(!$qr){
            return Response::json(array('success'=>'true','type'=>'error','message'=>'Lỗi không xác định. Vui lòng thử lại !'),404);
        }else{
            return Response::json(array('success'=>'true','type'=>'success','message'=>'Thêm thành công !'));
        }
    }
    function edit_user(Request $request){
        $fbid = $request->fbid;
        $money = (int)$request->money;
        $query = DB::collection('user')->where('fbid',$fbid)->increment('money',$money);
        if(!$query){
            return Response::json(array('success'=>'true','type'=>'error','message'=>'Lỗi không xác định. Vui lòng thử lại !'),404);
        }else{
            return Response::json(array('success'=>'true','type'=>'success','message'=>'Cập nhật thành công !'));
        }
    }
    function blockUser($id,$type){
        $qr = DB::collection('user')->where('_id',$id)->update(['active'=>$type]);
        if(!$qr){
            return Redirect::to('/admin/user_w');
            //return Response::json(array('success'=>'true','type'=>'error','message'=>'Lỗi không xác định. Vui lòng thử lại !'),404);
        }else{
            return Redirect::to('/admin/user_w');
            //return Response::json(array('success'=>'true','type'=>'success','message'=>'Thêm thành công !'));
        }
    }
}
