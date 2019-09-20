<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1,user-scalable=no" />
    <title>事件绑定</title>
    <link rel="stylesheet" type="text/css" href="/Ips_api_javascript/dijit/themes/tundra/tundra.css"/>
    <link rel="stylesheet" type="text/css" href="/Ips_api_javascript/esri/css/esri.css"/>
    <link rel="stylesheet" type="text/css" href="/Ips_api_javascript/fonts/font-awesome-4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="/Ips_api_javascript/Ips/css/widget.css"/>

    <script type="text/javascript" src="Ips_api_javascript/init.js"></script>
    <style>
        html, body, #map {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }
        #remove{
            position: absolute;top:30px;left:200px;font-size: 18px;
        }
    </style>
    <script>
        require([
            "Ips/map",
            "dojo/on",
            "dojo/dom",
            "dojo/domReady!ClickMapPoint"
        ], function (Map,on,dom){
            var map = new Map("map", {
                basemap: "topo",
                center: [-122.45, 37.75],
                zoom: 13,
                logo:false
            });
            //on绑定事件
            var click= on(map,"click",function (evt) {
                var point=evt.mapPoint;
                alert("X:"+point.x+"y:"+point.y)
            })
            on(dom.byId("remove"),"click",function () {
                click.remove();
            })
        });
    </script>
</head>
<body class="tundra">
<div id="map"></div>
<button id="remove">解绑click事件</button>
</body>
</html>