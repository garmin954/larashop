<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GoodsBrand;
use App\Tools\Uploads;

class GoodsBrandController extends BaseController
{
    public function index(Request $request,GoodsBrand $goods_brand_model){
        $list = $goods_brand_model->orderBy('is_sort','desc')->paginate(20);
        return $this->success_msg('Success',$list);
    }

    public function add(Request $request,GoodsBrand $goods_brand_model){
        if(!$request->isMethod('post')){
    		return $this->success_msg('Success',[]);
    	}

    	$data = [
    		'name' => $request->name,
    		'thumb' => empty($request->thumb)?'':$request->thumb,
    		'is_sort' => intval($request->is_sort),
    	];

    	$goods_brand_model->insert($data);
    	return $this->success_msg();
    }

    public function edit(Request $request,GoodsBrand $goods_brand_model,$id){
        if(!$request->isMethod('post')){
            $info = $goods_brand_model->find($id);
    		return $this->success_msg('Success',$info);
    	}

    	$data = [
    		'name' => $request->name,
    		'thumb' => empty($request->thumb)?'':$request->thumb,
    		'is_sort' => intval($request->is_sort),
    	];

    	$goods_brand_model->where('id',$id)->update($data);
    	return $this->success_msg();
    }

    public function del(Request $request,GoodsBrand $goods_brand_model){
        $id = $request->id;
        $ids = explode(',',$id);
        $goods_brand_model->destroy($ids);
        return $this->success_msg();
    }

    public function goods_barnd_upload(){
        $uploads = new Uploads;
        $rs = $uploads->uploads(['is_thumb'=>1,'filepath'=>'goods_barnd/']);
        if($rs['status']){
            return $this->success_msg('Success',$rs['path']);
        }else{
            return $this->error_msg($rs['msg']);
        }
    }
}
