<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1,user-scalable=no"/>
    <title>电子围栏示范系统</title>

    <!-- 菜单开始 -->
    <link rel="stylesheet" type="text/css" href="/css/menu/style.css"/>
    <!-- 菜单结束 -->
    <!-- 提示框开始 -->
    <link href="https://cdn.bootcss.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- 提示框结束 -->

    <!-- 地图开始 -->
    <link rel="stylesheet" type="text/css" href="/Ips_api_javascript/dijit/themes/tundra/tundra.css"/>
    <link rel="stylesheet" type="text/css" href="/Ips_api_javascript/esri/css/esri.css"/>
    <link rel="stylesheet" type="text/css" href="/Ips_api_javascript/fonts/font-awesome-4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="/Ips_api_javascript/Ips/css/widget.css"/>
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

<div class="row" style="height: 100%">

    <div id="map" class="col-md-12"></div>

</div>
</body>

<script>

    // 初始化全局参数
    var INTERVAL_TIME = 1; //数据刷新间隔时间
    var HELLO_STR = "系统初始化成功！"; //初始化欢迎语句
    var ERR_MSG = "电子围栏示范用户正处于危险区域！";//危险区域发送的信息
    var POINTSIZE = 22;    //默认图片大小为24*24
    var map;


    //地图
    require([
        "esri/map",
        "esri/geometry/Extent",
        "Ips/layers/DynamicMapServiceLayer",
        "Ips/layers/FeatureLayer",
        "Ips/layers/GraphicsLayer",
        "esri/graphic",
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
    ], function (Map, Extent,DynamicMapServiceLayer, FeatureLayer, GraphicsLayer, Graphic, Point, Polyline, Polygon, InfoTemplate, SimpleMarkerSymbol, SimpleLineSymbol,
                 SimpleFillSymbol, PictureMarkerSymbol, TextSymbol, Color, on, dom) {
        var initialExtent = new Extent({
            "spatialReference": { "wkid": 4326 }
        });

        var map = new Map("map", {
            basemap:"osm",
            center:[116.29656182, 40.04275177],
            zoom:15,
            extent:initialExtent,
            logo:false
        });
        //初始化pointLayer 用户数据点图层
        var pointLayer = new GraphicsLayer();
        map.addLayer(pointLayer);

        //初始化GraphicsLayer,设定围栏区域
        var layer = new GraphicsLayer();
        var lng1 = 116.29656182;
        var lat1 = 40.04275177;
        var lng2 = 116.29946182;
        var lat2 = 40.04576177;
        var arr = [[lng1,lat1],[lng1,lat2],[lng2,lat2],[lng2,lat1],[lng1,lat1]];

        var polygon= new Polygon(arr);
        //定义面的符号
        var fill= new SimpleFillSymbol(SimpleFillSymbol.STYLE_HORIZONTAL,
            new SimpleLineSymbol(SimpleLineSymbol.STYLE_DASHDOT,new Color([255,50,0]), 2),
            new Color([0,50,200,0.25]));
        var fillgr=new Graphic(polygon,fill);
        layer.add(fillgr);
        map.addLayer(layer);

        /**
         * 添加点图标
         * */
        function addUserPoint(id,uid, lng, lat, name, phone,floor,status) {
            //定义点的几何体
            //38.2477770 114.3489115
            console.log(lat);
            console.log(lng);
            console.log(status);
            var picpoint = new Point(lng,lat);
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
            pointLayer.add(picgr);
            map.addLayer(pointLayer);
            if (status==1){
                notify(ERR_MSG, "sys");
            }
        }
        /**
         * 从数据库读取用户列表和用户最新坐标并更新界面
         */
        function getDataAndRefresh() {
            // 从云端读取数据
            $.get("/api/apiGetLocationList",
                {},
                function (dat, status) {
                    // console.log(dat);
                    if (dat.status == 0) {
                        // 删除数据
                        pointLayer.clear();
                        // pointLayerF2.clear();
                        // pointLayerF3.clear();
                        //重绘
                        pointLayer.redraw();
                        // 添加人
                        //注销掉因为先单用户测试
                        for (var i in dat.data) {
                            // console.log(dat);
                            if (dat.data[i].uid != "32770901179105290") {
                                continue
                            }

                            addUserPoint(
                                dat.data[i].id,
                                "32770901179105290",
                                dat.data[i].lng,
                                dat.data[i].lat,
                                "电子围栏示范用户",
                                "150000000",
                                "0",
                                dat.data[i].status
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