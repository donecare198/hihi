<?php

namespace App\Http\Controllers\Admin;
use Validator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Viplike;
use App\Token;
use Carbon\Carbon;
class ViplikeController extends Controller
{
    function __construct(){
        $this->middleware('admin');
    }
    function like(){
        //dd(Viplike::orderBy('active','desc')->orderBy('created_at','desc')->get());
        return view('admin.vip.like')->with('data',Viplike::orderBy('active','desc')->orderBy('created_at','desc')->get());
    }
    function install(Request $request){
        
        if($request->fbid_notification == '' && $request->thongbao == 'true'){
            return Response()->json(['success'=>false,'type'=>'error','message'=>'Vui lòng điền điền FBID nhận thông báo hoặc bỏ chọn nhận thông báo. !!!']);
        }        
        $formData = $request->all();
        $formData['reaction'] = json_decode($request->reaction);
        $vipid = Viplike::where('fbid',$request->fbid)->first();  
        $validate = Validator::make(
            $formData,
            [
                'fbid' => 'required|numeric',
                'limit' => 'required|min:5|max:1000|numeric',
                'thoigian' => 'min:1|max:12|numeric',
                'reaction' => 'required|array|min:1',
            ],
        
            [
                'numeric' => ':attribute phải là số ví dụ 100004520190007',
                'required' => ':attribute không được để trống',
                'min' => ':attribute không được nhỏ hơn :min',
                'max' => ':attribute không được lớn hơn :max',
                'array' => ':attribute không hợp lệ',
            ],
            [
                'fbid' => 'FBID',
                'limit' => 'Cảm xúc / lần chạy',
                'reaction' => 'Cảm xúc',
            ]
        
        );
        if($validate->fails()){
            return Response()->json(['success'=>false,'type'=>'error','message'=>$validate->messages()->first()]);
        }
        if($vipid){
            return Response()->json(['success'=>false,'type'=>'error','message'=>'Vip ID đã tồn tại trong hệ thống xin vui lòng kiểm trai lại!!!']);
        }
        $data= $request->All();
        $data['active'] = 1;
        $create = Viplike::create($data);
        if($create){
            return Response()->json(['success'=>true,'type'=>'success','message'=>'Thêm thành công!!!','action'=>'location.reload();']);
        }
    }
    function loadEdit($id){
        $data = Viplike::where('_id',$id)->first();
        foreach(json_decode($data['reaction'],true) as $r){
            $reaction[$r] = $r;
        }
        $data['reaction'] = $reaction;
        return view('admin.vip.edit')->with('data',$data);
    }
    function LoadVipID(){
        return Response()->json(['data'=>Viplike::get()]);
    }
    function edit(Request $request){
        foreach($request->All() as $k=>$v){
           if($k == 'fbid_notification' && $request->thongbao == 'true' && $v == ''){
               return Response()->json(['success'=>false,'type'=>'error','message'=>'Vui lòng điền đầy đủ thông tin cần thiết. !!!']);
           }else if($v == '' && $k != 'fbid_notification'){
               return Response()->json(['success'=>false,'type'=>'error','message'=>'Vui lòng điền đầy đủ thông tin cần thiết. !!!']);
           }
        }
        $vipid = Viplike::findOrFail($request->id);
        $vipid->fill($request->All())->save();
        $vipid->fill(['updated_at'=>Carbon::now()])->save();
        
        return Response()->json(['success'=>true,'type'=>'success','message'=>'Lưu thành công!!!']); 
    }
    function delete(Request $request){
        $vip = Viplike::where('_id',$request->id)->first();
        if($vip->delete() == true){
            return Response()->json(['success'=>true,'type'=>'success','message'=>'Xóa thành công!!!','action'=>'alert("Xóa thành công !!!");location.reload();'],200);
        }else{
            return Response()->json(['success'=>false,'type'=>'error','message'=>'Có lỗi xảy ra không thể xóa!!!'],404);
        }
    }
}