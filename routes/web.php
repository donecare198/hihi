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

Route::get('/', "wow\HomeController@index");


Route::get('/buyfollow', "wow\HomeController@buyfollow");
Route::get('/auto', "wow\HomeController@auto");
Route::any('/gettoken', "wow\HomeController@getToken");

Route::group(['middleware' => 'authHome'],function(){
    Route::get('/login', function(){
        return redirect('/likes');
    });
    Route::get('/exchange', "wow\HomeController@exchange");
    Route::get('/reactions', "wow\HomeController@reactions");
    Route::post('/reactions', "wow\ActionController@reactions");
    Route::get('/follows', "wow\HomeController@follow");
    Route::post('/follows', "wow\ActionController@follow");
    Route::get('/likes', "wow\HomeController@likes");
    Route::post('/likes', "wow\ActionController@likes");
    Route::get('/topup', "wow\HomeController@topup");
});


Route::get('/captcha', "CaptchaController@getCaptcha");
Route::post('/login', "wow\HomeController@login");
Route::get('/logout', "wow\HomeController@logout");





Route::post('/getMember','HomeController@getMember');
/******api****/
Route::group(['prefix'=>'api'],function(){
    Route::post('/TestNotification','ApiController@TestNotification');
    Route::get('/Likes','ApiController@Likes');
    Route::get('/sendLikes','ApiController@sendLikes');
    Route::get('/getTaskVipLike','ApiController@getTaskVipLike');
    Route::get('/getPostId','ApiController@getPostId');  
});
//////Group admin
Route::group(['prefix'=>'admin'],function(){
    Route::group(['middleware' => 'admin'],function(){
        
        Route::get('/','Admin\HomeController@index');
        Route::get('/adduseragent','Admin\HomeController@addUserAgent');
        Route::get('/getuseragent','Admin\HomeController@getUserAgent');
        Route::get('/install_setting','Admin\HomeController@install_setting');
        
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
            Route::post('/delete','Admin\ViplikeController@delete');
        });
    });
    
    Route::post('/login','Admin\LoginController@login');
});
