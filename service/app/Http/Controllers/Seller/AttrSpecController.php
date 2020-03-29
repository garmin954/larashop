<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Models\AttrSpec;

class AttrSpecController extends BaseController
{
    public function index(AttrSpec $attr_spec_model){
        $user_info = auth()->user();
        $list = $attr_spec_model->where('user_id',$user_info['id'])->orderBy('id','desc')->paginate(20);
        return $this->success_msg('Success',$list);
    }

    public function add(Request $request,AttrSpec $attr_spec_model){
        if(!$request->isMethod('post')){
    		return $this->success_msg('Success',[]);
    	}
        $user_info = auth()->user();
    	$data = [
    		'user_id' => $user_info['id'],
    		'spec_name' => $request->spec_name,
    		'attr_name' => $request->attr_name,
    	];

    	$attr_spec_model->insert($data);
    	return $this->success_msg();
    }

    public function edit(Request $request,AttrSpec $attr_spec_model,$id){
        if(!$request->isMethod('post')){
            $info = $attr_spec_model->find($id);
    		return $this->success_msg('Success',$info);
    	}

    	$data = [
    		'spec_name' => $request->spec_name,
    		'attr_name' => $request->attr_name,
    	];

    	$attr_spec_model->where('id',$id)->update($data);
    	return $this->success_msg();
    }

    public function del(Request $request,AttrSpec $attr_spec_model){
        $id = $request->id;
        $ids = explode(',',$id);
        $attr_spec_model->destroy($ids);
        return $this->success_msg();
    }
}
