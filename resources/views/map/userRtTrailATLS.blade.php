<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1,user-scalable=no"/>
    <title>奥特莱斯地图 </title>

    <!-- 菜单开始 -->
    <link rel="stylesheet" type="text/css" href="/css/menu/style.css"/>
    <!-- 菜单结束 -->

    <!-- 提示框开始 -->
    <link href="https://cdn.bootcss.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- 提示框结束 -->

    <!-- 地图开始 -->
    <link rel="stylesheet" type="text/css" href="Ips_api_javascript/dijit/themes/tundra/tundra.css"/>
    <link rel="stylesheet" type="text/css" href="Ips_api_javascript/esri/css/esri.css"/>
    <link rel="stylesheet" type="text/css" href="Ips_api_javascript/fonts/font-awesome-4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="Ips_api_javascript/Ips/css/widget.css"/>
    <!-- 地图结束 -->
    {{--拖动框--}}
    <link rel="stylesheet" type="text/css" href="/css/box.css">

    <style>
        html, body, .map {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background: white;
        }
    </style>

    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>

    <!-- 提示框开始 -->
    <script src="/js/notify/bootstrap.min.js"></script>
    <script src="/js/notify/hullabaloo.js"></script>
    <!-- 提示框结束 -->

    <!-- 331地图 -->
    <script type="text/javascript" src="Ips_api_javascript/init.js"></script>
    <!-- 331地图 -->

    <!-- tools -->
    <script type="text/javascript" src="/js/tools.js"></script>
    <!-- tools-->
    {{--拖动框--}}
    <script type="text/javascript" src="js/box.js"></script>

</head>

<body class="tundra">
<style>
    .menu-btn {
        position: fixed;top:30px;right: 16%;font-size: 18px;
    }
</style>

{{--操作导航条--}}
<style type="text/css">
    *{
        margin: 0;
        padding: 0;
        font-family: "微软雅黑";
    }

    ul{
        list-style: none;
        background: #777;
    }

    .nav2{
        padding: 0;
    }

    .nav3{
        padding: 0;
    }


    .nav1{
        margin: 100px 0 0 100px;
        padding: 0;
    }

    /*设置a标签的属性  区块，宽高，下划线删除，改变默认颜色*/
    .nav1 a{
        display: block;
        width: 100px;
        height: 36px;
        line-height: 36px;
        text-decoration: none;
        color: #fff;
        text-align: center;
    }

    .nav1 a:hover{
        background: #ffaaaa;
        color: #000;
        transition: all 1s;
    }


    .nav1 li{
        position: relative;
    }

    li{
        text-align: center;
    }

    .nav1{
        width: 110px;
    }

    .nav1 a{
        width: 110px;
    }
    /*悬浮不同的nav中的li 设置不同颜色*/
    .li1:hover{
        background: #999;
    }

    .li2:hover{
        background: #bbb;
    }

    .li3:hover{
        background: #aaa;
    }
    /*	设置绝对定位 从nav1的ul开始，相对定位是nav中的li，也就是ul的父级元素
        因为nav1的宽度是110px;所以想让nav1下的ul紧贴nav1，设置left110-px,最好是 110px;
        并且设置子元素隐藏
    */

    .nav1 ul{
        position: absolute;
        left: 109px;
        top:0;
        display: none;
    }

    /*就是当鼠标悬浮在li1 上面的时候，让他子元素中的 nav2 显示可见  当鼠标悬浮在nv2中的li2 上面的时候，则让他子元素中的 nav3 显示可见*/
    .li1:hover .nav2{
        background: #999;
        display: block;
    }

    .li2:hover .nav3{
        background: #bbb;
        display: block;
    }

    /*单独设置每个nav 下的a 的宽度，以及nav 的绝对定位，*/
    .haizei .li2>a{
        width:110px;
    }

    .haizei .li3>a{
        width:120px;
    }

    .quanyecha .li2>a{
        width: 130px;
    }

    .quanyecha .nav3{
        left: 130px;
    }

    .quanyecha .li3>a{
        width: 140px;
    }

    .huoying .li2>a{
        width: 140px;
    }

    .huoying .li3>a{
        width: 150px;
    }

    .huoying .nav3{
        left: 140px;
    }


    .shumabaobei .li2>a{
        width: 150px;
    }

    .shumabaobei .nav3{
        left: 150px;
    }

    .shumabaobei .li3>a{
        width: 160px;
    }

    .sishen .li2>a{
        width: 120px;
    }

    .sishen .nav3{
        left: 120px;
    }

    .sishen .li3>a{
        width: 120px;
    }

</style>
<div  style="z-index: 5; position: fixed ;right:16%;top: 9%">
    <ul class="nav1" style="margin-top:0">
        <li class="li1 caozuo"><a href="">操作</a>
            <ul class="nav2">
                <li class="li2"><a href="{{ url('normalMapATLS')}}">返回用户分布</a>
                </li>
                <li class="li2" ><a href="#" id="showClean">清除轨迹</a>
                </li>
                <li class="li2" ><a href="#" id="showGetCoo">获取坐标</a>
                </li>
            </ul>
        </li>
    </ul>
</div>
{{--操作导航条--}}

{{--拖动框--}}
<div class="box">
    <div class="title">定位信息展示（仅供静态测试时使用）</div>
    <div class="con">
        <p>用户信息:<span id="user-msg"></span> </p>
        <p>用户坐标:<span id="current-location"></span> </p>
        <p>已知坐标:<span id="refer-location"></span> </p>
        <p>定位偏差:<span id="offset-location"></span> </p>
    </div>

</div>
{{--拖动框--}}
<div class="row" style="height: 100%">

    <div id="map_atls_1" class="col-md-6">
        <h5 style="position: absolute; left: 50%;top: 5%;z-index: 10">一层</h5>
    </div>

    <div id="map_atls_2" class="col-md-6">
        <h5 style="position: absolute; left: 50%;top: 5%;z-index: 10">二层</h5>
    </div>

</div>

</div>
</body>

<script>

    //331地图
    // 初始化全局参数
    var HTHT_SERVER_IP = "121.28.103.199:9078"; //航天宏图服务器地址
    var HTHT_TYPE_LOGIN_SCUUESS = 102 //航天宏图消息类型:登录成功
    var HTHT_TYPE_RECEIVE_MSG = 1; //航天宏图消息类型:收到消息
    var HELLO_STR = "系统初始化成功！"; //初始化欢迎语句
    var ERR_MSG = "您正处于危险区域！"//危险区域发送的信息

    var INTERVAL_TIME = 1; //数据刷新间隔时间
    var POINTSIZE = 22;    //默认图片大小为24*24
    var POINTLAT = 38.0;
    var POINTLNG = 114.0;

    /**
     * 拖动框
     */
    $(document).ready(function () {
        $(".box").bg_move({
            move: '.title',
            closed: '.close',
            size: 6
        });
    });
    //websocket 连接对象
    var conn;
    var map1;
    var map2;

    //地图
    require([
        "Ips/map",
        "esri/geometry/Extent",
        "Ips/widget/IpsMeasure",
        "Ips/layers/DynamicMapServiceLayer",
        "Ips/layers/FeatureLayer",
        "Ips/layers/GraphicsLayer",
        "esri/graphic",
        "esri/SpatialReference",
        "esri/geometry/Point",
        "esri/geometry/Polyline",
        "esri/geometry/Polygon",
        "esri/InfoTemplate",
        "esri/symbols/SimpleMarkerSymbol",
        "esri/symbols/SimpleLineSymbol",
        "esri/symbols/SimpleFillSymbol",
        "esri/symbols/PictureMarkerSymbol",
        "esri/symbols/TextSymbol",
        "dojo/colors",
        "dojo/on",
        "dojo/dom",
        "dojo/domReady!"
    ], function (Map, Extent,IpsMeasure,DynamicMapServiceLayer, FeatureLayer, GraphicsLayer, Graphic, SpatialReference, Point, Polyline, Polygon, InfoTemplate, SimpleMarkerSymbol, SimpleLineSymbol,
                 SimpleFillSymbol, PictureMarkerSymbol, TextSymbol, Color, on, dom) {


        //-----------------------------一层-------------------------------------
        map1 = new Map("map_atls_1", {
            center: new Point(534467,4202820, new SpatialReference({ wkid: 4547})),
            logo: false
        });

        //初始化F1楼层平面图
        var f1 = new DynamicMapServiceLayer("http://121.28.103.199:5567/arcgis/rest/services/outlets/outlets1f/MapServer");
        // var f1 = new DynamicMapServiceLayer("http://121.28.103.199:5567/arcgis/rest/services/outlets/outlets2f/MapServer");
        map1.addLayer(f1);

        //初始化pointLayer 用户数据点图层
        var pointLayerF1 = new GraphicsLayer();

        map1.addLayer(pointLayerF1);


        //-----------------------------二层-------------------------------------
        map2 = new Map("map_atls_2", {
            logo: false
        });

        //初始化F2楼层平面图
        var f2 = new DynamicMapServiceLayer("http://121.28.103.199:5567/arcgis/rest/services/outlets/outlets2f/MapServer");
        map2.addLayer(f2);

        //初始化pointLayer 用户数据点图层
        var pointLayerF2 = new GraphicsLayer();

        map2.addLayer(pointLayerF2);


        /**
         * 更新位置信息小部件
         */
        function updateLocationBox(username,curr_lng,curr_lat,refer_lng,refer_lat,floor,location_method){
            if (location_method==0){location_method='指纹定位'}
            if (location_method==1){location_method='混合定位'}
            if (location_method==2){location_method='视觉定位'}
            var currlat = (curr_lat+ "").substring(0,11);
            var currlng = (curr_lng+ "").substring(0,12);
            var referlat = (refer_lat+ "").substring(0,11);
            var referlng = (refer_lng+ "").substring(0,12);
            var d_lat = ((curr_lat-refer_lat)+ "");
            var d_lng = ((curr_lng-refer_lng)+ "");
            var index_lat=d_lat.lastIndexOf(".");
            var index_lng=d_lng.lastIndexOf(".");
            d_lat = d_lat.substring(0,index_lat+4);
            d_lng = d_lng.substring(0,index_lng+4);
            $('#user-msg').html(username+", "+floor+"楼, "+location_method);
            $('#current-location').html("("+currlat+","+currlng+")");
            $('#refer-location').html("("+referlat+","+referlng+")");
            $('#offset-location').html("("+d_lat+","+d_lng+")m");
        }
        on(dom.byId("showClean"),"click",function(){
            pointLayerF1.clear();
            pointLayerF2.clear();
        })


        on(dom.byId("showGetCoo"),"click",function () {
            var click1,click2;
            click1= on(map1,"click",function (evt) {
                var point=evt.mapPoint;
                POINTLAT = point.y;
                POINTLNG = point.x;
                console.log(point);
                click1.remove();
                click2.remove();
            });
            click2= on(map2,"click",function (evt) {
                var point=evt.mapPoint;
                POINTLAT = point.y;
                POINTLNG = point.x;
                click1.remove();
                click2.remove();
            });
        })

        /**
         * 添加点图标
         * */
        function addUserPoint(id,uid, lng, lat, name, phone,floor,status) {
            //定义点的几何体
            //38.2477770 114.3489115
            console.log(lat);
            console.log(lng);
            console.log(status);
            var picpoint = new Point(lng,lat, new SpatialReference({ wkid: 4547}));
            // //定义点的图片符号
            var img_uri="/Ips_api_javascript/Ips/image/marker.png";

            var picSymbol = new PictureMarkerSymbol(img_uri,POINTSIZE,POINTSIZE);
            //定义点的图片符号
            var attr = {"name": name, "phone": phone};
            //信息模板
            var infoTemplate = new InfoTemplate();
            infoTemplate.setTitle('用户');
            infoTemplate.setContent(
                "<b>名称:</b><span>${name}</span><br>"
                + "<b>时间:</b><span>${time}</span><br>"
            );
            var picgr = new Graphic(picpoint, picSymbol, attr, infoTemplate);
            if (floor == 1){
                pointLayerF1.add(picgr);
                map1.addLayer(pointLayerF1);
            }
            if (floor == 2){
                pointLayerF2.add(picgr);
                map2.addLayer(pointLayerF2);
            }
        }
        /**
         * 从数据库读取用户列表和用户最新坐标并更新界面
         */
        function getDataAndRefresh() {
            // 从云端读取数据
            $.get("/api/apiGetAllUserNewLocationList",
                {},
                function (dat, status) {
                    // console.log(dat);
                    if (dat.status == 0) {
                        // 删除数据
                        // pointLayerF1.clear();
                        // pointLayerF2.clear();
                        // pointLayerF3.clear();
                        //重绘
                        pointLayerF1.redraw();
                        pointLayerF2.redraw();
                        // 添加人
                        //注销掉因为先单用户测试
                        for (var i in dat.data) {
                            // console.log(dat);
                            console.log("{{$userPositionLists[0]->uid}}");
                            if (dat.data[i].uid != "{{$userPositionLists[0]->uid}}") {
                                continue
                            }

                            addUserPoint(
                                dat.data[i].id,
                                dat.data[i].uid,
                                dat.data[i].y,
                                dat.data[i].x,
                                dat.data[i].name,
                                dat.data[i].phone,
                                dat.data[i].floor,
                                i
                            );

                            updateLocationBox(
                                dat.data[i].name,
                                dat.data[i].y,
                                dat.data[i].x,
                                POINTLNG,
                                POINTLAT,
                                dat.data[i].floor,
                                dat.data[i].location_method
                            );
                        }
                    } else {
                        console.log('ajax error!');
                    }
                }
            );
        }
        /**
         * 刷新频率
         */
        setInterval(getDataAndRefresh, (INTERVAL_TIME * 1000));

        //显示初始化成功
        notify(HELLO_STR, "sys");

    });




</script>
</html>