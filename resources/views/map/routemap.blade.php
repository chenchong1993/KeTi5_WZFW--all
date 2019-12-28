<!DOCTYPE html>
<html style="height: 100%">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>大众位置服务云平台</title>

    {{--引入地图依赖的库--}}
    <link rel="stylesheet" type="text/css" href="/Ips_api_javascript/dijit/themes/tundra/tundra.css"/>
    <link rel="stylesheet" type="text/css" href="/Ips_api_javascript/esri/css/esri.css" />
    <link rel="stylesheet" type="text/css" href="/Ips_api_javascript/fonts/font-awesome-4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="/Ips_api_javascript/Ips/css/widget.css" />
    {{--拖动框--}}
    <link rel="stylesheet" type="text/css" href="/css/box.css">
    {{--修改三张地图尺寸--}}
    <style type="text/css">
        /*.user-msg{position:absolute;left:810px;top:10px;z-index:auto;width:500px;background-color:#f6f6f6}*/
        .map-col{position:absolute;left:0;top:0;right:0;height:100%;background-color:#f6f6f6}
    </style>


    {{--引入JS部分--}}
    {{--引入jquery--}}
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>

    {{--地图--}}
    <script type="text/javascript" src="/Ips_api_javascript/init.js"></script>
    {{--拖动框--}}
    <script type="text/javascript" src="js/box.js"></script>
</head>
<body>
<style>
    html, body, #map{
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
    }
</style>

<div class="row">
    <div class="map-col">
        <div id="map"></div>
    </div>

</div>
<script>
    //NA服务配置
    var config={
        FFSserver:{
            route1:"http://121.28.103.199:5567/arcgis/rest/services/C7/network1/NAServer/route",
            route2:"http://121.28.103.199:5567/arcgis/rest/services/C7/network2/NAServer/route",
            route3:"http://121.28.103.199:5567/arcgis/rest/services/C7/network3/NAServer/route"
        }
    };
    //楼层控制变量
    var floor_num=2;
    var map;
    require([
        "Ips/map",
        "Ips/layers/DynamicMapServiceLayer",
        "Ips/widget/IpsNetworkAnalysis",
        "dojo/domReady!"
    ], function (Map,DynamicMapServiceLayer,IpsNetworkAnalysis){
        map = new Map("map", {
            logo:false,
            zoom:21,
            center: [114.3489254,38.24772],
        });
        //初始化F1楼层平面图
        var f1 = new DynamicMapServiceLayer("http://121.28.103.199:5567/arcgis/rest/services/C7/c7floor2/MapServer");
        map.addLayer(f1);
        var networkanalysis = new IpsNetworkAnalysis({
            map: map
        });
        networkanalysis.startup();
    });
</script>

</body>
</html>