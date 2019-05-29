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
    <script type="text/javascript" src="hui/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <title>资讯列表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 检索查询 <span class="c-gray en">&gt;</span> 扩展检索 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
    <div class="text-c">
        <span class="select-box inline">
		<select id="type" class="select">
			<option value="0">检索类型</option>
			<option value="1">人名</option>
			<option value="2">地区</option>
            <option value="3">物名</option>
		</select>
		</span>
        关键词：
        <input type="text" name="searchKeyWord" id="searchKeyWord" placeholder=" 关键词" style="width:250px" class="input-text">
        <button name="" id=""  onClick="nameSearch($('#type').val(),$('#searchKeyWord').val())"  class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 检索</button>
    </div>
    <div class="mt-20">
        <table id = "search-list-table" class="table table-border table-bordered table-bg table-hover table-sort table-responsive">
        </table>
    </div>
</div>
<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="/lib/Hui/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/lib/Hui/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/lib/Hui/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="/lib/Hui/static/h-ui.admin/js/H-ui.admin.js"></script> <!--/_footer 作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="/lib/Hui/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/lib/Hui/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/lib/Hui/lib/laypage/1.2/laypage.js"></script>
<script type="text/javascript">
    function nameSearch(searchType,searchKeyWord) {
        console.log(searchType);
        $.post("/api/apiNameSeach",
            {
                searchType:searchType,
                searchKeyWord:searchKeyWord
            },
            function (dat, status) {
                console.log(dat);
                if (dat.status == 0) {
                    $('#search-list-table').empty();
                    $('#search-list-table').append(
                        '<tr>' +
                        '<th>用户ID</th>' +
                        '<th>用户名</th>' +
                        '<th>手机号</th>' +
                        '<th>性别</th>' +
                        '<th>Email</th>' +
                        '<th>地区</th>' +
                        '</tr>'
                    );
                    for (var i in dat.data) {
                        $('#search-list-table').append(
                            '<tr>' +
                            '<td>' + dat.data[i].uid + '</td>' +
                            '<td>' + dat.data[i].name + '</td>' +
                            '<td>' + dat.data[i].phone + '</td>' +
                            '<td>' + dat.data[i].sex + '</td>' +
                            '<td>' + dat.data[i].email + '</td>' +
                            '<td>' + dat.data[i].address + '</td>' +
                            '</tr>'
                        );
                    }
                } else {
                    console.log('ajax error!');
                }
            }
        );
    }
</script>
</body>
</html>