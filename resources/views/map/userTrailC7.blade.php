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
        .map-col{position:absolute;left:10px;top:0;z-index:0;width:1200px;height:800px;background-color:#f6f6f6}
    </style>
</head>
<body>
<style>
    .menu-btn {
        position: fixed;top:30px;left:900px;font-size: 18px;
    }
</style>
<style>
    html, body, #map{
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
        <li class="li1 caozuo"><a href="">操作</a>
            <ul class="nav2">
                <li class="li2"><a href="{{ url('normalMapC7')}}">返回用户分布</a>
                </li>
                <li class="li2"><a href="{{ url('userRtTrailC7').'?uid='.$userPositionLists[0]->uid }}">查看实时轨迹</a>
                </li>
                <li class="li2" ><a href="#" id="showbigger">放大点</a>
                </li>
                <li class="li2" ><a href="#" id="showsmaller">缩小点</a>
                </li>
            </ul>
        </li>
    </ul>
</div>
{{--操作导航条--}}
<div class="row">
    <div class="map-col">
        <div id="map"></div>
        <h2 class="menu-btn" style="left: 43%;font-size: 35px;color: #0c0c0c;top: 0">该用户历史轨迹</h2>
    </div>
</div>
<script>
    /**
     * 定义全局变量
     **/
    var POINTSIZE = 24;    //默认图片大小为24*24
    /**
     * 地图需求文件
     */
    require([
        "esri/map",
        "esri/layers/ArcGISDynamicMapServiceLayer",
        "esri/layers/GraphicsLayer",
        "esri/graphic",
        "esri/SpatialReference",
        "esri/InfoTemplate",
        "esri/geometry/Point",
        "esri/symbols/PictureMarkerSymbol",
        "esri/symbols/SimpleMarkerSymbol",
        "esri/symbols/SimpleLineSymbol",
        "dojo/colors",
        "dojo/on",
        "dojo/dom",
        "dojo/domReady!"
    ], function (Map, ArcGISDynamicMapServiceLayer,GraphicsLayer,Graphic,SpatialReference,InfoTemplate,Point,PictureMarkerSymbol,
                 SimpleMarkerSymbol,SimpleLineSymbol,Color,on,dom) {
        /**
         * 定义地图，并设定必要参数
         */
        var map = new Map("map", {
            center: new Point(538264,4212780, new SpatialReference({ wkid: 4547})),
            logo:false

        });
        /**
         * 初始化楼层平面图
         */
        var C7 = new ArcGISDynamicMapServiceLayer("http://121.28.103.199:5567/arcgis/rest/services/C7/NewC7Map/MapServer");
        map.addLayer(C7);
        /**
         * 定义点图层
         */
        var pointLayerC7 = new GraphicsLayer();
        /**
         * 放大缩小点图标按钮的具体实现方法
         */
        on(dom.byId("showbigger"),"click",function () {
            POINTSIZE++;
            pointLayerC7.clear();
            console.log(POINTSIZE);
            addPointToMap();
        });
        on(dom.byId("showsmaller"),"click",function () {
            POINTSIZE--;
            pointLayerC7.clear();
            console.log(POINTSIZE);
            addPointToMap();
        });
        /**
         * 添加点图标
         * */
        function addUserPoint(id,uid, lng, lat,floor,status) {
            var name = "name";
            var phone = "phone";
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
                + "<b>手机号:</b><span>${phone}</span><br>"
            );
            var picgr = new Graphic(picpoint, picSymbol, attr, infoTemplate);
            pointLayerC7.add(picgr);
            map.addLayer(pointLayerC7);
        }
        /**
         * 添加所有用户点到地图方法，分楼层显示并划定了建筑物边界，楼外不显示
         */
        console.log({{$userPositionLists[0]->lng}});
        function addPointToMap() {
               @foreach($userPositionLists as $userPositionList)
            addUserPoint(
                    {{$userPositionList->id}},
                    {{$userPositionList->uid}},
                    {{$userPositionList->y}},
                    {{$userPositionList->x}},
                    {{$userPositionList->floor}},
                    'normal'
            );
            @endforeach
        }
        addPointToMap();

    });
</script>

</body>
</html>