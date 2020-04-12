<?php

namespace App\Http\Controllers\Weixin;

use App\Models\Adv;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends BaseController
{

    public function login(Request $request){
        if (!$code = $request->post('code', '')){
            return $this->responseData('', 0);
        }

        // 获取微信
        $userModel = new Users();
        if($wx_data = $this->wxapp->auth->session($code)){
            $userModel->saveOpenid($wx_data['open_id']);
            return $this->responseData('', 1, compact('wx_data'));
        }

        // 根据openid查用户是否存在


    }

}
