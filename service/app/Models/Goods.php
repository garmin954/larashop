<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AttrSpec;
use App\Tools\Model as BaseModel;

class Goods extends Model
{
    use BaseModel;
    protected $table = "goods"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
    protected $params = ['goods_status'=>1,'goods_verify'=>1];  // 上架   和   审核通过

    /**
     * 关联规格 一对多
     */
    public function spec(){
        return $this->hasMany('App\Models\GoodsSpec','goods_id','id');
    }

    /**
     * 关联规格 一对一
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function spec_once(){
        return $this->hasOne('App\Models\GoodsSpec','goods_id','id');
    }


    /**
     * 获取商品详情
     * @param $id
     * @return array
     */
    public function getGoodsInfoById($id){
        $goods_info = $this->where($this->params)->with('spec')->find($id)->toArray();

        if(empty($goods_info)){
            return [];
        }

        // 秒杀信息
        $seckill_model = new Seckill();
        $seckill_info = $seckill_model->where('start_time','<',time())->where('end_time','>',time())->first()->toArray();
        $seckill_goods_model = new SeckillGoods();
        $is_seckill_goods = $seckill_goods_model->where('sid',$seckill_info['id'])->where('goods_id',$id)->exists();

        $goods_info['is_seckill'] = 0;
        if($is_seckill_goods){
            $goods_info['is_seckill'] = 1;
            $goods_info['seckill_info'] = $seckill_info;
        }

        $spec_list = [];
        if(!empty($goods_info['chose_attr'])){
            $chose_spec_ids = [];
            $chose_attr_arr = explode(',',$goods_info['chose_attr']);

            // 循环获取规格ID
            foreach($chose_attr_arr as $v){
                $val = explode('|',$v);
                if(!in_array($val[0],$chose_spec_ids)){
                    $chose_spec_ids[] = $val[0];
                }
            }

            $attr_spec_model = new AttrSpec();
            $attr_spec_list = $attr_spec_model->whereIn('id',$chose_spec_ids)->get();
            foreach($attr_spec_list as $v){
                $spec_list[$v['id']] = ['name'=>$v['spec_name'],'id'=>$v['id'],'list'=>[]];
            }

            foreach($chose_attr_arr as $v){
                $val = explode('|',$v);
                $spec_list[$val[0]]['list'][] = ["name"=>$val[1]];
            }

            $spec_list = array_merge($spec_list);

        }
        $goods_info['spec_list'] = $spec_list;

        // 商品图片显示
        $goods_info['goods_images'] = explode(',',$goods_info['goods_images']);
        $goods_images = array_diff($goods_info['goods_images'],[$goods_info['goods_master_image']]); // 用差集取出非相同的图片
        $goods_info['goods_images'] = array_merge([$goods_info['goods_master_image']],$goods_images);
        // $goods_info['goods_images'][0] = $goods_info['goods_master_image'];
        $goods_info['goods_images_thumb'] = get_format_image($goods_info['goods_images']); // 获取缩略图
        $goods_info['goods_images_thumb_200'] = get_format_image($goods_info['goods_images'],200); // 获取缩略图

        return $goods_info;
    }

    /**
     * 获取推荐置顶商品列表，根据商家ID
     * @param $id
     * @param int $page
     * @return mixed
     */
    public function getGoodsListByIdOrIsTop($id,$page=8){
        $list = $this->where($this->params)->where('user_id',$id)->orderBy('edit_time','desc')->with('spec_once')->where('is_top',1)->take($page)->get()->toArray();
        // 是否有规格，有规格则取规格价格
        foreach($list as $k=>$v){
            if(!empty($v['spec_once'])){
                $v['goods_price'] = $v['spec_once']['goods_price'];
                $v['goods_market_price'] = $v['spec_once']['goods_market_price'];
            }
            $v['image'] = get_format_image($v['goods_master_image'],200);
            $list[$k] = $v;
        }
        return $list;
    }

    /**
     * 获取团购产品
     * @param int $nums
     * @return mixed
     */
    public function getGroupToHome($nums=6)
    {
        $this->params['is_groupbuy'] = 1;
        return self::where($this->params)
            ->offset(0)->limit($nums)->orderByRaw('is_top desc ,edit_time desc')->get()->toArray();;
    }

    /**
     * 正常商品
     * @param $query
     * @return mixed
     */
    public function scopeNormal($query)
    {
        return $query->where($this->params);
    }

    /**
     * 获取首页产品
     * @param $page_index
     * @param int $page_size
     * @return mixed
     */
    public function getGoodsToHome($page_index, $page_size=10)
    {
        $current = ($page_index-1) * $page_size;
        return self::normal()->offset($current)->limit($page_size)->get();
    }

    public function getGoodsCountToHome()
    {
        return self::normal()->count();
    }

}
