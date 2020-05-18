<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1,user-scalable=no"/>
    <title>区域感知示范系统 </title>
    <link rel="stylesheet" type="text/css" href="/lib/Hui/lib/Hui-iconfont/1.0.8/iconfont.css" />
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
        <h3 class="panel-title">区域感知面板</h3>
    </div>
    <div class="panel-body">
        <h3 class="panel-title">设定感知区域：</h3><br>
        <input class="btn btn-default" type="button" value="选定区域端点"  id="selectPoint">
        <input class="btn btn-default" type="button" value="闭合并生成感知区域"  id="getArea"><br><br>
        <input class="btn btn-default" type="button" value="清除重新选择"  id="cleanPoint"><br><br>
        <h3 class="panel-title">感知区域列表：</h3><br>
        <table class="table table-border table-bordered ">
            <thead>
            <tr class="text-c">
                <th width="100">区域描述</th>
                <th width="10">操作</th>
            </tr>
            </thead>
            @foreach($areas as $area)
                <tr class="text-c">
                    <td>{{ $area->describe }}</td>
                    <td class="f-14 td-manage">
                        <a style="text-decoration:none" class="ml-2" onClick="locationTo('{{ $area->pointList }}')" href="javascript:;" title="定位到地图">
                            <i class="Hui-iconfont">&#xe671;</i>
                        </a>
                        <a style="text-decoration:none" class="ml-2" onClick="delArea('{{ $area->id }}')" href="javascript:;" title="删除">
                            <i class="Hui-iconfont">&#xe6e2;</i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>

<div id="modal-addArea" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content radius">
            <div class="modal-header">
                <h3 class="modal-title">新建位置感知区域</h3>
                <a class="close" data-dismiss="modal" aria-hidden="true" href="javascript:void();">×</a>
            </div>
            <div class="modal-body">
                <label class="modal-body"><span class="c-red"></span>描述：</label>
                <input type="text" class="textarea " value="" placeholder="" id="describe" name="describe">
            </div>
{{--            <div class="modal-body" >--}}
{{--                <p>用户信息:<span id="pointList"></span> </p>--}}
{{--            </div>--}}
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="sendAreaPercept()">确定</button>
            </div>
        </div>
    </div>
</div>

<div class="row" style="height: 100%">

    <div id="map" class="col-md-12">
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
    var pointList = [];
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
     * 跳转到用户历史轨迹页面
     * */
    function catUserTrail(uid) {
        // console.log("2:",uid+" ");
        var startTime = $('#startTime').val();
        var endTime = $('#endTime').val();
        window.location.href = '/ATLSTriail?uid=' + uid + '&startTime=' + startTime +'&endTime=' + endTime;
    }

    /**
     * 地图定位到这个位置
     */
    function locationTo(pointlist){
        var p = $.parseJSON(pointlist)
        console.log(p[0][0]);
        //地图
        require(["esri/geometry/Point",
            "esri/SpatialReference",], function (Point,SpatialReference) {
            var point = new Point(p[0][0],p[0][1],new SpatialReference({ wkid: 4547}));
            console.log(point);
            map.centerAndZoom(point,0.5);

        });
    }
    /**
     * 地图定位到这个位置
     */
    function delArea(id){
        $.post("/api/apiAreaDelete",
            {
                id:id,
            },
            function (dat) {
                if (dat == 0){
                    location.reload();
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
        if (!conn) {
            return true;
        }
        //端终发消息
        var msgJson = '"{\\"Type\\":2,\\"Data\\":{\\"from\\":31783766124920837,\\"to\\":29914377884794889,\\"content\\":\\"{\\\\\\"_id\\\\\\":3,\\\\\\"content\\\\\\":{\\\\\\"_id\\\\\\":3,\\\\\\"contentType\\\\\\":3,\\\\\\"fileName\\\\\\":null,\\\\\\"json\\\\\\":\\\\\\"' + message + '\\\\\\"},\\\\\\"contentType\\\\\\":3,\\\\\\"conversationId\\\\\\":\\\\\\"1536888885424\\\\\\",\\\\\\"conversationType\\\\\\":1,\\\\\\"createTime\\\\\\":1536888940315,\\\\\\"direct\\\\\\":2,\\\\\\"fileurl\\\\\\":null,\\\\\\"fromUserId\\\\\\":31783766124920837,\\\\\\"isImportance\\\\\\":false,\\\\\\"lat\\\\\\":0.0,\\\\\\"lon\\\\\\":0.0,\\\\\\"msgId\\\\\\":0,\\\\\\"serverMessageId\\\\\\":null,\\\\\\"status\\\\\\":0,\\\\\\"targetIDs\\\\\\":\\\\\\"29914377884794889\\\\\\"}\\",\\"tag\\":\\"1\\",\\"timestamp\\":1536888940}}"';

        var sengMsg = '{"type":1,"to":' + uid+ ',"text":' + msgJson + ',"appid":10,"time":1508898308,"platform":1}';
        conn.send(sengMsg);
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


    /**
     *将创建的位置感知区域存储到数据库
     **/
    function sendAreaPercept(){
        var describe = document.getElementById("describe").value;
        var pointListJson = JSON.stringify(pointList);
        $.post("/api/apiAddPointList",
            {
                pointList:pointListJson,
                describe:describe,
            },
            function (dat, status) {
                notify(dat.data, "sys");
                console.log(dat);
                pointList.length = 0;
                location.reload();
            }
        );
    }

    /**
     *判断是否在感知区域内
     * 在C7，参数Alon对应的是y,参数Alat对应的是X，APoints是[[y,x],[y,x]...[y,x]]
     **/
    function IsPtInPoly(ALon, ALat, APoints) {
        var iSum = 0,
            iCount;
        var dLon1, dLon2, dLat1, dLat2, dLon;
        if (APoints.length < 3) return false;
        iCount = APoints.length;
        for (var i = 0; i < iCount-1; i++) {
            if (i == iCount) {
                dLon1 = APoints[i].lng;
                dLat1 = APoints[i].lat;
                dLon2 = APoints[0].lng;
                dLat2 = APoints[0].lat;
            } else {
                dLon1 = APoints[i][0];
                dLat1 = APoints[i][1];
                dLon2 = APoints[i + 1][0];
                dLat2 = APoints[i + 1][1];
            }
            //以下语句判断A点是否在边的两端点的水平平行线之间，在则可能有交点，开始判断交点是否在左射线上
            if (((ALat >= dLat1) && (ALat < dLat2)) || ((ALat >= dLat2) && (ALat < dLat1))) {
                if (Math.abs(dLat1 - dLat2) > 0) {
                    //得到 A点向左射线与边的交点的x坐标：
                    dLon = dLon1 - ((dLon1 - dLon2) * (dLat1 - ALat)) / (dLat1 - dLat2);
                    if (dLon < ALon)
                        iSum++;
                }
            }
        }
        if (iSum % 2 != 0)
            return true;
        return false;
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
                 SimpleFillSymbol, PictureMarkerSymbol, IpsNetworkAnalysis, RouteTask,RouteParameters,FeatureSet,TextSymbol,FindTask, FindParameters,Color, on, dom) {


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
        var linelayer = new GraphicsLayer();
        map.addLayer(linelayer);
        var arealayer = new GraphicsLayer();


        var selectPointID;
        var getArea;

        // var selectThroughPointID;
        //给停靠点按钮添加点击事件
        on(dom.byId("selectPoint"),"click",function(){
            selectPointID = 1;
        });


        //定义停靠点的符号
        var stopSymbol = new SimpleMarkerSymbol();
        stopSymbol.style = SimpleMarkerSymbol.STYLE_CIRCLE;
        stopSymbol.setSize(8);
        stopSymbol.setColor(new Color("#ffc61a"));

        on(map, "click", function(evt){
            if(selectPointID==1){
                //获得停靠点的坐标
                var point=evt.mapPoint;
                var gr=new Graphic(point,stopSymbol);
                //构建停靠点的参数
                addTextPoint("端点", point, stopSymbol);
                var pointxy = [point.x,point.y];
                pointList.push(pointxy);
                console.log(pointxy);

                //定义线的几何体
                var line= new Polyline(pointList);
                //定义线的符号
                var lineSymbol  = new SimpleLineSymbol(SimpleLineSymbol.STYLE_DASH, new Color([255,50,0]), 2);
                var linegr=new Graphic(line,lineSymbol);
                linelayer.add(linegr);

            }
        });
        on(dom.byId("getArea"),"click",function(){
            if (pointList.length<3){
                notify("端点过少无法生成闭合区域", "sys");
            }else {
                pointList.push(pointList[0]);
                console.log(pointList);
                $("#modal-addArea").modal("show");
                // $('#pointList').html(pointList+"<br>");
                linelayer.clear();
                map.graphics.clear();
                selectPointID = 0;

            }
        });
        on(dom.byId("cleanPoint"),"click",function(){
            linelayer.clear();
            map.graphics.clear();
            pointList.length = 0;
        });



        function addArea(point) {
            var polygon= new Polygon($.parseJSON(point));

            //定义面的符号
            var fill= new SimpleFillSymbol(SimpleFillSymbol.STYLE_HORIZONTAL,
                new SimpleLineSymbol(SimpleLineSymbol.STYLE_DASHDOT,new Color([255,50,0]), 2),
                new Color([0,50,200,0.25]));
            var fillgr=new Graphic(polygon,fill);

            arealayer.add(fillgr);
            map.addLayer(arealayer);

        }


        function addTextPoint(text,point,symbol) {
            var textSymbol = new TextSymbol(text);
            textSymbol.setColor(new Color([128, 0, 0]));
            var graphicText = Graphic(point, textSymbol);
            var graphicpoint = new Graphic(point, symbol);
            //用默认的图层添加
            map.graphics.add(graphicpoint);
            map.graphics.add(graphicText);
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
            var userList;
            var areaList;
            // 从云端读取数据
            $.get("/api/apiGetUserAndArea",
                {},
                function (dat, status) {
                    // console.log(dat);
                    if (dat.status == 0) {
                        // 删除数据
                        point.clear();
                        arealayer.clear();
                        //重绘
                        point.redraw();
                        arealayer.redraw();
                        // 添加用户点
                        for (var i in dat.data.users) {
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
                        //添加感知区域
                        for (var i in dat.data.areas) {
                            addArea(dat.data.areas[i].pointList);
                        }
                        //判断感知区域
                        for (var i in dat.data.users) {
                            for (var j in dat.data.areas) {
                                // IsPtInPoly(dat.data.users[i].y,dat.data.users[i].x,dat.data.areas[j].pointList)
                                if (IsPtInPoly(dat.data.users[i].y,dat.data.users[i].x,$.parseJSON(dat.data.areas[j].pointList))){
                                    notify(dat.data.users[i].name+"在"+dat.data.areas[j].describe+"区域内", "sys");
                                    sendMessage(dat.data.users[i].uid,"您在"+dat.data.areas[j].describe+"区域内")
                                }
                            }
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
