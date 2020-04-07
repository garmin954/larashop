<?php

namespace App\Http\Controllers\Weixin;

use App\Models\Goods;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsController extends BaseController
{
    /**
     * 获取团购产品
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGroupToHome()
    {
        $goodsModel = new Goods();
        $group_list = $goodsModel->getGroupToHome(6);
        return $this->responseData('', 1, compact('group_list'));
    }


    public function getGoodsToHome(Request $request)
    {
        $goodsModel = new Goods();
        $page_index = $request->post('page', 1);
        $goods_list = $goodsModel->getGoodsToHome($page_index, 10);
        return $this->responseData('', 1, compact('goods_list'));
    }
}
