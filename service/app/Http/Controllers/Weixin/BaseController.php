<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected function responseData( string $message='', int $code=1, array $data=[], int $status=200, $headers=[], $options=0){
        if (empty($message)){
            $message = $code==1 ? '获取成功！' : '获取失败！';
        }
        return response()->json(compact('message', 'code', 'data'), $status , $headers, $options);
    }
}
