<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function responseData( string $message='', int $code=1, array $data=[], int $status=200, $headers=[], $options=0){
        if (empty($message)){
            $message = $code==1 ? '获取成功！' : '获取失败！';
        }
        return response()->json(compact('message', 'code', 'data'), $status , $headers, $options);
    }
}
