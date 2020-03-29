<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Hooks;

class HooksController extends BaseController
{

    public function index(Request $request,Hooks $hooks_model){
        $list = $hooks_model->orderBy('id','desc')->paginate(20);
        return $this->success_msg('Success',$list);
    }

    public function add(Request $request,Hooks $hooks_model){

    	$data = [
    		'name' => $request->name,
    		'controller_action' => $request->controller_action,
    		'apis' => $request->apis,
    		'is_type' => $request->is_type,
    		'content' => $request->content??'',
    	];

    	$hooks_model->insert($data);
    	return $this->success_msg();
    }


    public function edit(Request $request,Hooks $hooks_model,$id){
        if(!$request->isMethod('post')){
            $hooks_info = $hooks_model->find($id);
            return $this->success_msg('Success',$hooks_info);
        }

        $data = [
            'name' => $request->name,
            'controller_action' => $request->controller_action,
    		'apis' => $request->apis,
    		'is_type' => $request->is_type,
            'content' => $request->content??'',
        ];

        $hooks_model->where('id',$id)->update($data);
        return $this->success_msg();
    }

    public function del(Request $request,Hooks $hooks_model){
        $id = $request->id;
        $ids = explode(',',$id);
        $hooks_model->destroy($ids);
        return $this->success_msg();
    }

}
