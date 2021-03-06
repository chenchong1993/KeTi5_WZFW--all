<!DOCTYPE html>
<html style="height: 100%">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>大众位置服务云平台</title>

    <!-- Bootstrap Core CSS -->
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/dist/css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<!--    {{--HUI的图标库--}}-->
    <link rel="stylesheet" type="text/css" href="/Hui-iconfont/1.0.8/iconfont.css" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="/Ips_api_javascript/dijit/themes/tundra/tundra.css"/>
    <link rel="stylesheet" type="text/css" href="/Ips_api_javascript/esri/css/esri.css" />
    <link rel="stylesheet" type="text/css" href="/Ips_api_javascript/fonts/font-awesome-4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="/Ips_api_javascript/Ips/css/widget.css" />
    <script type="text/javascript" src="/Ips_api_javascript/init.js"></script>
    <script src="/vendor/jquery/jquery.min.js"></script>
    <style type="text/css">
        /*.user-msg{position:absolute;left:810px;top:10px;z-index:auto;width:500px;background-color:#f6f6f6}*/
        .map1-col{position:absolute;left:0;top:0;z-index:0;width:50%;height:800px;background-color:#f6f6f6}
        .map2-col{position:absolute;left:50%;top:0;z-index:0;width:50%;height:800px;background-color:#f6f6f6}
        .map3-col{position:absolute;left:0;top:800px ;width:50%;height:800px;margin-bottom: 180px;background-color:#f6f6f6}
    </style>
</head>
<body>
<style>
    .menu-btn {
        position: fixed;top:30px;left:900px;font-size: 18px;
    }
</style>
<style>
    html, body, #map1,#map2,#map3{
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
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
<div class="nav" style="z-index: 5; position: fixed ;right:16%;top: 7%">
    <ul class="nav1" style="margin-top:0">
        <li class="li1 caozuo"><a href="">转到其他地图</a>
            <ul class="nav2">
                <li class="li2"><a href="{{url('normalMap331')}}">331</a>
                </li>
                <li class="li2"><a href="{{url('normalMapC7')}}">C7</a>
                </li>
                <li class="li2"><a href="{{url('normalMapATLS')}}">奥特莱斯</a>
                </li>
            </ul>
        </li>
    </ul>
</div>
{{--操作导航条--}}


<div class="row">
    <div class="map1-col">
        <div id="map1"></div>
        <h2 class="menu-btn" style="left: 23%;font-size: 35px;color: #0c0c0c;top: 0">C7用户位置分布</h2>
    </div>
    <div class="map2-col">
        <div id="map2"></div>
    </div>
    <div class="map3-col">
        <div id="map3"></div>
    </div>
</div>
<script>
    /**
     * 定义全局变量
     **/
    var INTERVAL_TIME = 1; //数据刷新间隔时间
    var POINTSIZE = 24;    //默认图片大小为24*24
    /**
     * 跳转到用户历史轨迹页面
     * */
    function catUserTrail(uid) {
        // console.log("2:",uid+" ");
        var startTime = $('#startTime').val();
        var endTime = $('#endTime').val();
        window.location.href = '/userTrailC7?uid=' + uid + '&startTime=' + startTime +'&endTime=' + endTime;
    }
    /**
     * 跳转到用户实时轨迹页面
     * */
    function catUserRtTrail(uid) {
        window.location.href = '/userRtTrailC7?uid=' + uid;
    }
    /**
     * 导出文件
     * */
    function exportFlie(uid) {

        // console.log("2:",uid+" ");
        var startTime = $('#startTime').val();
        var endTime = $('#endTime').val();

        window.location.href = '/api/fileExport?uid=' + uid + '&startTime=' + startTime +'&endTime=' + endTime;

    }

    /**
     * 地图需求文件
     */
    require([
        "Ips/map",
        "esri/geometry/Extent",
        "Ips/widget/IpsMeasure",
        "Ips/layers/DynamicMapServiceLayer",
        "Ips/layers/FeatureLayer",
        "esri/SpatialReference",
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
    ], function (Map, Extent,IpsMeasure,DynamicMapServiceLayer,FeatureLayer, SpatialReference,GraphicsLayer, Graphic, Point, Polyline, Polygon, InfoTemplate, SimpleMarkerSymbol, SimpleLineSymbol,
                 SimpleFillSymbol, PictureMarkerSymbol, TextSymbol, Color, on, dom) {
        /**
         * 定义地图，并设定必要参数
         */
        var map1 = new Map("map1", {
            logo:false,
            zoom:12,
            center: new Point(538264,4212815, new SpatialReference({ wkid: 4547}))
        });
        var map2 = new Map("map2", {
            logo:false,
            zoom:12,
            center: new Point(538264,4212815, new SpatialReference({ wkid: 4547}))
        });
        var map3 = new Map("map3", {
            center: new Point(538264,4212815, new SpatialReference({ wkid: 4547})),
            logo:false,
            zoom:20
        });


        /**
         * 初始化楼层平面图
         */
        var f1 = new DynamicMapServiceLayer("http://121.28.103.199:5567/arcgis/rest/services/C7/c7floor1/MapServer");
        var f2 = new DynamicMapServiceLayer("http://121.28.103.199:5567/arcgis/rest/services/C7/c7floor2/MapServer");
        var f3 = new DynamicMapServiceLayer("http://121.28.103.199:5567/arcgis/rest/services/C7/c7floor3/MapServer");
        var route1 = new FeatureLayer("http://121.28.103.199:5567/arcgis/rest/services/C7/network1/MapServer");
        var route2 = new FeatureLayer("http://121.28.103.199:5567/arcgis/rest/services/C7/network2/MapServer");
        var route3 = new FeatureLayer("http://121.28.103.199:5567/arcgis/rest/services/C7/network3/MapServer");
        map1.addLayer(f1);
        map2.addLayer(f2);
        map3.addLayer(f3);
        map1.addLayer(route1);
        map2.addLayer(route2);
        map3.addLayer(route3);
        /**
         * 定义点图层
         */
        var pointLayerF1 = new GraphicsLayer();
        var pointLayerF2 = new GraphicsLayer();
        var pointLayerF3= new GraphicsLayer();
        /**
         * 添加点图标
         * */
        function addUserPoint(id,uid, lng, lat, name, phone,floor,status) {
            //定义点的几何体
            //38.2477770 114.3489115
            var picpoint = new Point(lng,lat);
            // //定义点的图片符号
            var picSymbol;
            var img_uri = "Ips_api_javascript/Ips/image/marker.png";

            picSymbol = new PictureMarkerSymbol(img_uri,POINTSIZE,POINTSIZE);
            //定义点的图片符号
            var attr = {"name": name, "phone": phone};
            //信息模板
            var infoTemplate = new InfoTemplate();
            infoTemplate.setTitle('用户');
            infoTemplate.setContent(
                "<b>名称:</b><span>${name}</span><br>"
                + "<b>手机号:</b><span>${phone}</span><br>"
                + "<b>起始时间：</b><input type='text' name='startTime'class='' id='startTime' placeholder='2018-01-01 00:00:00'><br>"
                + "<b>终止时间：</b><input type='text' name='endTime'class='' id='endTime' placeholder='2018-01-01 23:59:59'><br>"
                + "<button class='' onclick=catUserTrail(" + "'" + uid + "'" + ") > 查看该用户历史轨迹</button>"
                + "<button class='' onclick=catUserRtTrail(" + "'" + uid + "'" + ") > 查看该用户实时轨迹</button>"
                + "<button class='' onclick=exportFlie(" + "'" + uid + "'" + ") > 导出该时段数据</button>"
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
            if (floor == 3){
                pointLayerF3.add(picgr);
                map3.addLayer(pointLayerF3);
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
                     console.log(dat);
                     if (dat.status == 0) {
                         // 删除数据
                         pointLayerF1.clear();
                         pointLayerF2.clear();
                         pointLayerF3.clear();
                         //重绘
                         pointLayerF1.redraw();
                         pointLayerF2.redraw();
                         pointLayerF3.redraw();
                         // 添加人
                         //注销掉因为先单用户测试
                         for (var i in dat.data) {
                             console.log(dat);
                             // for (var i=5; i<10; i++) {
                             // console.log(dat.data[i].username);
                             if (dat.data[i].floor==3){
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
                             }
                             else {
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
                             }
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
        setInterval(getDataAndRefresh, (INTERVAL_TIME * 1000))
    });
</script>

</body>
</html>