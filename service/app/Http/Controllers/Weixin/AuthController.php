<?php

namespace App\Http\Controllers\Weixin;

use App\Models\Adv;
use App\Models\Users;
use App\Models\WechatUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{

    public function login(Request $request){
        try{
            $params = $request->only('code', 'encryptedData', 'iv');
            if (count($params) !== 3){
                throw new \Exception('授权失败1！');
            }

            //获取 session_key and openid
            $wx_data = $this->wxapp->auth->session($params['code']);
            if (!isset($wx_data['session_key']) || !isset($wx_data['openid'])){
                throw new \Exception('授权失败2！');
            }
            // 获取是否存在用户
            if (!$wechat_info = WechatUsers::where('xcx_openid', $wx_data['openid'])->first()){
                // 解密session_key
                $result = $this->wxapp->encryptor->decryptData($wx_data['session_key'], $params['iv'], $params['encryptedData']);
                $userModel = new Users();
                if (!$wechat_info = $userModel->createWechatUser($result)){
                    throw new \Exception('创建用户失败！');
                }

            }

            $credentials = [
                "username" => $wechat_info['xcx_openid'],
                "password" => $wechat_info['xcx_openid'],
            ];

            if (!$token = Auth::attempt($credentials)){
                throw new \Exception('生产token失败！');
            }

            unset($wechat_info['xcx_openid']);
            return $this->responseData('登录成功', 1, compact('wechat_info', 'token'));
        }catch (\Exception $exception){

            return $this->responseData($exception->getMessage(), 0);
        }
    }

}
