<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Models\FreightTemplate;

class FreightTemplateController extends BaseController
{
    public function index(FreightTemplate $freight_template_model){
        $user_info = auth()->user();
        $list = $freight_template_model->where('user_id',$user_info['id'])->orderBy('id','desc')->paginate(20);
        return $this->success_msg('Success',$list);
    }

    public function add(Request $request,FreightTemplate $freight_template_model){
        $user_info = auth()->user();
        $data = [
            'user_id'       =>  $user_info['id'],
            'name'          =>  $request->name,
            'content'       =>  isset($request->list)?json_encode($request->list):'',
            'price'         =>  floatval($request->price),
        ];
        $rs = $freight_template_model->insert($data);
        return $this->success_msg('ok',$rs);
    }

    public function edit(Request $request,FreightTemplate $freight_template_model,$id){
        if(!$request->isMethod('post')){
            $info = $freight_template_model->find($id);
            $info['content'] = json_decode($info['content']);
            return $this->success_msg('ok',$info);
        }
        $data = [
            'name'          =>  $request->name,
            'content'       =>  isset($request->list)?json_encode($request->list):'',
            'price'         =>  floatval($request->price),
        ];

        $rs = $freight_template_model->where('id',$id)->update($data);
        return $this->success_msg('ok',$rs);
    }

    public function del(Request $request,FreightTemplate $freight_template_model){
        $id = $request->id;
        $ids = explode(',',$id);
        $freight_template_model->destroy($ids);
        return $this->success_msg();
    }
}
