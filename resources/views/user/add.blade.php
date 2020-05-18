<!--_meta 作为公共模版分离出去-->
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="Bookmark" href="/favicon.ico" >
    <link rel="Shortcut Icon" href="/favicon.ico" />
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
    <!--/meta 作为公共模版分离出去-->

</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 用户管理 <span class="c-gray en">&gt;</span> 添加用户 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<article class="page-container">
    <div class="form form-horizontal" id="form-article-add">
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>用户名：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="" placeholder="" id="username" name="username">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>密码：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="password" class="input-text" value="" placeholder="" id="password" name="password">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>手机号：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="" placeholder="" id="phone" name="phone">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>email：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="" placeholder="" id="email" name="email">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>sex：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <span class="select-box inline">
                <select name="sex" class="select" id="sex" onchange="getSex()">
                    <option value="0">男</option>
                    <option value="1">女</option>
                </select>
                </span>
            </div>
        </div>
        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                <button  class="btn btn-primary radius" type="submit" onClick="save();"><i class="Hui-iconfont">&#xe632;</i> 保存并提交</button>
            </div>
        </div>
    </div>
</article>

<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="/lib/Hui/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/lib/Hui/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/lib/Hui/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="/lib/Hui/static/h-ui.admin/js/H-ui.admin.js"></script> <!--/_footer /作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="/lib/Hui/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/lib/Hui/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
<script type="text/javascript" src="/lib/Hui/lib/jquery.validation/1.14.0/validate-methods.js"></script>
<script type="text/javascript" src="/lib/Hui/lib/jquery.validation/1.14.0/messages_zh.js"></script>
<script type="text/javascript" src="/lib/Hui/lib/webuploader/0.1.5/webuploader.min.js"></script>
<script type="text/javascript" src="/lib/Hui/lib/ueditor/1.4.3/ueditor.config.js"></script>
<script type="text/javascript" src="/lib/Hui/lib/ueditor/1.4.3/ueditor.all.min.js"> </script>
<script type="text/javascript" src="/lib/Hui/lib/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript">

    var sex = 0;

    /**
     * 获取接入流类型
     */
    function getSex(){
        var select = document.getElementById("sex");
        sex = select.value
        // console.log(intype);
    }

    function save(){
        $(function(){
            $('.skin-minimal input').iCheck({
                checkboxClass: 'icheckbox-blue',
                radioClass: 'iradio-blue',
                increaseArea: '20%'
            });
            //表单验证
            $("#form-article-add").validate({
                rules: {
                    username: {
                        required: true,
                    },
                    password: {
                        required: true,
                    },
                    phone: {
                        required: true,
                    },
                    email: {
                        required: true,
                    },
                    sex: {
                        required: true,
                    },
                },
                onkeyup: false,
                focusCleanup: true,
                success: "valid",
            });
        });
        var username = document.getElementById("username");
        username = username.value;
        var password = document.getElementById("password");
        password = password.value;
        var phone = document.getElementById("phone");
        phone = phone.value;
        var sex = document.getElementById("sex");
        sex = sex.value;
        var email = document.getElementById("email");
        email = email.value;
        $.post("/api/apiUserAdd",
            {'username':username,
                'password':password,
                'phone':phone,
                'sex':sex,
                'email':email,
            },
            function (dat, status){
                layer.msg(dat.data,{icon:1,time:1000});
            }
        );


    }
</script>
<!--/请在上方写此页面业务相关的脚本-->
</body>
</html>