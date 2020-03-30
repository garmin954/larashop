<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;

class CartController extends BaseController
{
    // 获取购物车列表
    public function get_cart_list(Cart $cart_model){
        $user_info = auth()->user();
        $cart_list = $cart_model->where('user_id',$user_info['id'])->get()->toArray();
        $list = [];
        foreach($cart_list as $v){
            $list[$v['store_id']][] = $v;
        }
        $list = array_merge($list);
        return $this->success_msg('ok',$list);
    }

    // 获取购物车数量
    public function get_cart_count(Cart $cart_model){
        if (auth()->check()){
            $user_info = auth()->user();
            $count = $cart_model->where('user_id',$user_info['id'])->count();
            return $this->success_msg('ok',$count);            
        }
        return $this->success_msg('ok');
    }

    // 加入购物车
    public function add_cart(Request $request,Cart $cart_model){
        $user_info = auth()->user();
        $data = [
            'user_id'           =>  $user_info['id'],
            'spec_id'           =>  $request->spec_id??0,
            'goods_id'          =>  $request->id,
            'seller_id'         =>  $request->user_id,
            'store_id'          =>  $request->store_info['id'],
            'store_name'        =>  $request->store_info['store_name'],
            'goods_name'        =>  $request->goods_name,
            'image'             =>  get_format_image($request->goods_master_image,200),
            'goods_price'       =>  floatval($request->goods_price),
            'goods_num'         =>  intval($request->buy_num),
            'goods_spec'        =>  $request->goods_spec_name??'-',
        ];
        $rs = $cart_model->add_cart($data);

        if($rs['status']){
            return $this->success_msg($rs['msg'],$rs['status']);
        }else{
            return $this->error_msg($rs['msg'],$rs['status']);
        }

    }

    // 修改购物车参数
    public function change_cart(Request $request,Cart $cart_model){
        $user_info = auth()->user();
        $data = [
            'id'  =>  $request->id,
            'goods_num' =>$request->goods_num,
            'type'  =>  $request->type??0,
        ];
        $change_type = empty($request->change_type)?false:true;

        $rs = $cart_model->change_cart($data,$change_type);
        if($rs['status']){
            return $this->success_msg($rs['msg'],$rs['status']);
        }else{
            return $this->error_msg($rs['msg'],$rs['status']);
        }

    }

    // 删除购物车参数
    public function del_cart(Request $request,Cart $cart_model){
        $ids = explode(',',$request->ids);
        $rs = $cart_model->del_cart($ids);

        if($rs){
            return $this->success_msg('移除购物车成功',$rs);
        }else{
            return $this->error_msg('移除失败');
        }
    }

}
