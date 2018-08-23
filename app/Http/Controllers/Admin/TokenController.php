<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TokenController extends Controller
{
    function tach_token(Request $request){
        $list = $request->list;
        preg_match_all('/EAAA[A-Za-z0-9]{0,}/i',$list,$access_token);
        return response()->json(['tong'=>sizeof($access_token[0]),'access_token'=>$access_token[0]]);
    }
    function check_token(Request $request){
        
    }
}
