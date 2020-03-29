<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderGoods;

class OrderController extends BaseController
{
    // 获取订单需要的信息
    public function buy(Request $request){

    }

    // 开始生成订单
    public function create_order(Request $request,Order $order_model){
        $data['data'] = $request->info;
        $data['remark'] = $request->remark;
        $data['is_cart'] = $request->type==1?true:false;

        if($request->type == 2){
            $data['is_groupbuy'] = true;
        }

        $rs = $order_model->create_order($data);
        if($rs['status']){
            return $this->success_msg('ok',$rs['data']);
        }else{
            return $this->error_msg($rs['msg']);
        }
    }

    // 根据参数获取订单数据 购物车或者直接购买
    public function get_befor_order(Request $request,Order $order_model){
        $info = $request->info;
        $type = $request->type==1?true:false;

        // 团购
        $is_groupbuy = false;
        if($request->type == 2){
            $is_groupbuy = true;
        }

        $store_arr = $order_model->get_befor_order($info,$type,$is_groupbuy);
        return $this->success_msg('ok',$store_arr['data']);
    }

    // 获取订单信息
    public function get_order_info_by_order_no(Request $request,Order $order_model){
        return $this->success_msg('ok',$order_model->getOrderInfoByOrderNo($request->order_no));
    }

    // 获取订单信息
    public function get_order_info_by_order_id(Request $request,Order $order_model,OrderGoods $order_goods_model){
        $user_info = auth()->user();
        $order_info = $order_model->where('id',$request->order_id)->where('user_id',$user_info['id'])->first();
        $order_info['goods_list'] = $order_goods_model->where('order_id',$order_info['id'])->get();

        if(empty($order_info)){
            return $this->error_msg('非法数据');
        }else{
            return $this->success_msg('ok',$order_info);
        }
    }

    // 取消订单
    public function close_order(Request $request,Order $order_model){
        $order_no = $request->order_no;
        $order_info = $order_model->where('order_no',$order_no)->first();
        if($order_info['pay_status'] == 1){
            return $this->error_msg('订单无法取消');
        }
        $order_model->where('order_no',$order_no)->update(['order_status'=>2,'close_time'=>time()]);
        return $this->success_msg('取消成功');
    }

    // 申请售后
    public function refund(Request $request,Order $order_model){
        $id = $request->id;
        $refund_info = $request->refund_info;
        $refund_status = $request->refund_status;
        $rs = $order_model->where('id',$id)->update(['refund'=>1,'refund_status'=>$refund_status,'refund_info'=>$refund_info]);
        return $this->success_msg('申请成功');
    }

    // 寄回快递单号填写
    public function refund_delivery_no(Request $request,Order $order_model){
        $id = $request->id;
        $refund_delivery_no = $request->refund_delivery_no;
        $rs = $order_model->where('id',$id)->update(['refund'=>1,'refund_step'=>2,'refund_delivery_no'=>$refund_delivery_no]);
        return $this->success_msg('提交成功');
    }

}
