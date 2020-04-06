"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;

var _config = _interopRequireDefault(require('./../common/config.js'));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

var _default = {
  data: {
    http: '这里是request'
  },
  methods: {
    $get: function $get(url, data) {
      var promise = new Promise(function (resolve, reject) {
        wx.request({
          url: _config["default"].api[url],
          data: data,
          header: {
            'content-type': 'application/json'
          },
          // 或者是  header: {'content-type': 'application/json'},
          success: function success(res) {
            if (res.statusCode === 200) {
              resolve(res);
            } else {
              reject(res);
            }
          },
          fail: function fail(res) {
            reject(res);
          }
        });
      });
      return promise;
    },
    $post: function $post(url, data) {
      var promise = new Promise(function (resolve, reject) {
        wx.request({
          url: _config["default"].api[url],
          data: data,
          header: {
            'content-type': 'applicction/json'
          },
          //  或者是 header{'content-type':'application/x-www-form-urlencoded'},
          success: function success(res) {
            if (res.statusCode === 200) {
              resolve(res);
            } else {
              reject(res);
            }
          },
          fail: function fail(res) {
            reject(res);
          }
        });
      });
      return promise;
    }
  },
  created: function created() {
    console.log('http in mixin');
  }
};
exports["default"] = _default;