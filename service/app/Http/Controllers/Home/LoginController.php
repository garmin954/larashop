<?php

namespace App\Http\Controllers\Home;

use App\Http\Requests\Home\Login\RegisterRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use App\Models\SmsLog;
use App\Tools\Sms;

class LoginController extends BaseController
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request, Users $users_model,SmsLog $sms_log_model)
    {
        $credentials = $request->only(['phone', 'password']);

        // 判断验证码和手机号码是否可以使用
        $code = $request->code;

        // 有没有短信验证码
        if($code != ''){
            $sms_log = $sms_log_model->where(['code'=>json_encode(['code'=>$code]),'phone'=>$request->phone,'is_type'=>1])->where('add_time','>',time()-300)->first();
            if(empty($sms_log)){
                return $this->error_msg('验证码错误');
            }
            if($sms_log['is_use'] <= 0){
                return $this->error_msg('短信验证码已经失效请重新生成！');
            }
        }


        if (! $token = auth()->attempt($credentials)) {
            return $this->error_msg('账号或密码错误');
        }

        if($code != ''){
            $sms_log_model->where('id',$sms_log['id'])->update(['is_use'=>1]); // 修改短信已经使用过
        }

        $user_info = auth()->user();
        $users_model->where('phone',$request->phone)->update(['last_login_time'=>$user_info['login_time'],'login_time'=>time()]); // 更新登录时间和上次登录时间
        unset($user_info['password']); // 去掉存储的密码
        unset($user_info['pay_password']); // 去掉存储的密码

        return response()->json([
            'code'=>200,
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user_info' => $user_info,
        ]);
    }

    // 微信登录
    // public function wechat_login(Config $config_model){
    //     $config_format = $config_model->get_format_config();
    //     $config_info = json_decode($config_format['oauth_config'],true);
    //     $config = [
    //         'app_id' => $config_info['appid'],
    //         'secret' => $config_info['app_secret'],
    //         'oauth' => [
    //             'scopes'   => ['snsapi_login'],
    //             'callback' => '/user/index',
    //         ],
    //     ];
    //     $app = Factory::officialAccount($config);
    //     $oauth = $app->oauth;
    //     return $this->success_msg('ok',$oauth->redirect());

    // }

    // 微信Oauth 信息
    public function get_oauth_config(Config $config_model){
        $config_format = $config_model->get_format_config();
        $config_info = json_decode($config_format['oauth_config'],true);
        return $this->success_msg('ok',$config_info);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['code'=>200,'msg' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'code'=>200,
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    // 检测是否已经登录
    public function check_user_login(Request $request){
        if(auth()->parser()->setRequest($request)->hasToken()){
            return $this->success_msg();
        }else{
            return $this->error_msg('Token Not Provided Or Other Error');
        }
    }

    // 注册账号
    protected function register(RegisterRequest $request,Users $users_model){
        $phone = $request->phone;
        $inviter_id = $request->inviter_id??0;
        $hash_password = Hash::make($request->password);

        $user_data = [
            'phone'             =>  $request->phone,
            'username'          =>  $phone.'_'.mt_rand(1000,9999),
            'nickname'          =>  $phone.'_'.mt_rand(1000,9999),
            'password'          =>  $hash_password,
            'inviter_id'        =>  $inviter_id,  // 邀请人
            'login_time'        =>  time(),
            'last_login_time'   =>  time(),
            'add_time'          =>  time(),
        ];

        return $this->success_msg('注册成功！', $users_model->register($user_data));
    }

    // 忘记密码
    public function forget_password(Request $request,Users $users_model,SmsLog $sms_log_model){
        $phone = $request->phone;
        $code = $request->code;
        $password = $request->password;
        if(empty($phone)){
            return $this->error_msg('手机号码不能为空');
        }
        $user_info = $users_model->where('phone',$phone)->first();
        if(empty($user_info)){
            return $this->error_msg('该手机不存在');
        }

        $sms_where = [
            'code'=>json_encode(['code'=>intval($code)]), // 验证码数据
            'phone'=>$phone,
            'is_type'=>5, // 类型
        ];

        $sms_log = $sms_log_model->where($sms_where)->where('add_time','>',time()-300)->first();

        if(empty($sms_log)){
            return $this->error_msg('验证码错误！');
        }

        $hash_password = Hash::make($password);

        $user_data = [
            'password'          =>  $hash_password,
        ];

        $rs = $users_model->where('phone',$phone)->update($user_data);
        if($rs){
            $sms_log_model->where('id',$sms_log['id'])->update(['is_use'=>1]);
        }

        return $this->success_msg('注册成功！',$rs);
    }


    // 发送短信
    public function send_sms(Request $request){
        $sms = new Sms;
        $phone = $request->phone;
        $is_type = $request->is_type;
        $code = 'SMS_170470087';
        $data = [
            'code'  => mt_rand(10000,99999),
        ];
        return $sms->send_sms($phone,$code,$data,$is_type);
    }

    // 发送邮件
    public function send_email(Request $request){
        $sms = new Sms;
        $email = $request->email;
        $is_type = $request->is_type;
        $data = [
            'code'  => mt_rand(10000,99999),

        ];
        return $sms->send_email($email,$data['code'],$request->title??'青梧信息科技',$is_type);
    }

}
