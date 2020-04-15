import Config from '@/common/config'
import Storge from '@/common/storge'

// 获取localStorage 内token
const token = Storge.get('token');
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
      let promise = new Promise((resolve, reject) => {
        wx.request({
          url: Config.api[url],
          data: data,
          method: 'GET',
          header: {'content-type': 'application/json', 'Authorization': Authorizations}, // 或者是  header: {'content-type': 'application/json'},
          success: res => {
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

    $post(url, data) {
      let promise = new Promise((resolve, reject) => {
        wx.request({
          url: Config.api[url],
          data: data,
          method: 'POST',
          header: {'content-type': 'applicction/json', 'Authorization': Authorizations}, //  或者是 header{'content-type':'application/x-www-form-urlencoded'},
          success: res => {
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
