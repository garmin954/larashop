<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\IntegralOrder;

class IntegralOrderController extends BaseController
{
    // 开始生成订单
    public function create_order(Request $request,IntegralOrder $integral_order_model){
        $data['data'] = explode('|',$request->info);
        $data['remark'] = $request->remark;
        $rs = $integral_order_model->create_order($data);
        if($rs['status']){
            return $this->success_msg('ok',$rs['data']);
        }else{
            return $this->error_msg($rs['msg']);
        }
    }

    // 获取用户积分订单
    public function get_user_order(Request $request,IntegralOrder $integral_order_model){
        $user_info = auth()->user(); // 用户信息
        $integral_order_model2 = $integral_order_model;
        if(isset($request->order_no) && !empty($request->order_no)){
            $integral_order_model = $integral_order_model->where('order_no',$request->order_no);
        }
        if(isset($request->order_type) && !empty($request->order_type)){
            $integral_order_model = $integral_order_model->where(get_integral_order_params($request->order_type));
        }
        $data['order_list'] = $integral_order_model->where('user_id',$user_info['id'])->with('integral_order_goods')->orderBy('id','desc')->paginate(20)->toArray();
        $data['order_list']['data'] = get_integral_order_status($data['order_list']['data']);
        $data['order_num']['all'] = $integral_order_model2->where('user_id',$user_info['id'])->count();
        $data['order_num']['wait_send'] = $integral_order_model2->where('user_id',$user_info['id'])->where(get_integral_order_params('WAITSEND'))->count();
        $data['order_num']['wait_rec'] = $integral_order_model2->where('user_id',$user_info['id'])->where(get_integral_order_params('WAITREC'))->count();

        return $this->success_msg('ok',$data);
    }

    // 确定收货
    public function change_order_status(Request $request,IntegralOrder $integral_order_model){
        $order_id = $request->order_id??0;
        $user_info = auth()->user();
        $integral_order_model->where('id',$order_id)->where('user_id',$user_info['id'])->update(['order_status'=>1]);
        return $this->success_msg();
    }
}
