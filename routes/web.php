<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Middleware\admin;

Route::get('/', "HomeController@index");
Route::get('/a',function(){
    $a = new \App\test;
    $a->create(['name'=>'luc']);
    return dd($a->get());
});
Route::post('/getMember','HomeController@getMember');
/******api****/
Route::group(['prefix'=>'api'],function(){
    Route::post('/TestNotification','ApiController@TestNotification');
    Route::get('/Likes','ApiController@Likes');
    Route::get('/sendLikes','ApiController@sendLikes');
    
    
});
//////Group admin
Route::group(['prefix'=>'admin'],function(){
    Route::group(['middleware' => 'admin'],function(){
        
        Route::get('/','Admin\HomeController@index');
        Route::get('/adduseragent','Admin\HomeController@addUserAgent');
        Route::get('/getuseragent','Admin\HomeController@getUserAgent');
        
        Route::any('/logout','Admin\LoginController@logout');
        Route::get('/tach-token',function(){
            return view('admin.token.tach');
        });
        Route::post('/tach-token','Admin\TokenController@tach_token');
        Route::get('/check-token',function(){
            return view('admin.token.check');
        });
        Route::get('/up-khien',function(){
            return view('admin.token.upkhien');
        });
        
        Route::get('/getuidgroup',function(){
            return view('admin.tools.get_member_group');
        });
        
        
        Route::get('/add-token',function(){
            return view('admin.token.add');
        });
        /*****vip like*****/
        Route::get('/viplike','Admin\ViplikeController@like');
        Route::get('/viplike/{id}','Admin\ViplikeController@LoadEdit');
        Route::get('/LoadVipID','Admin\ViplikeController@LoadVipID');
        
        Route::group(['prefix'=>'viplike'],function(){
            Route::post('/install','Admin\ViplikeController@install');
            Route::post('/edit','Admin\ViplikeController@edit');
        });
    });
    
    Route::post('/login','Admin\LoginController@login');
});
