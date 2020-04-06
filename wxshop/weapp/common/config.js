"use strict";

var base_url = "https://api.shop.org";
var api = {
  get_banner: "index/get_banner_list"
};
Object.keys(api).forEach(function (item) {
  api[item] = "".concat(base_url, "/api/weixin/").concat(api[item]);
});
module.exports = {
  api: api,
  base_url: base_url
};