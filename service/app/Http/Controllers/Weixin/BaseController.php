<?php

namespace App\Http\Controllers\Weixin;

use EasyWeChat\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected $wxapp;

    public function __construct()
    {
        $config = [
            'app_id' => 'wx8ee1d2607ce3dd67',
            'secret' => '962a13257cae31acd2b9a2cc8077e9d6',
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' => __DIR__.'/wechat.log',
            ],
        ];
        $this->wxapp = Factory::miniProgram($config);
    }


    protected function responseData( string $message='', int $code=1, array $data=[], int $status=200, $headers=[], $options=0)
    {
        if (empty($message)) {
            $message = $code == 1 ? '获取成功！' : '获取失败！';
        }
        return response()->json(compact('message', 'code', 'data'), $status, $headers, $options);
    }


}
