<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <!--[if lt IE 9]>
    <script type="text/javascript" src="hui/lib/html5shiv.js"></script>
    <script type="text/javascript" src="hui/lib/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="/lib/Hui/static/h-ui/css/H-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="/lib/Hui/static/h-ui.admin/css/H-ui.admin.css" />
    <link rel="stylesheet" type="text/css" href="/lib/Hui/lib/Hui-iconfont/1.0.8/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/lib/Hui/static/h-ui.admin/skin/default/skin.css" id="skin" />
    <link rel="stylesheet" type="text/css" href="/lib/Hui/static/h-ui.admin/css/style.css" />
    <!--[if IE 6]>
    <script type="text/javascript" src="/lib/Hui/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <title>用户列表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 用户管理 <span class="c-gray en">&gt;</span> 在线用户列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
    <div class="mt-20">
        <div class="cl pd-5 bg-1 bk-gray mt-20"><i class="Hui-iconfont"> <span class="r">当前在线用户数：<strong>{{ count($users) }}</strong> </span> </div>
        <table class="table table-border table-bordered table-bg table-hover table-sort table-responsive">
            <thead>
            <tr class="text-c">
                <th width="80">用户ID</th>
                <th width="80">用户名</th>
                <th width="90">手机号码</th>
                <th width="60">性别</th>
                <th width="90">email</th>
            </tr>
            </thead>
            <tbody>
            <tr class="text-cc"></tr>
            @foreach($users as $user)
                <tr class="text-c">
                    <td>{{ $user->uid }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->sex}}</td>
                    <td>{{ $user->email }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="/lib/Hui/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/lib/Hui/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/lib/Hui/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="/lib/Hui/static/h-ui.admin/js/H-ui.admin.js"></script> <!--/_footer 作为公共模版分离出去-->
<script type="text/javascript">
    /*编辑基准站信息*/
    function userEdit(pusher_id){
        console.log(pusher_id);
        window.location.href = '/userEdit?pusher_id=' + pusher_id;
    }
    /*删除基准站*/
    function userDel(obj,pusher_id){
        layer.confirm('确认要删除吗？',function(index){
            console.log(pusher_id);
            $.post("/api/apiUserDelete",
                {
                    pusher_id:pusher_id
                },
                function (data){
                    if (data == 0){
                        $(obj).parents("tr").remove();
                        layer.msg('已删除!',{icon:1,time:1000});
                    }else {
                        console.log(data.msg);
                    }
                }
            );

        });
    }
</script>
<script>


</script>
</body>
</html>