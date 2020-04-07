import Config from '@/common/config'
export default {
  data: {
    http: '这里是request'
  },
  methods: {
    $get(url, data) {
      var promise = new Promise((resolve, reject) => {
        wx.request({
          url: Config.api[url],
          data: data,
          header: {'content-type': 'application/json'}, // 或者是  header: {'content-type': 'application/json'},
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
          header: {'content-type': 'applicction/json'}, //  或者是 header{'content-type':'application/x-www-form-urlencoded'},
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
