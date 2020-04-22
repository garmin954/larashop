// const base_url = "https://api.shop.org";
const base_url = "https://api.jietuma.com";


let api = {
    get_banner : `index/get_banner_list`, // 首页banner
    get_group_to_home : `goods/get_group_to_home`, // 首页团购
    get_goods_list : `goods/get_goods_list`, // 商品列表
    get_goods_class_list : `goods/get_goods_class`, // 商品分类
    login : `auth/login`, // 登录
    search_goods_list : `goods/search_goods_list`, // 搜索商品

}

 Object.keys(api).forEach(item=>{
   api[item] = `${base_url}/api/weixin/${api[item]}`
 })

module.exports = {
  api : api,
  base_url : base_url
}
