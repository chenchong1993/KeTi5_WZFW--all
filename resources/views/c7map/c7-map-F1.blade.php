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
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- tools -->
    <script type="text/javascript" src="/js/tools.js"></script>
    <!-- tools-->

</head>

<body class="tundra">
<div class="panel panel-primary" style="z-index: 99;position: absolute;top: 3%;left: 5%">
    <div class="panel-heading">
        <h3 class="panel-title">导航面板</h3></div>
    <div class="panel-body">
        <input class="btn btn-default" type="button" value="选定起点"  id="startPoint">
        <input class="btn btn-default" type="button" value="选定终点"  id="endPoint">
        {{--        <input class="btn btn-default" type="button" value="途径点  "  id="throughPoint">--}}
        <input class="btn btn-default" type="button" value="清除    "  id="cleanPoint"><br><br>
        <input class="btn btn-default" type="button" value="生成路径"  id="routeAnalysis">
        <input class="btn btn-default" type="button" value="发送路径"  id="sendRoute" data-toggle="modal" data-target="#modal-sendTo">
    </div>
</div>

<div class="modal fade" id="modal-sendTo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" >发送到...</h4>
            </div>

            <div class="modal-body">
                <select class="form-control" style="height: 100%" id="select-user-search">
                    <option value="name">查姓名</option>
                </select>
                <br>
                <input type="text" class="form-control" id="input-sendTo" required>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-close-sendTo-modal">关闭</button>
                    <button type="button" class="btn btn-primary" id="btn-sendTo" onclick="userSend2Search()">查找</button>
                </div>
                <table class="table table-condensed " id="tab-send2user-list">
                </table>
            </div>

        </div>
    </div>
</div>

<div class="row" style="height: 100%">

    <div id="map" class="col-md-12">
        <input class="btn btn-primary radius" type="button" value="F1"  style="position: absolute;left: 93%;bottom: 14.2%;z-index: 99" onclick="go2F1();">
        <input class="btn btn-primary radius" type="button" value="F2"  style="position: absolute;left: 93%;bottom: 10%;z-index: 99" onclick="go2F2()">
        <input class="btn btn-primary radius" type="button" value="F3"  style="position: absolute;left: 93%;bottom: 6%;z-index: 99" onclick="go2F3()">
        <h5 style="position: absolute; left: 50%;top: 5%;z-index: 10">一层</h5>
    </div>

</div>

</div>
</body>

<script>

    //331地图
    // 初始化全局参数
    var HTHT_SERVER_IP = "121.28.103.199:9078"; //航天宏图服务器地址
    var HTHT_TYPE_LOGIN_SCUUESS = 102; //航天宏图消息类型:登录成功
    var HTHT_TYPE_RECEIVE_MSG = 1; //航天宏图消息类型:收到消息
    var INTERVAL_TIME = 2; //数据刷新间隔时间
    var HELLO_STR = "系统初始化成功！"; //初始化欢迎语句
    var ERR_MSG = "您正处于危险区域！";//危险区域发送的信息
    var DANGER_AREA = [];//危险区域范围
    var POINTSIZE = 22;    //默认图片大小为24*24
    var routeAnalysisPoints = [111,23];
    var routeSign = "route:"

    //websocket 连接对象
    var conn;
    var map;

    var config={
        FFSserver:{
            route1:"http://121.28.103.199:5567/arcgis/rest/services/outlets/network1/NAServer/route",
            route2:"http://121.28.103.199:5567/arcgis/rest/services/outlets/network2/NAServer/route",
            route3:"http://121.28.103.199:5567/arcgis/rest/services/C7/network3/NAServer/route"
        }
    };
    //楼层控制变量
    var floor_num=1;
    /**
     * 楼层切换
     */
    function go2F1() {
        window.location.href = '/mapC7';
    }
    function go2F2() {
        window.location.href = '/mapC7F2';
    }
    function go2F3() {
        window.location.href = '/mapC7F3';
    }

    /**
     * 地图定位到这个位置
     */
    function locationTo(x,y,floor){
        //关闭对话框
        $('#btn-close-user-search-modal').click();
        //地图
        require(["esri/geometry/Point",
            "esri/SpatialReference",], function (Point,SpatialReference) {
            var point = new Point(x,y,new SpatialReference({ wkid: 4547}));
            console.log(point);
            map.infoWindow.show(point);
            map.centerAndZoom(point,0.05);

        });
    }
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
     * [人员查找]
     * @return null
     */
    function userSearch(){
        var select_content = $('#input-user-search').val();
        console.log(select_content);
        $.post("/api/getUsersByName",
            {'name':select_content,
                'floor':1},
            function (dat, status) {
                if (dat.status == 0) {
                    $('#tab-user-list').empty();
                    $('#tab-user-list').append('<tr><th>姓名</th><th>所在楼层</th><th>操作</th></tr>');
                    for (var i in dat.data.users) {
                        $('#tab-user-list').append(
                            '<tr>'+
                            '<td>'+ dat.data.users[i].name +'</td>'+
                            '<td>'+ dat.data.users[i].floor +'</td>'+
                            '<td><button class="btn btn-default btn-xs" onclick="locationTo('+ dat.data.users[i].y +','+ dat.data.users[i].x + ',' + dat.data.users[i].floor + ')">查看</button></td>'+
                            '</tr>'
                        );
                    }
                    notify("查找成功", "sys");
                } else {
                    notify("查找失败", "sys");
                }
            }
        );
    }
    /**
     * [推送人员查找]
     * @return null
     */
    function userSend2Search(){
        var select_content = $('#input-sendTo').val();
        console.log(select_content);
        $.post("/api/getUsersByName",
            {'name':select_content,
                'floor':1},
            function (dat, status) {
                if (dat.status == 0) {
                    $('#tab-send2user-list').empty();
                    $('#tab-send2user-list').append('<tr><th>姓名</th><th>操作</th></tr>');
                    for (var i in dat.data.users) {
                        $('#tab-send2user-list').append(
                            '<tr>'+
                            '<td>'+ dat.data.users[i].name +'</td>'+
                            '<td><button class="btn btn-default btn-xs" onclick="sendMessage('+"'"+ dat.data.users[i].uid+ "'" +','+"'"+ routeSign+ "'" +'+'+JSON.stringify(routeAnalysisPoints)+')">发送</button></td>'+
                            '</tr>'
                        );
                    }
                    routeAnalysisPoints = null;
                    notify("查找成功", "sys");
                } else {
                    notify("查找失败", "sys");
                }
            }
        );
    }
    /**
     * [群发消息]
     * @return {[type]} [description]
     */
    function groupMsg(){

        var msg = $('#input-group-msg').val();
        $.get("/api/apiGetAllUserNewLocationList",
            {},
            function (dat, status) {

                if (dat.status == 0) {
                    // 向人员发送消息
                    for (var i in dat.data.users) {
                        sendMessage(dat.data.users[i].uid, msg);
                    }
                    notify("群发消息成功", "sys");
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
        $.post("/api/apiGetAllUserNewLocationList",
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
    function sendMessage(uid, message) {
        console.log("sendMessage id 是", uid);
        if (!conn) {
            return true;
        }
        //端终发消息
        var msgJson = '"{\\"Type\\":2,\\"Data\\":{\\"from\\":31783766124920837,\\"to\\":29914377884794889,\\"content\\":\\"{\\\\\\"_id\\\\\\":3,\\\\\\"content\\\\\\":{\\\\\\"_id\\\\\\":3,\\\\\\"contentType\\\\\\":3,\\\\\\"fileName\\\\\\":null,\\\\\\"json\\\\\\":\\\\\\"' + message + '\\\\\\"},\\\\\\"contentType\\\\\\":3,\\\\\\"conversationId\\\\\\":\\\\\\"1536888885424\\\\\\",\\\\\\"conversationType\\\\\\":1,\\\\\\"createTime\\\\\\":1536888940315,\\\\\\"direct\\\\\\":2,\\\\\\"fileurl\\\\\\":null,\\\\\\"fromUserId\\\\\\":31783766124920837,\\\\\\"isImportance\\\\\\":false,\\\\\\"lat\\\\\\":0.0,\\\\\\"lon\\\\\\":0.0,\\\\\\"msgId\\\\\\":0,\\\\\\"serverMessageId\\\\\\":null,\\\\\\"status\\\\\\":0,\\\\\\"targetIDs\\\\\\":\\\\\\"29914377884794889\\\\\\"}\\",\\"tag\\":\\"1\\",\\"timestamp\\":1536888940}}"';

        var sengMsg = '{"type":1,"to":' + uid+ ',"text":' + msgJson + ',"appid":10,"time":1508898308,"platform":1}';
        conn.send(sengMsg);
        notify("发送消息成功", "sys");
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
        var loginfo = '{ "Type": 101,"Appid": 10,"From": 29914377884794889,"To": 0, "Connid": 0,"ConnServerid": 0, "Gid": 0,"Text": "{\\"uid\\":29914377884794889,\\"user\\":\\"u\\",\\"passwd\\":\\"bd_agent_10\\",\\"key\\":\\"\\",\\"platform\\":2,\\"lastmsgid\\":0}","Time": 1498203115,"Platform": 1,"Payload": null}';
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
            console.log(evt.data)
            var uid = evt.data.substring(27,44);
            rootObj = JSON.parse(evt.data);
            console.log(uid)
            switch (rootObj.Type) {
                case HTHT_TYPE_LOGIN_SCUUESS:
                    notify('云推送登录成功', 'opt_ok');
                    break;
                //接收到消息
                case HTHT_TYPE_RECEIVE_MSG:
                    // console.log("rootObj:" + JSON.stringify(rootObj));

                    // uid = rootObj.From;
                    // console.log("uid  is:" + uid);

                    // console.log("item console:" + rootObj.Text);
                    textObj = $.parseJSON(rootObj.Text);

                    // console.log("item console:" + textObj.Data.content);
                    contentObj = $.parseJSON(textObj.Data.content);

                    // console.log("item console:" + contentObj.content.json);
                    msg = contentObj.content.json;
                    console.log(uid);
                    $.post("api/getUsersByUid",
                        {
                            uid:uid
                        },
                        function (dat, status) {
                            if (dat.status == 0) {
                                console.log(msg);
                                if (msg.indexOf("http") != -1){
                                    notify(dat.data.users[0].name + ":<br/>"
                                        + "<a href="+msg+">点击查看位置分享</a><br>", 'user');
                                }else {
                                    notify(dat.data.users[0].name + ":<br/>" +msg, 'user');
                                }
                            } else {
                                notify('获取用户名失败！', 'user');
                            }
                        }
                    );


                    break;
            }
        }
    } else {
        console.log("Your browser does not support WebSockets.");
    }


    //地图
    require([
        "Ips/map",
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
        "Ips/widget/IpsNetworkAnalysis",
        "esri/tasks/RouteTask",
        "esri/tasks/RouteParameters",
        "esri/tasks/FeatureSet",
        "esri/symbols/TextSymbol",
        "esri/tasks/FindTask",
        "esri/tasks/FindParameters",
        "dojo/colors",
        "dojo/on",
        "dojo/dom",
        "dojo/domReady!"
    ], function (Map, DynamicMapServiceLayer, FeatureLayer, GraphicsLayer, Graphic, SpatialReference, Point, Polyline, Polygon, InfoTemplate, SimpleMarkerSymbol, SimpleLineSymbol,
                 SimpleFillSymbol, PictureMarkerSymbol, IpsNetworkAnalysis, RouteTask,RouteParameters,FeatureSet,TextSymbol,FindTask, FindParameters, Color, on, dom) {


        //-----------------------------一层-------------------------------------
        map = new Map("map", {
            logo: false
        });

        //初始化F1楼层平面图
        var f1 = new DynamicMapServiceLayer("http://121.28.103.199:5567/arcgis/rest/services/C7/c7floor1/MapServer");
        map.addLayer(f1);

        //初始化pointLayer 用户数据点图层
        var point = new GraphicsLayer();
        map.addLayer(point);

        // --------------------------路径分析-----------------------------------------
        //创建路径分析对象
        var routeAnalyst = new RouteTask("http://121.28.103.199:5567/arcgis/rest/services/C7/network1/NAServer/route");
        //创建路径参数对象
        var routeParas = new RouteParameters();
        //障碍点，但是此时障碍点为空
        routeParas.barriers = new FeatureSet();
        //停靠点，但是此时停靠点为空
        routeParas.stops = new FeatureSet();
        //路径是否有方向
        routeParas.returnDirections = false;
        //是否返回路径，此处必须返回
        routeParas.returnRoutes = true;
        //空间参考
        routeParas.outSpatialReference = map.SpatialReference;

        var selectStartPointID;
        var selectStopPointID;
        // var selectThroughPointID;
        //给停靠点按钮添加点击事件
        on(dom.byId("startPoint"),"click",function(){
            selectStartPointID = 1;
        });
        on(dom.byId("endPoint"),"click",function(){
            selectStopPointID = 1;
        });
        // on(dom.byId("throughPoint"),"click",function(){
        //     selectThroughPointID = 1;
        // });

        //定义停靠点的符号
        var stopSymbol = new SimpleMarkerSymbol();
        stopSymbol.style = SimpleMarkerSymbol.STYLE_CIRCLE;
        stopSymbol.setSize(8);
        stopSymbol.setColor(new Color("#ffc61a"));

        on(map, "click", function(evt){
            if(selectStartPointID==1){
                //获得停靠点的坐标
                var pointStart=evt.mapPoint;
                var gr=new Graphic(pointStart,stopSymbol);
                //构建停靠点的参数
                routeParas.stops.features.push(gr);

                //如果selectStartPointID不等于0，将点的坐标在地图上显示出来
                if (selectStartPointID != 0) {
                    addTextPoint("起点", pointStart, stopSymbol);

                    selectStartPointID = 0;
                }
            }
        });
        on(map, "click", function(evt){
            if(selectStopPointID==1){
                //获得停靠点的坐标
                var pointStop=evt.mapPoint;
                var gr=new Graphic(pointStop,stopSymbol);
                //构建停靠点的参数
                routeParas.stops.features.push(gr);

                //如果selectStopPointID不等于0，将点的坐标在地图上显示出来
                if (selectStopPointID != 0) {
                    addTextPoint("终点", pointStop, stopSymbol);

                    selectStopPointID = 0;
                }
            }
        });
        // on(map, "click", function(evt){
        //     if(selectThroughPointID==1){
        //         //获得停靠点的坐标
        //         var pointStop=evt.mapPoint;
        //         var gr=new Graphic(pointStop,stopSymbol);
        //         //构建停靠点的参数
        //         routeParas.stops.features.push(gr);
        //
        //         //如果selectStopPointID不等于0，将点的坐标在地图上显示出来
        //         if (selectThroughPointID != 0) {
        //             addTextPoint("途径点", pointStop, stopSymbol);
        //
        //             selectThroughPointID = 0;
        //         }
        //     }
        // });
        //文本符号：文本信息，点坐标，符号
        function addTextPoint(text,point,symbol) {
            var textSymbol = new TextSymbol(text);
            textSymbol.setColor(new Color([128, 0, 0]));
            var graphicText = Graphic(point, textSymbol);
            var graphicpoint = new Graphic(point, symbol);
            //用默认的图层添加
            map.graphics.add(graphicpoint);
            map.graphics.add(graphicText);
        }
        //给分析按钮添加点击事件
        on(dom.byId("routeAnalysis"),"click",function(){
            //如果障碍点或者停靠点的个数有一个为0，提示用户参数输入不对
            if  (routeParas.stops.features.length == 0)
            {
                alert("输入参数不全，无法分析");
                return;
            }
            //执行路径分析函数
            routeAnalyst.solve(routeParas, showRoute)
        });
        //处理路径分析返回的结果。
        function showRoute(solveResult) {
            //路径分析的结果
            var routeResults = solveResult.routeResults;
            console.log(routeResults[0].route.geometry.paths[0]);
            routeAnalysisPoints = routeResults[0].route.geometry.paths[0];
            //路径分析的长度
            var res = routeResults.length;
            //路径的符号
            var routeSymbol  = new SimpleLineSymbol(SimpleLineSymbol.STYLE_DASH, new Color([0, 0, 200]), 3);
            if (res > 0) {
                for (var i = 0; i < res; i++) {
                    var graphicroute = routeResults[i];
                    var graphic = graphicroute.route;
                    graphic.setSymbol(routeSymbol);
                    map.graphics.add(graphic);
                }
            }
            else {
                alert("没有返回结果");
            }
        }
        on(dom.byId("cleanPoint"),"click",function(){
            map.graphics.clear();
            routeParas=null;
            selectStartPointID = null;
            selectStopPointID = null;
            // selectThroughPointID = null;
            // points = "";
        });
        // --------------------------路径分析结束-----------------------------------------

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
        function addUserPoint(id,uid, lng, lat, name, phone,floor,status) {
            //定义点的几何体
            //38.2477770 114.3489115
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
                + "<b>起始时间：</b><input type='text' name='startTime'class='' id='startTime' placeholder='2018-01-01 00:00:00'><br>"
                + "<b>终止时间：</b><input type='text' name='endTime'class='' id='endTime' placeholder='2018-01-01 23:59:59'><br>"
                + "<button class='' onclick=catUserTrail(" + "'" + uid + "'" + ") > 查看该用户历史轨迹</button><br>"
                // + "<button class='' onclick=catUserRtTrail(" + "'" + uid + "'" + ") > 查看该用户实时轨迹</button>"
                + "<b>发送消息：</b><br>"
                + "<input type='text' name='sendmsg'class='textarea' id='sendmsg' placeholder='内容' style='width: 100%'><br>"
                + "<button class='' onclick=sendMessage(" + "'" + uid + "'" + ",$('#sendmsg').val()) > 发送</button>"
                // + "<button class='' onclick=exportFlie(" + "'" + uid + "'" + ") > 导出该时段数据</button>"
            );
            var picgr = new Graphic(picpoint, picSymbol, attr, infoTemplate);
            if (floor == 1){
                point.add(picgr);
                map.addLayer(point);
            }

        }



        /**
         * 从服务器读取用户列表数据数据并更新界面
         */
        function getDataAndRefresh() {
            // 从云端读取数据
            $.get("/api/apiGetAllUserNewLocationList",
                {},
                function (dat, status) {
                    // console.log(dat);
                    if (dat.status == 0) {
                        // 删除数据
                        point.clear();
                        // pointLayerF2.clear();
                        //重绘
                        point.redraw();
                        // pointLayerF2.redraw();
                        // 添加人
                        //注销掉因为先单用户测试
                        console.log(dat.data)
                        for (var i in dat.data.users) {
                            // for (var i=5; i<10; i++) {
                            // console.log(dat.data[i].username);
                            console.log(dat);
                            addUserPoint(
                                dat.data.users[i].id,
                                dat.data.users[i].uid,
                                dat.data.users[i].y,
                                dat.data.users[i].x,
                                dat.data.users[i].name,
                                dat.data.users[i].phone,
                                dat.data.users[i].floor,
                                i
                            );
                        }

                    } else {
                        console.log('ajax error!');
                    }
                }
            );
        }

        //循环执行
        setInterval(getDataAndRefresh, (INTERVAL_TIME * 1000));

        //显示初始化成功
        notify(HELLO_STR, "sys");

    });




</script>
</html>
