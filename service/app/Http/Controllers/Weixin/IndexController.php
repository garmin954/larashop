<?php

namespace App\Http\Controllers\Weixin;

use App\Models\Adv;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function getBannerList(Request $request)
    {
        $data['banner_list'] = Adv::where(['ap_id'=> 13])->get();
        return self::responseData('', 1, $data);
    }
}
