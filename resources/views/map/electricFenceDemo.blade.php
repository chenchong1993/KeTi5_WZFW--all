<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1,user-scalable=no"/>
    <title>添加图形</title>

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
{{--拖动框--}}
<div class="box">
    <div class="title">电子围栏验证</div>
    <div class="con">
        <p>圆心坐标为[116.29656182, 40.04275177]</p>
        <input type="text" name="LNG" id="LNG" value="经度">
        <input type="text" name="LAT" id="LAT" value="纬度">
        <input type="button" onclick="getElements()" value="验证" />
    </div>

</div>
{{--拖动框--}}
</body>

<script>

    //331地图
    // 初始化全局参数
    var HTHT_SERVER_IP = "121.28.103.199:9078"; //航天宏图服务器地址
    var HTHT_TYPE_LOGIN_SCUUESS = 102; //航天宏图消息类型:登录成功
    var HTHT_TYPE_RECEIVE_MSG = 1; //航天宏图消息类型:收到消息
    var INTERVAL_TIME = 4; //数据刷新间隔时间
    var HELLO_STR = "系统初始化成功！"; //初始化欢迎语句
    var ERR_MSG = "您正处于危险区域！";//危险区域发送的信息
    var DANGER_AREA = [];//危险区域范围

    var userLng = 0;   //测试电子围栏
    var userLat = 0;

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
    var map;


    /**
     * 地图定位到这个位置
     */
    function locationTo(lng,lat){
        $('#btn-close-user-search-modal').click();
        $('#btn-close-device-search-modal').click();
        //地图
        require(["esri/geometry/Point",], function (Point) {
            var point = new Point(lng,lat);
            map.infoWindow.show(point)
            map.centerAndZoom(point,1);
        });
    }


    /**
     * [群发消息]
     * @return {[type]} [description]
     */
    function groupMsg(){

        var msg = $('#input-group-msg').val();
        $.post("/api/getAllLocation",
            {},
            function (dat, status) {

                if (dat.status == 0) {
                    // 向人员发送消息
                    for (var i in dat.data.users) {
                        sendMessage(dat.data.users[i].uid, msg, 1);
                    }
                    // 向设备发送消息
                    // for (var i in dat.data.devices) {
                    //     sendMessage(dat.data.devices[i].uid, msg);
                    // }
                    $.post("/api/msgTxAdd",
                        {content:msg},
                        function (dat, status) {
                            //TODO
                            if (dat.status == 0) {
                                notify("群发消息成功", "sys");
                            } else {
                                notify("群发消息失败", "sys");
                            }
                        }
                    );
                } else {
                    notify("群发消息失败", "sys");
                }
            }
        );
    }

    /**
     * [群发命令]
     * @param  {[type]} cmd [发送的命令]
     * @return {[type]}     [description]
     */
    function groupCmd(cmd){
        console.log(cmd);
        $.post("/api/getAllLocation",
            {},
            function (dat, status) {

                if (dat.status == 0) {
                    // 向人员发送消息
                    // for (var i in dat.data.users) {
                    //     sendMessage(dat.data.users[i].uid, msg);
                    // }
                    // 向设备发送消息
                    for (var i in dat.data.devices) {
                        sendMessage(dat.data.devices[i].uid, cmd);
                    }
                    notify("群发消息成功", "sys");


                } else {
                    notify("群发消息失败", "sys");
                }
            }
        );
    }

    /**
     * [通过云推送发送命令]
     * @param  {[type]} uid [推送目标ID]
     * @param  {[type]} message    [推送信息]
     * @return {[null]}            [description]
     */
    function sendMessage(uid, message,isGroup = 0) {
        console.log("sendMessage id 是", uid);
        if (!conn) {
            console.log(1111);
            return true;
        }
        //端终发消息
        var msgJson = '"{\\"Type\\":2,\\"Data\\":{\\"from\\":31783766124920837,\\"to\\":29914377884794889,\\"content\\":\\"{\\\\\\"_id\\\\\\":3,\\\\\\"content\\\\\\":{\\\\\\"_id\\\\\\":3,\\\\\\"contentType\\\\\\":3,\\\\\\"fileName\\\\\\":null,\\\\\\"json\\\\\\":\\\\\\"' + message + '\\\\\\"},\\\\\\"contentType\\\\\\":3,\\\\\\"conversationId\\\\\\":\\\\\\"1536888885424\\\\\\",\\\\\\"conversationType\\\\\\":1,\\\\\\"createTime\\\\\\":1536888940315,\\\\\\"direct\\\\\\":2,\\\\\\"fileurl\\\\\\":null,\\\\\\"fromUserId\\\\\\":31783766124920837,\\\\\\"isImportance\\\\\\":false,\\\\\\"lat\\\\\\":0.0,\\\\\\"lon\\\\\\":0.0,\\\\\\"msgId\\\\\\":0,\\\\\\"serverMessageId\\\\\\":null,\\\\\\"status\\\\\\":0,\\\\\\"targetIDs\\\\\\":\\\\\\"29914377884794889\\\\\\"}\\",\\"tag\\":\\"1\\",\\"timestamp\\":1536888940}}"';

        var sengMsg = '{"type":1,"to":' + uid+ ',"text":' + msgJson + ',"appid":10,"time":1508898308,"platform":1}';
        conn.send(sengMsg);

        if (isGroup) {
            notify("发送成功", "opt_ok");
            return true;
        }

        $.post("/api/msgTxAdd",
            {
                content:message,
                uid:uid
            },
            function (dat, status) {
                //TODO
                if (dat.status == 0) {
                    notify("发送成功", "opt_ok");

                } else {
                    notify("存储数据失败", "opt_ok");
                }
            }
        );
        return true;
    }

    /**
     * 云推送登錄
     * @return {[type]} [description]
     */
    function login() {
        if (!conn) {
            return false;
        }
        var loginfo = '{ "Type": 101,"Appid": 10,"From": 29914363070513161,"To": 0, "Connid": 0,"ConnServerid": 0, "Gid": 0,"Text": "{\\"uid\\":29914363070513161,\\"user\\":\\"u\\",\\"passwd\\":\\"bd_agent_10\\",\\"key\\":\\"\\",\\"platform\\":2,\\"lastmsgid\\":0}","Time": 1498203115,"Platform": 1,"Payload": null}';
        conn.send(loginfo);
    }


    //连接云推送WS
    if (window["WebSocket"]) {
        conn = new WebSocket("ws://" + HTHT_SERVER_IP + "/ws");
        console.log("conn:", conn)
        conn.onopen = function (evt) {
            login()
        };
        conn.onclose = function (evt) {
            console.log(evt.data);
        }
        conn.onmessage = function (evt) {
            rootObj = JSON.parse(evt.data);

            switch (rootObj.Type) {
                case HTHT_TYPE_LOGIN_SCUUESS:
                    notify('云推送登录成功', 'opt_ok');
                    break;
                //接收到消息
                case HTHT_TYPE_RECEIVE_MSG:
                    // console.log("rootObj:" + JSON.stringify(rootObj));

                    uid = rootObj.From;
                    // console.log("uid  is:" + uid);

                    // console.log("item console:" + rootObj.Text);
                    textObj = $.parseJSON(rootObj.Text);

                    // console.log("item console:" + textObj.Data.content);
                    contentObj = $.parseJSON(textObj.Data.content);

                    // console.log("item console:" + contentObj.content.json);
                    msg = contentObj.content.json;

                    $.post("/api/getUsersByUid",
                        {
                            uid:uid
                        },
                        function (dat, status) {
                            if (dat.status == 0) {

                                notify(dat.data.users[0].name + ":<br/>" + msg, 'user');

                            } else {
                                notify('获取用户名失败！', 'user');
                            }
                        }
                    );

                    $.post("/api/msgRxAdd",
                        {
                            content:msg,
                            uid:uid
                        },
                        function (dat, status) {
                            if (dat.status == 0) {

                            } else {

                            }
                        }
                    );

                    break;
            }
        }
    } else {
        console.log("Your browser does not support WebSockets.");
    }
    function getElements()
    {
        var LNG = document.getElementsByName("LNG");
        var LAT = document.getElementsByName("LAT");
        userLng = LNG[0].value;   //测试
        userLat = LAT[0].value;
        console.log(LNG[0].value);
        console.log(LAT[0].value);
        dlng = (userLng -116.29656182)*111319.4907;
        dlat = (userLat -40.04275177)*111319.4907;
        if(Math.sqrt(dlng*dlng+dlat*dlat)<50)
            alert("用户处于围栏内部");
        else{
            alert("用户未进入围栏");
        }
    }

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
            zoom:13,
            extent:initialExtent,
            logo:false
        });
        //初始化pointLayer 用户数据点图层
        var pointLayer = new GraphicsLayer();
        map.addLayer(pointLayer);

        //初始化GraphicsLayer
        var layer = new GraphicsLayer();
        //生成圆的数据数组开始
        var num = 3600, r = 0.004, cx = 116.29656182, cy = 40.04275177,clockwise=false;
        var arr = [];
        var du = 360 / num;
        // 是否反向旋转

        for (var times = 0; times < num; times++) {
            var hudu = (2 * Math.PI / 360) * (du * times - 180);
            if (clockwise) hudu *= -1;
            var x = Math.sin(hudu) * r + cx;
            var y = Math.cos(hudu) * r + cy;
            arr.push([x, y]);
        }
        arr.push([cx, cy - r]); // 闭合
        //数组生成完毕
        //设置电子围栏
        var polygon= new Polygon(arr);
        //定义面的符号
        var fill= new SimpleFillSymbol(SimpleFillSymbol.STYLE_HORIZONTAL,
            new SimpleLineSymbol(SimpleLineSymbol.STYLE_DASHDOT,new Color([255,50,0]), 2),
            new Color([0,50,200,0.25]));
        var fillgr=new Graphic(polygon,fill);
        layer.add(fillgr);
        map.addLayer(layer);

        // //设置电子围栏
        // $.post("/api/getFenceList",
        //     {},
        //     function (dat, status) {
        //
        //         if (dat.status == 0) {
        //
        //             //初始化 电子围栏图层
        //             var surfaceLayer = new GraphicsLayer();
        //
        //             for (var i in dat.data.fences) {
        //                 DANGER_AREA.push($.parseJSON(dat.data.fences[i].content));
        //
        //                 //定义面的几何体
        //                 var polygon = new Polygon($.parseJSON(dat.data.fences[i].content));
        //                 //定义面的符号
        //                 var fill = new SimpleFillSymbol(SimpleFillSymbol.STYLE_HORIZONTAL,
        //                     new SimpleLineSymbol(SimpleLineSymbol.STYLE_DASHDOT, new Color([255, 50, 0]), 2),
        //                     new Color([0, 50, 200, 0.25]));
        //                 var fillgr = new Graphic(polygon, fill);
        //
        //                 surfaceLayer.add(fillgr);
        //
        //             }
        //             console.log(DANGER_AREA);
        //
        //             map.addLayer(surfaceLayer);
        //             notify("读取电子围栏成功", "sys");
        //
        //         } else {
        //             notify("读取电子围栏失败", "sys");
        //         }
        //     }
        // );


        /**
         * 根据返回点判断是否为危险区域内
         * @param {[type]} x       [x坐标]
         * @param {[type]} y       [y坐标]
         */
        function isInDangerArea(lng, lat) {
            dlng = lng -116.29656182;
            dlat = lat -40.04275177;
            if(Math.sqrt(dlng*dlng+dlat*dlat)<0.001)
                console.log("111111111111111111")
        }


        /**
         * 添加用戶點
         * @param {[type]} id      [用户ID]
         * @param {[type]} x       [x坐标]
         * @param {[type]} y       [y坐标]
         * @param {[type]} name    [用户姓名]
         * @param {[type]} phone   [用户手机号]
         * @param {[type]} uid     [用户推送ID]
         * @param {[type]} status  [用户状态]
         */
        function addUserPoint(id, x, y, name, phone, uid, status) {

            //定义点的几何体
            //38.2477770 114.3489115
            var picpoint = new Point(x, y);
            // //定义点的图片符号
            var picSymbol;
            if (status == 0)
                picSymbol = new PictureMarkerSymbol("Ips_api_javascript/Ips/image/user.png", 32, 32);
            else if (status == 1)
                picSymbol = new PictureMarkerSymbol("Ips_api_javascript/Ips/image/user-red.png", 32, 32);

            //定义点的图片符号
            var attr = {"name": name, "phone": phone};
            //信息模板
            var infoTemplate = new InfoTemplate();
            infoTemplate.setTitle('人员');

            infoTemplate.setContent(
                "<b>名称:</b><span>${name}</span><br>"
                + "<b>手机号:</b><span>${phone}</span><br><br>"
                + "<button class='' onclick=sendMessage("
                + "'" + uid + "'" + ",'预警信息1') >预警信息1</button>"
                + "<button class='' onclick=sendMessage("
                + "'" + uid + "'" + ",'预警信息2') >预警信息2</button>"
                + "<button class='' onclick=sendMessage("
                + "'" + uid + "'" + ",'预警信息3') >预警信息3</button>"
            );
            var picgr = new Graphic(picpoint, picSymbol, attr, infoTemplate);
            pointLayer.add(picgr);
        }

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
                                if ((38.24766<dat.data[i].lat)&&(dat.data[i].lat<38.2478) &&(114.3485<dat.data[i].lng)&&(dat.data[i].lng<114.34871))
                                {
                                    addUserPoint(
                                        dat.data[i].id,
                                        dat.data[i].uid,
                                        dat.data[i].lng,
                                        dat.data[i].lat,
                                        dat.data[i].name,
                                        dat.data[i].phone,
                                        dat.data[i].floor,
                                        i
                                    );
                                }
                            }
                            else {
                                if ((38.24766<dat.data[i].lat)&&(dat.data[i].lat<38.2478) &&(114.3485<dat.data[i].lng)&&(dat.data[i].lng<114.349238))
                                {
                                    addUserPoint(
                                        dat.data[i].id,
                                        dat.data[i].uid,
                                        dat.data[i].lng,
                                        dat.data[i].lat,
                                        dat.data[i].name,
                                        dat.data[i].phone,
                                        dat.data[i].floor,
                                        i
                                    );
                                }
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
        //setInterval(getDataAndRefresh, (INTERVAL_TIME * 1000));

        //显示初始化成功
        notify(HELLO_STR, "sys");

    });




</script>
</html>