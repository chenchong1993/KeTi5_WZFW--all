<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <!--[if lt IE 9]>
    <script type="text/javascript" src="lib/Hui/lib/html5shiv.js"></script>
    <script type="text/javascript" src="lib/Hui/lib/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="lib/Hui/static/h-ui/css/H-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="lib/Hui/static/h-ui.admin/css/H-ui.admin.css" />
    <link rel="stylesheet" type="text/css" href="lib/Hui/lib/Hui-iconfont/1.0.8/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="lib/Hui/static/h-ui.admin/skin/default/skin.css" id="skin" />
    <link rel="stylesheet" type="text/css" href="lib/Hui/static/h-ui.admin/css/style.css" />
    <!--[if IE 6]>
    <script type="text/javascript" src="lib/Hui/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->



    <title>群组列表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 消息管理 <span class="c-gray en">&gt;</span> 群组列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">

    <div class="text-c" style="float: right">
        <button class="btn btn-primary radius" onClick="addGroup()">新建群组</button>
    </div>
    <div class="mt-20">
        <table class="table table-border table-bordered table-bg table-hover table-sort table-responsive">
            <thead>
            <tr class="text-c">

                <th width="30">群组名</th>
                <th width="30">管理员</th>
{{--                <th width="60">用户数</th>--}}
                <th width="60">描述</th>
                <th width="30">操作</th>
            </tr>
            </thead>
            <tbody>
            <tr class="text-cc"></tr>
            @foreach($groupInfos as $groupInfo)
                <tr class="text-c">
                    <td>{{ $groupInfo->groupName }}</td>
                    <td>{{ $groupInfo->admin }}</td>
{{--                    <td>{{ $groupInfo->userNum}}</td>--}}
                    <td>{{ $groupInfo->describe }}</td>
                    <td>
                        <input class="btn btn-primary-outline radius" type="button" value="成员列表" onClick="memeberList('{{ $groupInfo->id}}')">
                        <input class="btn btn-primary-outline radius" type="button" value="添加成员" onClick="memeberAdd('{{ $groupInfo->id}}')">
                        <input class="btn btn-primary-outline radius" type="button" value="移除成员" onClick="memeberDel('{{ $groupInfo->id}}')">
                        <input class="btn btn-primary-outline radius" type="button" value="解散群组" onClick="delGroup('{{ $groupInfo->id}}')">
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="modal-add" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content radius">
            <div class="modal-header">
                <h3 class="modal-title">添加成员</h3>
                <a class="close" data-dismiss="modal" aria-hidden="true" href="javascript:void();">×</a>
            </div>

            <div class="modal-body">
                <input type="text" class="input-text radius size-M" id="input-user-search" required>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" onclick="userSearch()">查找</button>
                <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
            </div>
            <table class="table table-condensed " id="tab-user-add">
            </table>
        </div>
    </div>
</div>

<div id="modal-del" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content radius">
            <div class="modal-header">
                <h3 class="modal-title">移除成员</h3>
                <a class="close" data-dismiss="modal" aria-hidden="true" href="javascript:void();">×</a>
            </div>

            <table class="table table-condensed " id="tab-member-del">
            </table>
        </div>
    </div>
</div>

<div id="modal-list" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content radius">
            <div class="modal-header">
                <h3 class="modal-title">成员列表</h3>
                <a class="close" data-dismiss="modal" aria-hidden="true" href="javascript:void();">×</a>
            </div>

            <table class="table table-condensed " id="tab-member-list">
            </table>
        </div>
    </div>
</div>

<div id="modal-addGroup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content radius">
            <div class="modal-header">
                <h3 class="modal-title">新建群组</h3>
                <a class="close" data-dismiss="modal" aria-hidden="true" href="javascript:void();">×</a>
            </div>

            <div class="modal-body">
                <label class="modal-body"><span class="c-red">*</span>群组名：</label>
                <div class="modal-body">
                    <input type="text" class="input-text radius size-M" value="" placeholder="" id="groupname" name="groupname">
                </div>
            </div>

            <div class="modal-body">
                <label class="modal-body"><span class="c-red">*</span>管理员：</label>
                <div class="modal-body">
                    <input type="text" class="input-text radius size-M" readOnly="true" value="位置服务消息中心" placeholder="" id="admin" name="admin">
                </div>
            </div>
            <div class="modal-body">
                <label class="modal-body"><span class="c-red"></span>描述：</label>
                <div class="modal-body">
                    <input type="text" class="input-text radius size-M" value="" placeholder="" id="describe" name="describe">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="getGroup()">确定</button>
            </div>
        </div>
    </div>
</div>
<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="lib/Hui/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="lib/Hui/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="lib/Hui/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="lib/Hui/static/h-ui.admin/js/H-ui.admin.js"></script>

<!--/_footer 作为公共模版分离出去-->

<script type="text/javascript">
        var groupid = 0;

    function addGroup() {
        // window.location.href = '/addGroup';
        $("#modal-addGroup").modal("show");
    }
    function getGroup() {
        var groupname = document.getElementById("groupname").value;
        var admin = document.getElementById("admin").value;
        var describe = document.getElementById("describe").value;
        $.post("/api/apiAddGroup",
            {'groupname':groupname,
                'admin':admin,
                'describe':describe},
            function (dat, status){
                console.log(dat.data)
                if (dat.data == "新建群组成功"){
                    // $(obj).parents("tr").remove();
                    layer.msg('新建群组成功!',{icon:1,time:1000});
                    location.replace(location.href);
                }
                if(dat.data.groupname == "The groupname field is required.") {
                    layer.msg('群组名是必填项!',{icon:2,time:1000});
                }
                if(dat.data.admin == "The admin field is required.") {
                    layer.msg('管理员是必填项!',{icon:2,time:1000});
                }
            }
        );

    }
    function delGroup(id){
        console.log(id)
        groupid = id;
        $.post("/api/apiDelGroup",
            {'id':id},
            function (dat, status){
                console.log(dat.data)
                if (dat.data == "解散成功"){
                    layer.msg('解散成功!',{icon:1,time:1000});
                    location.replace(location.href);
                }else {
                    layer.msg('解散失败!',{icon:2,time:1000});
                }
            }
        );
    }


    function memeberAdd(id){
        console.log(id)
        groupid = id;
        $("#modal-add").modal("show");
    }
    function memeberDel(id){
        console.log(id)
        groupid = id;
        $("#modal-del").modal("show");
        $.post("/api/memberList",
            {'id':id},

            function (dat, status) {

                if (dat.status == 0) {
                    $('#tab-member-del').empty();
                    $('#tab-member-del').append('<tr><th>姓名</th><th>操作</th></tr>');
                    console.log(dat.data.names[0]);
                    for (var i in dat.data.names) {
                        $('#tab-member-del').append(
                            '<tr>'+
                            '<td>'+ dat.data.names[i] +'</td>'+
                            '<td><button class="btn btn-primary-outline radius" onclick="delMember(groupid,'+"'"+dat.data.ids[i]+"'"+','+"'"+dat.data.names[i]+"'"+')">移除成员</button></td>'+
                            '</tr>'
                        );
                    }
                } else {
                }
            }
        );
    }
    function memeberList(id){
        console.log(id)
        groupid = id;
        $("#modal-list").modal("show");
        $.post("/api/memberList",
            {'id':id},

            function (dat, status) {

                if (dat.status == 0) {
                    $('#tab-member-list').empty();
                    $('#tab-member-list').append('<tr><th>uid</th><th>姓名</th></tr>');
                    console.log(dat.data.names[0]);
                    for (var i in dat.data.names) {
                        $('#tab-member-list').append(
                            '<tr>'+
                            '<td>'+ dat.data.ids[i] +'</td>'+
                            '<td>'+ dat.data.names[i] +'</td>'+
                            '</tr>'
                        );
                    }
                } else {
                }
            }
        );
    }
    function userSearch() {
        var select_content = $('#input-user-search').val();
        console.log(select_content)
        $.post("/api/getUsersByName",
            {'name':select_content,
                'floor':1},
            function (dat, status) {
                if (dat.status == 0) {
                    $('#tab-user-add').empty();
                    $('#tab-user-add').append('<tr><th>姓名</th><th>操作</th></tr>');
                    for (var i in dat.data.users) {
                        $('#tab-user-add').append(
                            '<tr>'+
                            '<td>'+ dat.data.users[i].name +'</td>'+
                            '<td><button class="btn btn-primary-outline radius" onclick="getMember(groupid,'+"'"+dat.data.users[i].uid+"'"+')">设为成员</button></td>'+
                            '</tr>'
                        );
                    }
                } else {
                }
            }
        );

    }
    function getMember(id,uid) {
        console.log(id);
        console.log(uid);
        $.post("/api/memberAdd",
            {'id':id,
                'uid':uid},
            function (dat, status){
            console.log(dat.data)
                if (dat.data == "添加成功"){
                    // $(obj).parents("tr").remove();
                    layer.msg('添加成功!',{icon:1,time:500});
                }else {
                    // $(obj).parents("tr").remove();
                    layer.msg('该成员已存在!',{icon:2,time:500});
                }
            }
        );
    }
    function delMember(id,uid,name) {
        console.log(id);
        console.log(uid);
        $.post("/api/memberDel",
            {'id':id,
                'uid':uid,
                'name':name},
            function (dat, status){
                console.log(dat);
                if (dat.data == "移除成功"){
                    // $(obj).parents("tr").remove();
                    layer.msg('移除成功!',{icon:1,time:500});
                }else {
                    // $(obj).parents("tr").remove();
                    layer.msg('该成员已移除!',{icon:2,time:500});
                }
            }
        );
    }



</script>
</body>
</html>
