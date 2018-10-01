<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class authHome
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('home')->check()) {
            $path = $request->path();
            $type = 'likes';
            if($path == 'likes' || $path == 'reactions'){
                $str = 'ปั๊มไลค์ - ปั้มไลค์ ปั๊มไลค์ ออโต้ไลค์ แฮคไลค์ ปั้มไลค์ ไลค์สถานะ ไลค์รูปภาพ ไลค์แฟนเพจ ไลค์โพสต์ ปั๊มไลค์ฟรี ปั้มไลค์เฟสบุ๊ค ปั๊มไลค์facebookฟรี โปรไลค์ เพิ่มไลค์';
            }else if($path == 'follows'){
                $str = 'ปั้มติดตาม';
                $type = 'follows';
            }else if($path == 'exchange'){
                $str = 'กรุณาเข้าสู่ระบบก่อนแลกวีไอพี';
            }else if($path == 'topup'){
                $str = 'กรุณาเข้าสู่ระบบก่อนเติมเงิน';
            }else if($path == 'login'){
                $str = 'เข้าสู่ระบบ';
            }else{
                $str = 'ปั๊มไลค์ - ปั้มไลค์ ปั๊มไลค์ ออโต้ไลค์ แฮคไลค์ ปั้มไลค์ ไลค์สถานะ ไลค์รูปภาพ ไลค์แฟนเพจ ไลค์โพสต์ ปั๊มไลค์ฟรี ปั้มไลค์เฟสบุ๊ค ปั๊มไลค์facebookฟรี โปรไลค์ เพิ่มไลค์';
            }
            return response(view('wow.login')->with('data',array('string'=>$str,'type'=>$type)));
        }
        return $next($request);
    }
}
