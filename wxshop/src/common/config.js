const base_url = "https://api.shop.org";

let api = {
  get_banner : `index/get_banner_list`,
}

 Object.keys(api).forEach(item=>{
   api[item] = `${base_url}/api/weixin/${api[item]}`
 })

module.exports = {
  api : api,
  base_url : base_url
}
