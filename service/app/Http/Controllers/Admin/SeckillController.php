<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Seckill;
use App\Models\SeckillGoods;

class SeckillController extends BaseController
{
    public function index(Request $request,Seckill $seckill_model){
        $seckill_model = $seckill_model->orderBy('start_time','desc');
        $list = $seckill_model->paginate(20);
        return $this->success_msg('Success',$list);
    }

    public function add(Request $request,Seckill $seckill_model){
        if(!$request->isMethod('post')){
    		return $this->success_msg('Success',[]);
        }

    	$data = [
    		'name' => $request->name,
            'start_time'=> strtotime($request->seckill_date[0]),
            'end_time'=> strtotime($request->seckill_date[1]),
    	];

    	$seckill_model->insert($data);
    	return $this->success_msg();
    }

    public function edit(Request $request,Seckill $seckill_model,$id){
        if(!$request->isMethod('post')){
            $info = $seckill_model->find($id)->toArray();
            $info['seckill_date'] = [];
            $info['seckill_date'][0] = date('Y-m-d H:m',$info['start_time']);
            $info['seckill_date'][1] = date('Y-m-d H:m',$info['end_time']);
    		return $this->success_msg('Success',$info);
    	}

    	$data = [
    		'name' => $request->name,
            'start_time'=> strtotime($request->seckill_date[0]),
            'end_time'=> strtotime($request->seckill_date[1]),
    	];

    	$seckill_model->where('id',$id)->update($data);
    	return $this->success_msg();
    }

    public function del(Request $request,Seckill $seckill_model){
        $id = $request->id;
        $ids = explode(',',$id);
        $seckill_model->destroy($ids);
        return $this->success_msg();
    }

    public function del_seckill_goods(Request $request,SeckillGoods $seckill_goods_model){
        $id = $request->id;
        $ids = explode(',',$id);
        $seckill_goods_model->destroy($ids);
        return $this->success_msg();
    }

    // 获取参加的商品
    public function get_add_seckill_goods(Request $request,SeckillGoods $seckill_goods_model){
        $sid = $request->sid;
        $goods_list = $seckill_goods_model->with('goods')->where('sid',$sid)->paginate(20);
        return $this->success_msg('Success',$goods_list);
    }

    // 审核通过
    public function change_status(Request $request,SeckillGoods $seckill_goods_model){
        $id = $request->id;
        $info = $seckill_goods_model->find($id);
        if($info['status'] == 0){
            $seckill_goods_model->where('id',$id)->update(['status'=>1]);
        }else{
            $seckill_goods_model->where('id',$id)->update(['status'=>0]);
        }
        return $this->success_msg('Success');
    }

}
