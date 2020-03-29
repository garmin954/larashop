<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Agreement;

class AgreementController extends BaseController
{
    public function index(Agreement $agreement_model){
        $list = $agreement_model->orderBy('id','desc')->paginate(20);
        return $this->success_msg('Success',$list);
    }

    public function add(Request $request,Agreement $agreement_model){
        if(!$request->isMethod('post')){
    		return $this->success_msg('Success',[]);
    	}

    	$data = [
    		'name' => $request->name,
    		'ename' => $request->ename,
    		'content' => $request->content,
    	];

    	$agreement_model->insert($data);
    	return $this->success_msg();
    }

    public function edit(Request $request,Agreement $agreement_model,$id){
        if(!$request->isMethod('post')){
            $info = $agreement_model->find($id);
    		return $this->success_msg('Success',$info);
    	}

    	$data = [
    		'name' => $request->name,
    		'ename' => $request->ename,
    		'content' => $request->content,
    	];

    	$agreement_model->where('id',$id)->update($data);
    	return $this->success_msg();
    }

    public function del(Request $request,Agreement $agreement_model){
        $id = $request->id;
		$ids = explode(',',$id);
        $agreement_model->destroy($ids);
        return $this->success_msg();
    }
}
