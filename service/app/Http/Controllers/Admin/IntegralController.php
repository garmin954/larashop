<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\IntegralGoods;
use App\Models\IntegralClass;
use Illuminate\Support\Facades\DB;
use App\Tools\Uploads;

class IntegralController extends BaseController
{
    // 获取列表
    public function index(Request $request,IntegralGoods $integral_goods_mdeol){
        $list = $integral_goods_mdeol->orderBy('id','desc')->paginate(20);
        return $this->success_msg('Success',$list);
    }

    // 添加积分商品
    public function add(Request $request,IntegralGoods $integral_goods_mdeol,IntegralClass $integral_class_mdeol){
        if(!$request->isMethod('post')){
            $data['integral_class'] = $integral_class_mdeol->get();
            return $this->success_msg('Success',$data);
        }

        $goods_images = implode(',',$request->file_list);                           // 商品图片


        $postData = [
            'cid'                   =>  intval($request->cid),                      // 分类ID
            'goods_name'            =>  $request->goods_name,                       // 商品名
            'goods_price'           =>  abs(floatval($request->goods_price)),       // 商品价格
            'goods_market_price'    =>  floatval($request->goods_market_price),     // 商品市场价格
            'goods_num'             =>  abs(intval($request->goods_num)),           // 商品库存
            'goods_images'          =>  $goods_images,                          // 商品图片
            'goods_master_image'    =>  $request->goods_master_image,               // 商品主图片
            'goods_status'          =>  $request->goods_status,                     // 商品状态 上架 下架
            'goods_content'         =>  empty($request->content)?'':$request->content,  // 商品内容
            'is_hot'                =>  $request->is_hot,                           // 是否置顶
            'add_time'              =>  time(),
        ];
        $goods_id = $integral_goods_mdeol->insertGetId($postData); // 插入积分商品表
        return $this->success_msg('Success',$goods_id);
    }

    // 编辑积分商品
    public function edit(Request $request,IntegralGoods $integral_goods_mdeol,IntegralClass $integral_class_mdeol,$id){
        if(!$request->isMethod('post')){
            $data['integral_class'] = $integral_class_mdeol->get();
            $data['integral_info'] = $integral_goods_mdeol->find($id);
            return $this->success_msg('Success',$data);
        }

        $goods_images = implode(',',$request->file_list);                           // 商品图片

        $postData = [
            'cid'                   =>  intval($request->cid),                      // 分类ID
            'goods_name'            =>  $request->goods_name,                       // 商品名
            'goods_price'           =>  abs(floatval($request->goods_price)),       // 商品价格
            'goods_market_price'    =>  floatval($request->goods_market_price),     // 商品市场价格
            'goods_num'             =>  abs(intval($request->goods_num)),           // 商品库存
            'goods_images'          =>  $goods_images,                          // 商品图片
            'goods_master_image'    =>  $request->goods_master_image,               // 商品主图片
            'goods_status'          =>  $request->goods_status,                     // 商品状态 上架 下架
            'goods_content'         =>  empty($request->content)?'':$request->content,  // 商品内容
            'is_hot'                =>  $request->is_hot,                           // 是否置顶
            'add_time'              =>  time(),
        ];
        $goods_id = $integral_goods_mdeol->where('id',$id)->update($postData); // 插入积分商品表
        return $this->success_msg('Success',$goods_id);
    }

    // 删除商品
    public function del(Request $request,IntegralGoods $integral_goods_mdeol){
        $id = $request->id;
        $ids = explode(',',$id);
        $integral_goods_mdeol->destroy($ids);
        return $this->success_msg();
    }


    // 指定上架
    public function goods_status(Request $request,IntegralGoods $integral_goods_mdeol){
        $id = $request->id;
        $user_info = auth()->user();
        $goods_info = $integral_goods_mdeol->find($id);
        $goods_status = $goods_info['goods_status']==1?0:1;
        $integral_goods_mdeol->where('id',$id)->update(['goods_status'=>$goods_status]);
        return $this->success_msg();
    }

    // 指定热门推荐
    public function goods_hot(Request $request,IntegralGoods $integral_goods_mdeol){
        $id = $request->id;
        $goods_info = $integral_goods_mdeol->find($id);
        $goods_status = $goods_info['is_hot']==1?0:1;
        $integral_goods_mdeol->where('id',$id)->update(['is_hot'=>$goods_status]);
        return $this->success_msg();
    }

    // 商品图片上传
    public function integral_upload(){
        $uploads = new Uploads;
        $rs = $uploads->uploads(['filepath'=>'integral/']);
        if($rs['status']){
            return $this->success_msg('Success',$rs['path']);
        }else{
            return $this->error_msg($rs['msg']);
        }
    }
}
