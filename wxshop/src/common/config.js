// const base_url = "https://api.shop.org";
const base_url = "https://api.jietuma.com";


let api = {
    get_banner : `index/get_banner_list`,
    get_group_to_home : `goods/get_group_to_home`,
}

 Object.keys(api).forEach(item=>{
   api[item] = `${base_url}/api/weixin/${api[item]}`
 })

module.exports = {
  api : api,
  base_url : base_url
}
