<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menus;

class MenusController extends BaseController
{
    public function index(Request $request,Menus $menus_model){
        $list = $menus_model->orderBy('is_sort','desc')->get();
        $list = getChild($list);
        $arr['admin'] = [];
        $arr['seller'] = [];
        foreach($list as $v){
            if($v['is_type']==0){
                $arr['admin'][] = $v;
            }else{
                $arr['seller'][] = $v;
            }
        }
        return $this->success_msg('Success',$arr);
    }

    public function add(Request $request,Menus $menus_model){
    	if(!$request->isMethod('post')){
    		$menu_list = $menus_model->orderBy('is_sort','desc')->get();
            $list = getTree($menu_list);

            $arr['admin'] = [];
            $arr['seller'] = [];
            foreach($list as $v){
                $v['name'] = str_repeat('——',$v['lev']).' '.$v['name'];
                if($v['is_type']==0){
                    $arr['admin'][] = $v;
                }else{
                    $arr['seller'][] = $v;
                }
            }

    		return $this->success_msg('Success',$arr);
    	}

    	$data = [
    		'pid' => intval($request->pid),
    		'name' => $request->name,
    		'icon' => $request->icon,
    		'url' => $request->url,
    		'is_sort' => intval($request->is_sort),
    		'is_type' => intval($request->is_type),
    	];

    	$menus_model->insert($data);
    	return $this->success_msg();
    }

    public function edit(Request $request,Menus $menus_model,$id){

        if(!$request->isMethod('post')){
            $res['info'] = $menus_model->find($id);
            $menu_list = $menus_model->orderBy('is_sort','desc')->where('id','<>',$id)->get();
            $res['list'] = getTree($menu_list);
            return $this->success_msg('Success',$res);
        }

        $data = [
            'pid' => intval($request->pid),
            'name' => $request->name,
            'icon' => $request->icon,
            'url' => $request->url,
            'is_sort' => intval($request->is_sort),
            'is_type' => intval($request->is_type),
        ];

        $menus_model->where('id',$id)->update($data);
        return $this->success_msg();
    }

    public function del(Request $request,Menus $menus_model){
        $id = $request->id;
        $ids = explode(',',$id);
        $menus_model->destroy($ids);
        return $this->success_msg();
    }

    // 获取菜单的面包屑 ，根据URL
    public function get_bread_nav(Request $request,Menus $menus_model){
        $url = $request->url;
        $bread_name = [];
        $menus_info = $menus_model->where('url',$url)->first();

        if(empty($menus_info)){
            $bread_name[] = '后台控制';
            return $this->success_msg('Success',$bread_name);
        }

        $bread_name[] = $menus_info['name'];
        if($menus_info['pid']>0){
            $menus_info = $menus_model->where('id',$menus_info['pid'])->first();
            $bread_name[] = $menus_info['name'];
        }
        return $this->success_msg('Success',array_reverse($bread_name));

    }


}
