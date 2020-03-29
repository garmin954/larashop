<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache; // 缓存不然处理太慢了
use App\Models\Area;

class AreaController extends BaseController
{
    public function index(Area $area_model){
        if (!Cache::has('admin_area')) {
            $list = $area_model->get();
            $list = getAreaChildNode($list);
            Cache::put('admin_area', json_encode($list));
        }else{
            $list = json_decode(Cache::get('admin_area'));
        }
        return $this->success_msg('Success',$list);
    }

    public function add(Request $request,Area $area_model){
        if(!$request->isMethod('post')){
            // $list = $area_model->get_area_tree();
            $arr = [];
            // foreach($list as $v){
            //     $v['name'] = str_repeat('——',$v['lev']).' '.$v['name'];
            //     $arr[] = $v;
            // }
    		return $this->success_msg('Success',$arr);
    	}
    	$data = [
            'name' => $request->name,
            'pid'  => $request->area_info?$request->area_info[count($request->area_info)-1]:0,
    		'area_id' => empty($request->area_id)?'':$request->area_id,
    	];

        $area_model->insert($data);
        Cache::forget('admin_area');// 清空缓存
    	return $this->success_msg();
    }

    public function edit(Request $request,Area $area_model,$id){
        // if(!$request->isMethod('post')){
        //     $info = $area_model->find($id);
        //     $list = $area_model->get_area_list();
        //     $arr = [];
        //     foreach($list as $v){
        //         $v['name'] = str_repeat('——',$v['lev']).' '.$v['name'];
        //         $arr[] = $v;
        //     }
        //     $info['area'] = $arr;
    	// 	return $this->success_msg('Success',$info);
    	// }

    	// $data = [
    	// 	'name' => $request->name,
        //     'pid'  => $request->pid,
    	// 	'area_id' => empty($request->area_id)?'':$request->area_id,
    	// ];

        // $area_model->where('id',$id)->update($data);
        // Cache::flush(); // 清空缓存
    	// return $this->success_msg();
    }

    public function del(Request $request,Area $area_model){
        $id = $request->id;
        $ids = explode(',',$id);
        $area_model->destroy($ids);
        Cache::forget('admin_area');// 清空缓存
        return $this->success_msg();
    }

    // 获取省市区信息
    public function get_area_list(Area $area_model){
        $rs = $area_model->get_area_list();
        return $this->success_msg('Success',$rs);
    }

    // 根据父Area_id 获取子area
    public function get_area_children(Request $request,Area $area_model){
        $area_id = $request->area_id;
        return $area_model->getAreaChildren($area_id);
    }
}
