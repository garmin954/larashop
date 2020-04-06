"use strict";

var _core = _interopRequireDefault(require('./../../vendor.js')(1));

var _toast = _interopRequireDefault(require('./../../vendor/vant/toast/toast.js'));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

var self;

_core["default"].page({
  config: {
    navigationBarTitleText: "test"
  },
  hooks: {
    // Page 级别 hook, 只对当前 Page 的 setData 生效。
    "before-setData": function beforeSetData(dirty) {
      if (Math.random() < 0.2) {
        console.log("setData canceled");
        return false; // Cancel setData
      }

      dirty.time = +new Date();
      return dirty;
    }
  },
  data: {
    value: "搜索",
    auto: true,
    time: 2000,
    bannerList: [{
      adv_image: '/static/images/test.jpg'
    }],
    groupBuy: [{
      adv_image: '/static/images/test.jpg'
    }],
    item: 3,
    vertical: true
  },
  computed: {},
  methods: {
    get_banner: function get_banner() {
      console.log(self.$app); // console.log(self);

      self.$app.$get('get_banner', {}).then(function (response) {
        console.log(response);

        if (response.data.code > 0) {
          self.bannerList = response.data.data.banner_list;
          self.$apply();
          console.log(self.bannerList);
        }
      })["catch"](function (error) {
        console.log(error);

        _toast["default"].success('失败文案');
      });
    },
    getGroupToHome: function getGroupToHome() {
      self.$app.$get('get_group_to_home', {}).then(function (response) {
        console.log(response);

        if (response.data.code > 0) {
          console.log(self.data);
          self.groupBuy = response.data.data.group_list;
          self.$apply();
        }
      })["catch"](function (error) {
        console.log(error);

        _toast["default"].success('失败文案');
      });
    },
    onChange: function onChange(e) {
      this.value = e.detail;
    },
    onSearch: function onSearch() {
      console.log('sd京拉'); // Toast.success('成功文案');

      _toast["default"].loading({
        mask: true,
        message: '加载中...'
      });
    },
    onClick: function onClick() {
      console.log('京拉'); // Toast.success('成功文案');

      _toast["default"].loading({
        mask: true,
        message: '加载中...'
      });
    },
    loading: function loading() {
      _toast["default"].loading({
        mask: true,
        message: '加载中...',
        duration: 0
      });
    }
  },
  created: function created() {// wx.getUserInfo({
    //   success (res) {
    //     self.userInfo = res.userInfo
    //   }
    // })
  },
  onLoad: function onLoad() {
    self = this;
    self.loading();
    self.get_banner();
    self.getGroupToHome();
  },
  onReady: function onReady() {
    console.log('加载完成');
    setTimeout(function (res) {
      return (0, _toast["default"])('加载完成');
    }, 2000);
  }
}, {info: {"components":{"van-search":{"path":"./../../vendor/vant/search/index"},"van-toast":{"path":"./../../vendor/vant/toast/index"},"van-grid":{"path":"./../../vendor/vant/grid/index"},"i-input":{"path":"./../../vendor/iview/input/index"},"i-card":{"path":"./../../vendor/iview/card/index"},"gm-goods-group":{"path":"./../../components/goods/goods_group"},"van-grid-item":{"path":"./../../vendor/vant/grid-item/index"}},"on":{}}, handlers: {'8-149': {"tap": function proxy () {
    var $event = arguments[arguments.length - 1];
    var _vm=this;
      return (function () {
        _vm.onClick($event);
      })();
    
  }}}, models: {}, refs: undefined });