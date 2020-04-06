<?php

namespace App\Http\Controllers\Weixin;

use App\Models\Goods;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsController extends BaseController
{
    public function getGroupToHome()
    {
        $goodsModel = new Goods();
        $group_list = $goodsModel->getGroupToHome(6);
        return $this->responseData('', 1, compact('group_list'));
    }
}
