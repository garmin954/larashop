import eventHub from "./eventHub";
import Stroage from '@/common/storage'
const needLogin = ['cart/index'];

export default {

    // 跳转页面
    goToNavigate(url, type=''){

        if (needLogin.includes(url.replace('/pages/', ''))){
            if (!this.isLogin())return;
        }

        if (type == 'tabbar'){
            wx.switchTab({
                url: url
            })
        }else{
            wx.navigateTo({
                url: url
            })
        }
    },
    // 判断是否登录
    isLogin() {
        if (Stroage.get('token')){
            return true;
        }else {
            wx.showToast({
                title: '没有登录',
                icon: 'none',
                duration: 2000
            })
            setTimeout(r=>{
                this.goToLogin();

            }, 1000)

            return false;
        }
    },
    // 去登录界面
    goToLogin(){
        this.goToNavigate('/pages/other/login')
    },
    logs(){
        let [...params] = arguments;
        if (params.length == 0){
            console.info('------标题：'+params[0].toString());
            console.log(params[0]);
        }else{
            console.info('------标题：'+params.toString());
            console.log(params);
        }

    },
    toastInfo(title='操作成功！', icon='none', duration=2000){
        wx.showToast({
            title: title,
            icon: icon,
            duration: duration
        })
    }
}