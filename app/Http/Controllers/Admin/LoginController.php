<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Admin;

use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    function login(Request $request){
        $admin = Admin::where('taikhoan','=',$request->username)->where('matkhau','=',md5($request->password))->first();
        if(!$admin){
            return response()->json(['success'=>false,'message'=>'Tài khoản hoặc mật khẩu không hợp lệ'],404);
        }else{
            Auth::guard('admin')->login($admin);
            return response()->json(['success'=>true,'message'=>'Đăng nhập thành công','redirect'=>'/admin'],200);
        }
    }
    function logout(){
        if(Auth::guard('admin')->check()){
            Auth::guard('admin')->logout();    
        }
        return redirect('admin');
    }
}
