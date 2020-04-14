import Stroge from '@/common/storge'

export default {
    data: {
        code:'',
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
        isLogin() {
            
        },
        destoryCode(){
            Stroge.destory('wx_code');
        }
    },
    created () {
        console.log('code in mixin');
        this.getCode()
    }
}
