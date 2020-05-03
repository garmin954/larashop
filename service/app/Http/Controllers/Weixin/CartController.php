<?php


namespace App\Http\Controllers\Weixin;


use App\Models\Cart;
use App\Models\Goods;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CartController extends BaseController
{


    /**
     * 获取购物车商品详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCartList(Request $request)
    {
        $uid = Auth::id();
        $page_index = $request->post('page', 1);
        $page_size = $request->post('limit', 8);

        try {
            $goods_list = Cart::with('goods')->get();

            $goods_count = Cart::where('user_id', $uid)->count();

            return $this->responseData('登录成功', 1, compact('goods_count', 'goods_list'));
        }catch (\Exception $exception){

            return $this->responseData($exception->getMessage(), 0);
        }

    }


    /**
     * 修改购物车商品
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function modifyCartGoods(Request $request)
    {
        $goods_id = $request->post('goods_id', 0);
        $goods_num = $request->post('goods_num', 1);
        $type = $request->post('type', 'add'); // 修改类型

        try{
            $user_id = Auth::id();

            if (!Goods::where('id', $goods_id)->exists()){
                throw new \Exception('商品不存在');
            }
            switch ($type){
                case 'add':
                    if (!Cart::modifyOrInsert(compact('goods_id', 'user_id'), compact('goods_num'))){
                        throw new \Exception('购物车添加商品失败');
                    }
                    break;
            }

        return $this->responseData('添加成功', 1);

        }catch (\Exception $exception){
            return $this->responseData($exception->getMessage(), 0);
        }
    }
}
