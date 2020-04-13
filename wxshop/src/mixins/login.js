export default {
    data: {},
    mothods: {
        // 获取code 并保存
        getCode(){
            wx.login({
                success (res) {
                    if (res.code) {
                        //发起网络请求
                        console.log(res.code);
                        // wx.request({
                        //     url: 'https://test.com/onLogin',
                        //     data: {
                        //         code: res.code
                        //     }
                        // })
                    } else {
                        console.log('登录失败！' + res.errMsg)
                    }
                }
            })
        }
    },
    created () {
        console.log('http in mixin');
    }
}