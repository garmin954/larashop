<?php

namespace App\Http\Controllers\Weixin;

use App\Models\Goods;
use App\Models\GoodsClass;
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


    /**
     * 获取商品
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGoodsToHome(Request $request)
    {
        $goodsModel = new Goods();
        $page_index = $request->post('page', 1);
        $goods_list = $goodsModel->getGoodsToHome($page_index, 10);
        $total = $goodsModel->getGoodsCountToHome();
        return $this->responseData('', 1, compact('goods_list', 'total'));
    }


    public function getGoodsClass(Request $request)
    {
        $pid = $request->post('pid', 0);
        $goodsClassModel = new GoodsClass();

        $cate_list = $goodsClassModel->get_goods_class_list();

        return $this->responseData('', 1, compact('cate_list'));
    }


    /**
     * 搜索商品
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchGoodsList(Request $request)
    {
        $name = $request->get('name', '');
        if ($name){
            $goodsModel = new Goods();
            $page_size = 10;
            $page_index = $request->post('page', 1);
            $goods_list = Goods::normal()->like('goods_name', $name)->page($page_index, $page_size);
            $total_count= Goods::normal()->like('goods_name', $name)->count();
            $goods_count = ceil($total_count/$page_size);
            return $this->responseData('', 1, compact('goods_list', 'goods_count'));
        }

        return $this->responseData('', 0);
    }


}
