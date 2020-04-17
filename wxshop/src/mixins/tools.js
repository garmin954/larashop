export default {
    data: {
        hideCount: true, //角标初始是隐藏的
        count: 0, //角标数
        hide_good_box: true,
        feiBox: "",
        imgUrls:{}
    },
    methods: {
        screenSize (){
            var self = this;
            wx.getSystemInfo({
                success: function (res) {
                    //可视窗口宽度
                    var ww = res.windowWidth;
                    //可视窗口高度
                    var hh = res.windowHeight;
                    self.$options.globalData.ww = ww;
                    self.$options.globalData.hh = hh;
                }
            })
        },

        bezier(points, times){
            // 0、以3个控制点为例，点A,B,C,AB上设置点D,BC上设置点E,DE连线上设置点F,则最终的贝塞尔曲线是点F的坐标轨迹。
            // 1、计算相邻控制点间距。
            // 2、根据完成时间,计算每次执行时D在AB方向上移动的距离，E在BC方向上移动的距离。
            // 3、时间每递增100ms，则D,E在指定方向上发生位移, F在DE上的位移则可通过AD/AB = DF/DE得出。
            // 4、根据DE的正余弦值和DE的值计算出F的坐标。
            // 邻控制AB点间距
            var bezier_points = [];
            var points_D = [];
            var points_E = [];
            const DIST_AB = Math.sqrt(Math.pow(points[1]['x'] - points[0]['x'], 2) + Math.pow(points[1]['y'] - points[0]['y'], 2));
            // 邻控制BC点间距
            const DIST_BC = Math.sqrt(Math.pow(points[2]['x'] - points[1]['x'], 2) + Math.pow(points[2]['y'] - points[1]['y'], 2));
            // D每次在AB方向上移动的距离
            const EACH_MOVE_AD = DIST_AB / times;
            // E每次在BC方向上移动的距离
            const EACH_MOVE_BE = DIST_BC / times;
            // 点AB的正切
            const TAN_AB = (points[1]['y'] - points[0]['y']) / (points[1]['x'] - points[0]['x']);
            // 点BC的正切
            const TAN_BC = (points[2]['y'] - points[1]['y']) / (points[2]['x'] - points[1]['x']);
            // 点AB的弧度值
            const RADIUS_AB = Math.atan(TAN_AB);
            // 点BC的弧度值
            const RADIUS_BC = Math.atan(TAN_BC);
            // 每次执行
            for (var i = 1; i <= times; i++) {
                // AD的距离
                var dist_AD = EACH_MOVE_AD * i;
                // BE的距离
                var dist_BE = EACH_MOVE_BE * i;
                // D点的坐标
                var point_D = {};
                point_D['x'] = dist_AD * Math.cos(RADIUS_AB) + points[0]['x'];
                point_D['y'] = dist_AD * Math.sin(RADIUS_AB) + points[0]['y'];
                points_D.push(point_D);
                // E点的坐标
                var point_E = {};
                point_E['x'] = dist_BE * Math.cos(RADIUS_BC) + points[1]['x'];
                point_E['y'] = dist_BE * Math.sin(RADIUS_BC) + points[1]['y'];
                points_E.push(point_E);
                // 此时线段DE的正切值
                var tan_DE = (point_E['y'] - point_D['y']) / (point_E['x'] - point_D['x']);
                // tan_DE的弧度值
                var radius_DE = Math.atan(tan_DE);
                // 地市DE的间距
                var dist_DE = Math.sqrt(Math.pow((point_E['x'] - point_D['x']), 2) + Math.pow((point_E['y'] - point_D['y']), 2));
                // 此时DF的距离
                var dist_DF = (dist_AD / DIST_AB) * dist_DE;
                // 此时DF点的坐标
                var point_F = {};
                point_F['x'] = dist_DF * Math.cos(radius_DE) + point_D['x'];
                point_F['y'] = dist_DF * Math.sin(radius_DE) + point_D['y'];
                bezier_points.push(point_F);
            }
            return {
                'bezier_points': bezier_points
            };
        },
        //点击商品触发的事件
        touchOnGoods(e) {
            //把点击每一项的对应的商品图保存下来，就是飞向购物车的图片
            this.setData({
                feiBox: this.data.imgUrls[e.currentTarget.dataset.idx]
            })
            // 如果good_box正在运动
            if (!this.data.hide_good_box) return;
            //当前点击位置的x，y坐标
            this.finger = {};
            var topPoint = {};
            this.finger['x'] = e.touches["0"].clientX;
            this.finger['y'] = e.touches["0"].clientY - 20;
            if (this.finger['y'] < this.busPos['y']) {
                topPoint['y'] = this.finger['y'] - 150;
            } else {
                topPoint['y'] = this.busPos['y'] - 150;
            }

            if (this.finger['x'] < this.busPos['x']) {
                topPoint['x'] = Math.abs(this.finger['x'] - this.busPos['x']) / 2 + this.finger['x'];
            } else {
                topPoint['x'] = this.busPos['x'];
                this.finger['x'] = this.busPos['x']
            }


            this.linePos = this.$options.bezier([this.finger, topPoint, this.busPos], 30);
            this.startAnimation();

        },
        //开始动画
        startAnimation() {
            var index = 0,
                that = this,
                bezier_points = that.linePos['bezier_points'];
            this.setData({
                hide_good_box: false,
                bus_x: that.finger['x'],
                bus_y: that.finger['y']
            })
            this.timer = setInterval(function() {
                index++;
                that.setData({
                    bus_x: bezier_points[index]['x'],
                    bus_y: bezier_points[index]['y']
                })
                if (index >= 28) {
                    clearInterval(that.timer);
                    that.setData({
                        hide_good_box: true,
                        hideCount: false,
                        count: that.data.count += 1
                    })
                }
            }, 33);
        }


    },
    globalData:{

    },
    created () {
        //可视窗口x,y坐标
        console.log('this.$optionsthis.$optionsthis.$optionsthis.$options');
        console.log(this);
        this.busPos = {};
        this.busPos['x'] = this.$options.globalData.ww * .85;
        this.busPos['y'] = this.$options.globalData.hh * .85;
    }
}
