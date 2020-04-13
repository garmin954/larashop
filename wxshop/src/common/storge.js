// 设置
const set = (key, value, time) => {
    let timestamp = Date.parse(new Date())/1000;
    wx.setStorageSync(`${key}_time`, timestamp+time);
    wx.setStorageSync(key, value);

}

// 获取
const get = (key) => {
    let timestamp = Date.parse(new Date())/1000;
    let limit = wx.getStorageSync(`${key}_time`);
    if (timestamp < limit){

        return     wx.getStorageSync(key);
    }else{
        destory(key);
    }

}

// 销毁
const destory = (key) => {
    wx.removeStorageSync(key);
}

module.exports = {
    set : set,
    get : get,
    destory : destory
}

