<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use App\Models\Roles;
use App\Models\Menus;
use App\Models\MoneyLog;

class UsersController extends BaseController
{

	public function index(Request $request,Users $users_model){
		$list = $users_model->orderBy('id','desc')->with('roles')->paginate(20);
		return $this->success_msg('Success',$list);
	}

	public function add(Request $request,Users $users_model,Roles $roles_model){
		if(!$request->isMethod('post')){
			$roles_list = $roles_model->get();
			$data['roles_list'] = $roles_list;
			return $this->success_msg('Success',$data);
		}

		$data['username'] = $request->username;
		$data['password'] = Hash::make($request->password);
		$data['add_time'] = time();
		$users = $users_model->create($data);
		$users->roles()->attach($request->roles_list);
		return $this->success_msg();
	}

	public function edit(Request $request,Users $users_model,Roles $roles_model,$id){
		if(!$request->isMethod('post')){
			$data['info'] = $users_model->with('roles')->find($id);
			$roles_list = $roles_model->get();
			$data['roles_list'] = $roles_list;
			return $this->success_msg('Success',$data);
		}

		if(!empty($request->password)){
			$save_data['password'] = Hash::make($request->password);
		}

		if(!empty($request->username)){
			$save_data['username'] = $request->username;
		}

		if(!empty($request->avatar)){
			$save_data['avatar'] = $request->avatar;
		}

		if(!empty($request->phone)){
			$save_data['phone'] = $request->phone;
		}

		if(!empty($request->email)){
			$save_data['email'] = $request->email;
		}

		$users = $users_model->find($id);

		if(!empty($request->roles_list)){
			$users->roles()->sync($request->roles_list);
		}else{
			$users->roles()->sync([]);
		}

		if(!empty($save_data)){
			$users_model->where('id',$id)->update($save_data);
		}

		return $this->success_msg();


	}

	public function del(Request $request,Users $users_model){
		$id = $request->id;
		$ids = explode(',',$id);
		if(in_array('1',$ids)){
			return $this->error_msg('超级管理员不能删除.');
		}
		$users = $users_model->whereIn('id',$ids)->get();
		foreach($users as $v){
			$v->roles()->sync([]);
		}
        $users_model->destroy($ids);
        return $this->success_msg();
	}

	// 获取用户的权限栏目\
	public function get_permission_menus(Request $request,Users $users_model,Roles $roles_model,Menus $menus_model){
		$user_info = auth()->user();
		$user_info = $users_model->find($user_info['id']);

		// 获取角色ID
		$roles_id = [];
		foreach($user_info->roles()->get() as $v){
			$roles_id[] = $v['id'];
		}

		// 获取栏目ID
		$roles_info = $roles_model->whereIn('id',$roles_id)->get();
		$menus_id = [];
		foreach($roles_info as $v){
			foreach($v->menus as $vo){
				if(!in_array($vo->id,$menus_id)){
					$menus_id[] = $vo->id;
				}
			}

		}

		$menus_model = $menus_model->whereIn('id',$menus_id)->orderBy('is_sort','asc');
		if(empty($request->is_type)){
			$menus_list = $menus_model->where('is_type',0)->get();
		}else{
			$menus_list = $menus_model->where('is_type',1)->get();
		}

		// 去查询列表并且生成树状结构
		$menus_list = $menus_model->whereIn('id',$menus_id)->get();
		$new = getChild($menus_list);
		return $this->success_msg('ok',$new);
	}

    public function get_user_info(){
    	$user_info = auth()->user();
    	return $this->success_msg('ok',$user_info);
    }

    // 改变用户资金
    public function change_money(Request $request,Users $user_model,MoneyLog $money_log_model){
        $user_id = $request->user_id;
        $change_type = $request->change_type;
        $type = $request->type;
        $money = $request->money;

        $admin_info = auth()->user();

        // 随机数生成  订单号
        $make_rand = make_rand();

        $user_info = $user_model->find($user_id);

        switch($change_type){
            case 0:
                if(empty($type)){
                    if($user_info['money']>=$money){
                        $user_model->money_change('系统操作','money',-$money,$user_info,$admin_info['nickname'].'执行了该操作','-');
                    }else{
                        return $this->error_msg('余额不够');
                    }

                }else{
                    $user_model->money_change('系统操作','money',$money,$user_info,$admin_info['nickname'].'执行了该操作','-');
                }
            break;
            case 1:
                if(empty($type)){
                    if($user_info['integral']>=$money){
                    $log_data['money'] = $money;
                        $user_model->money_change('系统操作','integral',-$money,$user_info,$admin_info['nickname'].'执行了该操作','-');
                    }else{
                        return $this->error_msg('积分不够');
                    }

                }else{
                    $user_model->money_change('系统操作','integral',$money,$user_info,$admin_info['nickname'].'执行了该操作','-');
                }
            break;
            case 2:
                if(empty($type)){
                    if($user_info['frozen_money']>=$money){
                        $user_model->money_change('系统操作','frozen_money',-$money,$user_info,$admin_info['nickname'].'执行了该操作','-');
                    }else{
                        return $this->error_msg('冻结资金不够');
                    }

                }else{
                    $user_model->money_change('系统操作','frozen_money',$money,$user_info,$admin_info['nickname'].'执行了该操作','-');
                }
            break;

        }

        return $this->success_msg('ok');

    }
}
