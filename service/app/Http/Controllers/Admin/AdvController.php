<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Adv;
use App\Models\AdvPosition;
use App\Tools\Uploads;

class AdvController extends BaseController
{
    public function index(Request $request,Adv $adv_model){
        $adv_model = $adv_model->orderBy('adv_sort','asc');
        if(!empty($request->adv_position_id)){
            $adv_model->where('ap_id',$request->adv_position_id);
        }
        $list = $adv_model->paginate(20);
        return $this->success_msg('Success',$list);
    }

    public function add(Request $request,Adv $adv_model,AdvPosition $adv_position_model){
        if(!$request->isMethod('post')){
            $list = $adv_position_model->get();
    		return $this->success_msg('Success',$list);
        }

    	$data = [
    		'adv_title' => $request->adv_title,
    		'ap_id' => $request->ap_id,
    		'adv_link' => $request->adv_link??'',
    		'adv_image' => $request->adv_image,
    		'adv_sort' => intval($request->adv_sort),
            'adv_type' => intval($request->adv_type),
            'adv_start'=> strtotime($request->adv_date[0]),
            'adv_end'=> strtotime($request->adv_date[1]),
    	];

    	$adv_model->insert($data);
    	return $this->success_msg();
    }

    public function edit(Request $request,Adv $adv_model,AdvPosition $adv_position_model,$id){
        if(!$request->isMethod('post')){
            $info = $adv_model->find($id)->toArray();
            $info['adv_date'] = [];
            $info['adv_date'][0] = date('Y-m-d H:m',$info['adv_start']);
            $info['adv_date'][1] = date('Y-m-d H:m',$info['adv_end']);
            $info['list'] = $adv_position_model->get();
    		return $this->success_msg('Success',$info);
    	}

    	$data = [
    		'adv_title' => $request->adv_title,
    		'ap_id' => $request->ap_id,
    		'adv_link' => $request->adv_link??'',
    		'adv_image' => $request->adv_image,
    		'adv_sort' => intval($request->adv_sort),
            'adv_type' => intval($request->adv_type),
            'adv_start'=> strtotime($request->adv_date[0]),
            'adv_end'=> strtotime($request->adv_date[1]),
    	];

    	$adv_model->where('id',$id)->update($data);
    	return $this->success_msg();
    }

    public function del(Request $request,Adv $adv_model){
        $id = $request->id;
        $ids = explode(',',$id);
        $adv_model->destroy($ids);
        return $this->success_msg();
    }

    // 广告上传
    public function adv_upload(){
        $uploads = new Uploads;
        $rs = $uploads->adv_upload(['filepath'=>'adv/']);
        if($rs['status']){
            return $this->success_msg('Success',$rs['path']);
        }else{
            return $this->error_msg($rs['msg']);
        }
    }
}
