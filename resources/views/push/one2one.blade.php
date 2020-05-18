<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <!-- Bootstrap Core CSS -->
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!--[if lt IE 9]>
    <script type="text/javascript" src="hui/lib/html5shiv.js"></script>
    <script type="text/javascript" src="hui/lib/respond.min.js"></script>

    <![endif]-->
    <link rel="stylesheet" type="text/css" href="/lib/Hui/static/h-ui/css/H-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="/lib/Hui/static/h-ui.admin/css/H-ui.admin.css" />
    <link rel="stylesheet" type="text/css" href="/lib/Hui/lib/Hui-iconfont/1.0.8/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/lib/Hui/static/h-ui.admin/skin/default/skin.css" id="skin" />
    <link rel="stylesheet" type="text/css" href="/lib/Hui/static/h-ui.admin/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/Ips_api_javascript/fonts/font-awesome-4.7.0/css/font-awesome.min.css" />
    <!--[if IE 6]>
    <script type="text/javascript" src="hui/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <title>用户消息推送</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 消息管理 <span class="c-gray en">&gt;</span> 点对点发送 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

{{--下面是选择是输入用户群组--}}
{{--云推送接受的js代码--}}
<script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>

<div class="row " style="padding: 2%" >
    <div class="col-sm-4">
        <div class="cl pd-5 bg-1 bk-gray "> <span class="l">当前在线用户数：<strong>{{ count($users) }}</strong> </span> </div>
        <table class="table table-border table-bordered table-bg table-hover table-sort table-responsive">
                <thead>
                <tr class="text-c">
                    <th width="80">用户名</th>
                    <th width="50">操作</th>
                </tr>
                </thead>
                <tbody>
                <tr class="text-cc"></tr>
                @foreach($users as $user)
                    <tr class="text-c">
                        <td>{{ $user->name }}</td>
                        <td class="f-14 td-manage">
                            <button type="button" id="#btn-chat" class="btn btn-link" onclick="pushMsg('{{$user->uid}} ','{{ $user->username }}')">私信</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
    </div>
    {{--下面是聊天窗口布局--}}
    <div class="col-sm-8">
        <div class="chat-panel panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-comments fa-fw"></i> Chat
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <ul class="chat"></ul>
            </div>
            <!-- /.panel-body -->
            <div class="panel-footer">
                <div class="input-group">
                    <input class="form-control input-sm" id="btn-input" type="text" placeholder="Type your message here...">
                    <span class="input-group-btn">
                    <button class="btn btn-warning btn-sm" id="btn-chat">
                        Send
                    </button>
                </span>
                </div>
            </div>
            <!-- /.panel-footer -->
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="/vendor/jquery/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="/vendor/bootstrap/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="/vendor/metisMenu/metisMenu.min.js"></script>
<!-- Custom Theme JavaScript -->
<script src="/dist/js/sb-admin-2.js"></script>
<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="/lib/Hui/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/lib/Hui/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/lib/Hui/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="/lib/Hui/static/h-ui.admin/js/H-ui.admin.js"></script>
<!--/_footer 作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="/lib/Hui/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/lib/Hui/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/lib/Hui/lib/laypage/1.2/laypage.js"></script>
<script type="text/javascript">

    var conn;  //定义一个链接

    /**
     * 登陆
     * @returns {boolean}
     */
    function login(conn) {
        if (!conn) {
            return false;
        }
        // var loginfo = '{"Type":101,"Appid":1,"From":23024091317405712,"To":23024091317406736,"Connid":0,"ConnServerid":0,"Gid":0,"Text":"{\\"uid\\":23024091317405712,\\"user\\":\\"u\\",\\"passwd\\":\\"bd_agent_1\\",\\"key\\":\\"\\",\\"platform\\":2,\\"lastmsgid\\":0}","Time":1463035119,"Msgid":1,"Platform":2,"Payload":null,"Options":{"TimeLive":0,"StartTime":0,"ApnsProduction":false,"Command":null}}';
        // conn.send(loginfo);

        var loginfo = '{ "Type": 101,"Appid": 10,"From": 0,"To":29914363070513161, "Connid": 0,"ConnServerid": 0, "Gid": 0,"Text": "{\\"uid\\":29914377884794889,\\"user\\":\\"u\\",\\"passwd\\":\\"bd_agent_10\\",\\"key\\":\\"\\",\\"platform\\":2,\\"lastmsgid\\":0}","Time": 1498203115,"Platform": 1,"Payload": null}';
        conn.send(loginfo);
        console.log('登陆成功');

    }
    /**
     * 获取当前时间
     * @returns {string}
     */
    function getLocalTime() {
        var date = new Date();
        var seperator1 = "-";
        var seperator2 = ":";
        var month = date.getMonth() + 1;
        var strDate = date.getDate();
        if (month >= 1 && month <= 9) {
            month = "0" + month;
        }
        if (strDate >= 0 && strDate <= 9) {
            strDate = "0" + strDate;
        }
        var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate
            + " " + date.getHours() + seperator2 + date.getMinutes()
            + seperator2 + date.getSeconds();
        return currentdate;
    }
    /**
     * 添加到显示框
     * @param user
     * @param time
     * @param content
     * @param position
     */
    function appendLog(user,time,content,position) {
        if (position=='left')
        {
            $('.chat').append(
                '<li class="left clearfix">' +
                '<span class="chat-img pull-left">' +
                '<img class="img-circle" alt="User Avatar" src="http://placehold.it/50/55C1E7/fff">' +
                '</span>' +
                '<div class="chat-body clearfix">' +
                '<div class="header">' +
                '<strong class="primary-font">'+ user +'</strong>' +
                '<small class="pull-right text-muted">' +
                '<i class="fa fa-clock-o fa-fw"></i> '+ time +'' +
                '</small>' +
                '</div>' +
                '<p>' +
                content +
                '</p>' +
                '</div>' +
                '</li>'
            );
        }
        if (position=='right')
        {
            $('.chat').append(
                '<li class="right clearfix">' +
                '<span class="chat-img pull-right">' +
                '<img class="img-circle" alt="User Avatar" src="http://placehold.it/50/FA6F57/fff">' +
                '</span>' +
                '<div class="chat-body clearfix">' +
                '<div class="header">' +
                '<small class=" text-muted">' +
                '<i class="fa fa-clock-o fa-fw"></i> '+ time +'</small>' +
                '<strong class="pull-right primary-font">'+ user +'</strong>' +
                '</div>' +
                '<p>' +
                content +
                '</p>' +
                '</div>' +
                '</li>'
            );
        }


    }

    /**
     * 把JS接收到的云推送的数据post到后台
     * @param msg
     */
    function pushData(msg) {
        $.post("{{ url('PLSCP/saveResDate') }}", { "msg":msg },
            function(data){
                if(data.status == 0){
                    console.log('successs');
                }else if (data.status == 1){
                    console.log('error');
                }
            }, "json");

    }
    /**
     * 发送消息
     * @param push_user_id
     * @param message
     * @returns {boolean}
     */
    function sendmsg(conn,push_user_id,message) {
        if (!conn) {
            return false;
        }
        /**
         //            给终端发消息模板
         var msgJson = '"{\\"Type\\":1,\\"Data\\":{\\"from\\":0,\\"to\\":0,\\"content\\":\\"{\\\\\\"_id\\\\\\":3,\\\\\\"content\\\\\\":{\\\\\\"_id\\\\\\":3,\\\\\\"contentType\\\\\\":3,\\\\\\"fileName\\\\\\":null,\\\\\\"json\\\\\\":\\\\\\"'+message+'\\\\\\"},\\\\\\"contentType\\\\\\":3,\\\\\\"conversationId\\\\\\":\\\\\\"1536888885424\\\\\\",\\\\\\"conversationType\\\\\\":1,\\\\\\"createTime\\\\\\":1536888940315,\\\\\\"direct\\\\\\":2,\\\\\\"fileurl\\\\\\":null,\\\\\\"fromUserId\\\\\\":889,\\\\\\"isImportance\\\\\\":false,\\\\\\"lat\\\\\\":0.0,\\\\\\"lon\\\\\\":0.0,\\\\\\"msgId\\\\\\":0,\\\\\\"serverMessageId\\\\\\":null,\\\\\\"status\\\\\\":0,\\\\\\"targetIDs\\\\\\":\\\\\\"0\\\\\\"}\\",\\"tag\\":\\"1\\",\\"timestamp\\":1536888940}}"';
         //          给云端发消息模板
         //             var msgJson = message;
         var sengMsg = '{"type":1,"to":'+push_user_id+',"From":29914377884794889,"text":' + msgJson + ',"appid":10,"time":1508898308,"platform":1}';
         conn.send(sengMsg);
         console.log(sengMsg);
         return false
         **/
        var msgJson = '"{\\"Type\\":2,\\"Data\\":{\\"from\\":29914377884794889,\\"to\\":29914377884794889,\\"content\\":\\"{\\\\\\"_id\\\\\\":3,\\\\\\"content\\\\\\":{\\\\\\"_id\\\\\\":3,\\\\\\"contentType\\\\\\":3,\\\\\\"fileName\\\\\\":null,\\\\\\"json\\\\\\":\\\\\\"'+message+'\\\\\\"},\\\\\\"contentType\\\\\\":3,\\\\\\"conversationId\\\\\\":\\\\\\"1536888885424\\\\\\",\\\\\\"conversationType\\\\\\":1,\\\\\\"createTime\\\\\\":1536888940315,\\\\\\"direct\\\\\\":2,\\\\\\"fileurl\\\\\\":null,\\\\\\"fromUserId\\\\\\":31783766124920837,\\\\\\"isImportance\\\\\\":false,\\\\\\"lat\\\\\\":0.0,\\\\\\"lon\\\\\\":0.0,\\\\\\"msgId\\\\\\":0,\\\\\\"serverMessageId\\\\\\":null,\\\\\\"status\\\\\\":0,\\\\\\"targetIDs\\\\\\":\\\\\\"29914377884794889\\\\\\"}\\",\\"tag\\":\\"1\\",\\"timestamp\\":1536888940}}"';

        var sengMsg = '{"type":1,"to":'+push_user_id+',"text":'+msgJson+',"appid":10,"time":1508898308,"platform":1}';
        conn.send(sengMsg);

        // notify("发送成功","opt_ok");
        return true;

    }
    /**
     * 处理API接收消息模板
     * @param data
     */
    function API_Msg(data) {
        var apimsg = JSON.parse(data).Text;
        return apimsg;
    }
    /**
     * 处理终端接收消息模板
     * @param data
     */
    function Mobile_Msg(data) {
        var text = JSON.parse(data).Text;
//            这个对象里有上传数据的很多内容
        var mobilmsg = JSON.parse(JSON.parse(text).Data.content);
        console.log(mobilmsg);
        var mobilejson = mobilmsg.content.json;
        console.log(typeof (mobilejson));
        console.log(mobilejson);
        return mobilejson;

    }

    /**
     * 推送消息
     * @param UserId
     * @param UserName
     */
    function pushMsg(UserId,UserName) {
        console.log(UserId);
        var msg = $('#btn-input');
        var conn;

        $('#btn-chat').click(function () {
            if (!conn) {
                return false;
            }
            if (!msg.val()) {
                return false;
            }
            var timepush = getLocalTime();
//               管理员发的消息
            console.log(msg.val());
            appendLog('admin',timepush,msg.val(),'right');
            var message =  msg.val();
            sendmsg(conn,UserId,message);
            msg.val("");
            return false
        });

        if (window["WebSocket"]) {
            conn = new WebSocket("ws://121.28.103.199:9078/ws");
            console.log("conn:", conn);
            conn.onopen = function (evt) {
                login(conn);
            };
            conn.onclose = function (evt) {
            };

            conn.onmessage = function (evt) {
                console.log(evt.data);
                var timerec = getLocalTime();
                var type = JSON.parse(evt.data).Type;
                if (type == 102){
                    appendLog(UserName,timerec,'连接成功','left')
                    console.log(evt.data);
                }
                else{
                    // var apimsg = API_Msg(evt.data);
                    // console.log(apimsg);
                    // appendLog(UserName,timerec,apimsg,'left')
                    console.log(evt.data);
                    var mobilemsg = Mobile_Msg(evt.data);
                    appendLog(UserName,timerec,mobilemsg,'left')
                }
            }
        } else {
            appendLog($("<div><b>Your browser does not support WebSockets.</b></div>"))
        }


    }

</script>
</body>
</html>



