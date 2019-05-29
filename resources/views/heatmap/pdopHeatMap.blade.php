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
    {{--HUI的图标库--}}
    <link rel="stylesheet" type="text/css" href="/Hui-iconfont/1.0.8/iconfont.css" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="Ips_api_javascript/echarts.js"></script>
    <script src="Ips_api_javascript/echartsExtent.js"></script>
    <link rel="stylesheet" type="text/css" href="/Ips_api_javascript/dijit/themes/tundra/tundra.css"/>
    <link rel="stylesheet" type="text/css" href="/Ips_api_javascript/esri/css/esri.css" />
    <link rel="stylesheet" type="text/css" href="/Ips_api_javascript/fonts/font-awesome-4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="/Ips_api_javascript/Ips/css/widget.css" />
    <script type="text/javascript" src="/Ips_api_javascript/init.js"></script>
    <script src="/vendor/jquery/jquery.min.js"></script>
    {{--修改三张地图尺寸--}}
    <style type="text/css">
        /*.user-msg{position:absolute;left:810px;top:10px;z-index:auto;width:500px;background-color:#f6f6f6}*/
        .map1-col{position:absolute;left:0;top:0;right:0;background-color:#f6f6f6}
        .map2-col{position:absolute;left:0;top:400px;right: 0;background-color:#f6f6f6}
        .map3-col{position:absolute;left:0;top:800px ;right: 60%;background-color:#f6f6f6}
    </style>
</head>
<body>
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
                <li class="li2"><a href="{{url('hdopHeatMap')}}">HDOP</a>
                </li>
                <li class="li2"><a href="{{url('vdopHeatMap')}}">VDOP</a>
                </li>
                <li class="li2"><a href="{{url('pdopHeatMap')}}">PDOP</a>
                </li>
                <li class="li2"><a href="{{url('gdopHeatMap')}}">GDOP</a>
                </li>
            </ul>
        </li>
    </ul>
</div>
{{--操作导航条--}}
<style>
    .menu-btn {
        position: fixed;top:30px;right: 16%;font-size: 18px;
    }
</style>
<div class="row">
    <div class="map1-col">
        <div id="map1"></div>
    </div>
    <div class="map2-col">
        <div id="map2"></div>
        <h2 class="menu-btn" style="left: 43%;font-size: 35px;color: #0c0c0c;top: 10px">PDOP</h2>
    </div>
    <div class="map3-col">
        <div id="map3"></div>
    </div>
</div>
<script>
    var dojoConfig={
        async: true,
        packages: [{
            name: "src",
            location: location.pathname.replace(/\/[^/]+$/, "")+"/src"
        }]
    }
</script>
<script>
    /**
     * 地图需求文件
     */
    require([
        "Ips/map",
        "esri/geometry/Extent",
        "src/Echarts3Layer",
        "Ips/layers/DynamicMapServiceLayer",
        "Ips/layers/FeatureLayer",
        "dojo/on",
        "dojo/dom",
        "dojo/domReady!"
    ], function (Map,Extent,Echarts3Layer,DynamicMapServiceLayer,FeatureLayer,on,dom){
        /**
         * 定义三张地图，并设定必要参数
         */
        var initialExtent = new Extent({
            "xmin": 114.348488, "ymin": 114.348832,
            "xmax": 114.349324, "ymax": 114.348865,
            "spatialReference": { "wkid": 4326 }
        });
        var initialExtentf3 = new Extent({
            "xmin": 114.348488, "ymin": 114.348832,
            "xmax": 114.3487591, "ymax": 114.348865,
            "spatialReference": { "wkid": 4326 }
        });
        /**
         * 定义三张地图，并设定必要参数
         */
        var map1 = new Map("map1", {
            logo:false,
            zoom:21,
            extent:initialExtent,
            center: [114.3489254,38.24772],
        });
        var map2 = new Map("map2", {
            logo:false,
            zoom:21,
            extent:initialExtent,
            center: [114.3489254,38.24772],
        });
        var map3 = new Map("map3", {
            logo:false,
            zoom:21,
            extent:initialExtentf3,
            center: [114.3486414,38.247735],
        });
        /**
         * 初始化楼层平面图
         */
        var f1 = new DynamicMapServiceLayer("http://121.28.103.199:5567/arcgis/rest/services/331/floorone/MapServer");
        var f2 = new DynamicMapServiceLayer("http://121.28.103.199:5567/arcgis/rest/services/331/floortwo/MapServer");
        var f3 = new DynamicMapServiceLayer("http://121.28.103.199:5567/arcgis/rest/services/331/floorthree/MapServer");
        var grid1 = new FeatureLayer("http://121.28.103.199:5567/arcgis/rest/services/331/grid/MapServer/0");
        var grid2 = new FeatureLayer("http://121.28.103.199:5567/arcgis/rest/services/331/grid/MapServer/0");
        var grid3 = new FeatureLayer("http://121.28.103.199:5567/arcgis/rest/services/331/grid/MapServer/0");
        map1.addLayer(f1);
        map2.addLayer(f2);
        map3.addLayer(f3);
        map1.addLayer(grid1);
        map2.addLayer(grid2);
        map3.addLayer(grid3);

        /**
         * 从数据库获取热力图数据
         */
        function getHeatMap(type,floor,point ,map){
            /**
             * 调用获取热力图数据api接口
             */
            $.post("/api/heatMapData",
                {
                    type:type,
                    floor:floor
                },
                function (data) {
                    /**
                     * jQuery.parseJSON(data) 把json字符串转成json对象
                     */
                    var jsonObj = jQuery.parseJSON(data);
                    /**
                     * 循环生成point数组
                     */
                    for (var i in jsonObj){
                        var  newLine = [jsonObj[i][1],jsonObj[i][0],jsonObj[i][2]];
                        point.push(newLine);
                    }
                    try {
                        //初始化echarts图层
                        var overlay = new Echarts3Layer(map, echarts);
                        var chartsContainer = overlay.getEchartsContainer();
                        var myChart = overlay.initECharts(chartsContainer);
                        //热力图配置
                        var option = {
                            title: {
                                text: '',
                                left: 'center',
                                textStyle: {
                                    color: '#0c0c0c'
                                }
                            },
                            visualMap: {
                                min: 1.825,
                                max: 1.85,
                                splitNumber: 5,
                                show: false,
                                inRange: {
                                    // color: ['#d94d07', '#ea4007', '#baa808'].reverse()
                                    color: ['blue', 'blue', 'green', 'yellow', 'red']
                                },
                                textStyle: {
                                    color: '#080808'
                                }
                            },
                            geo: {
                                map: '',
                                show: false,
                                label: {
                                    emphasis: {
                                        show: false
                                    }
                                },
                                left: 0,
                                top: 0,
                                right: 0,
                                bottom: 0,
                                roam: false,
                                itemStyle: {
                                    normal: {
                                        areaColor: '#323c48',
                                        borderColor: '#111'
                                    },
                                    emphasis: {
                                        areaColor: '#2a333d'
                                    }
                                }
                            },
                            series: [{
                                type: 'heatmap', //effectScatter
                                coordinateSystem: 'geo',
                                data: point, //渲染数据【点数组】
                                pointSize: 28,  //点大小
                                blurSize: 80  //模糊大小
                            }]
                        };
                        // 使用刚指定的配置项和数据显示图表。
                        overlay.setOption(option);
                        console.log(point)

                    }
                    catch (e) {
                    }
                }
            );
        }
        var pointF1 = [];
        var pointF2 = [];
        var pointF3 = [];
        getHeatMap('PDOP','1',pointF1,map1);
        getHeatMap('PDOP','2',pointF2,map2);
        getHeatMap('PDOP','3',pointF3,map3);

    });
</script>

</body>
</html>