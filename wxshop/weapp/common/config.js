"use strict";

// const base_url = "https://api.shop.org";
var base_url = "https://api.jietuma.com";
var api = {
  get_banner: "index/get_banner_list",
  get_group_to_home: "goods/get_group_to_home"
};
Object.keys(api).forEach(function (item) {
  api[item] = "".concat(base_url, "/api/weixin/").concat(api[item]);
});
module.exports = {
  api: api,
  base_url: base_url
};