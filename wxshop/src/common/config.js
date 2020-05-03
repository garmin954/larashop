// const base_url = "https://api.shop.org";
const base_url = "http://api.jietuma.com";


let api = {
    get_banner : `index/get_banner_list`, // 首页banner
    get_group_to_home : `goods/get_group_to_home`, // 首页团购
    get_goods_list : `goods/get_goods_list`, // 商品列表
    get_goods_class_list : `goods/get_goods_class`, // 商品分类
    login : `auth/login`, // 登录
    search_goods_list : `goods/search_goods_list`, // 搜索商品
    get_cart_list : `cart/get_cart_list`, // 获取购物车
    add_cart_goods : `cart/add_cart_goods`, // 添加到购物车

}

 Object.keys(api).forEach(item=>{
   api[item] = `${base_url}/api/weixin/${api[item]}`
 })

module.exports = {
  api : api,
  base_url : base_url
}
