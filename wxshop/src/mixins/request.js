import Config from '@/common/config'
import Storage from '@/common/storage'
import Tools from '@/common/tools'

// 获取localStorage 内token
const token = Storage.get('token');
var Authorizations = '';
if(token){
   Authorizations = 'Bearer '+token;
  // config.headers['Authorization'] = 'Bearer '+token; // 如果token 存在则携带token访问
}
export default {
  data: {
    http: '这里是request'
  },
  methods: {
    $get(url, data) {
      return this.requestCore(url, data, "GET");
    },

    $post(url, data) {
      return this.requestCore(url, data, "POST");
    },

    // 请求核心
    requestCore(url, data, method){
      let self = this;
      let promise = new Promise((resolve, reject) => {
        wx.request({
          url: Config.api[url],
          data: data,
          method: method,
          header: {'content-type': 'applicction/json', 'Authorization': Authorizations}, //  或者是 header{'content-type':'application/x-www-form-urlencoded'},
          success: res => {
            if (res.statusCode === 401){
              Tools.goToLogin();
            }
            if (res.statusCode === 200) {
              resolve(res)
            } else {
              reject(res)
            }
          },
          fail: res => {
            reject(res)
          }
        })
      })
      return promise
    },
  },
  created () {
    console.log('http in mixin');
  }
}
