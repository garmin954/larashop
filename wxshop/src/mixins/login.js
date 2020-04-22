import Stroge from '@/common/storage'

export default {
    data: {
        code:'',
        isLogin:false,
    },
    methods: {
        // 获取code 并保存
        async getCode(){
            let self = this;
            let code = Stroge.get('wx_code');
            if (!code){
                 let code = await this.refreshCode();
                    Stroge.set('wx_code', code, 3600*4);
                    self.code = code;
                    return code;
            } else{
                self.code = code;
                return self.code;
            }
        },
        refreshCode(){
            let promise = new Promise((resolve, reject)=>{
                wx.login({
                    success (res) {
                        if (res.code) {
                            // 存储起来
                            resolve(res.code);
                            return res.code;
                        } else {
                            reject();
                            return false;
                        }
                    },
                    fail(error) {
                        reject(error);
                    }
                })
            })
            return promise;
        },
        // 删除code
        destroyCode(){
            Stroge.destory('wx_code');
        },
        // 是否登录
        isLoginAction() {
           if ( Stroge.get('token')){
               this.isLogin = true
           }else {
               this.isLogin = false
           }
        },


    },
    created () {
        console.log('code in mixin');
        this.isLoginAction();
        this.getCode()
    }
}
