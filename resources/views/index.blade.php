<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    <link rel="Bookmark" href="hui/favicon.jpg" >
    <link rel="Shortcut Icon" href="hui/favicon.jpg" />
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
    <title>大众位置服务</title>
    <meta name="description" content="综合管理系统">

</head>
<body>
{{--头部--}}
<header class="navbar-wrapper">
    <div class="navbar navbar-fixed-top">
        <div class="container-fluid cl">
            <nav class="nav navbar-nav">
                <ul class="cl">
                    <li class="dropDown dropDown_hover "><a href="javascript:;" class="dropDown_A" style="font-size: 18px">大众位置服务<i class="Hui-iconfont">&#xe6d5;</i></a>
                        <ul class="dropDown-menu menu radius box-shadow">
                            <li><a href="http://121.28.103.199:180/index"> 安全监控平台</a></li>
                            <li><a href="http://121.28.103.199:5621/yjjy/main/index">应急救援平台</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
                <ul class="cl">
                    <li>超级管理员</li>
                    <li class="dropDown dropDown_hover">
                        {{--                        <a href="#" class="dropDown_A">admin <i class="Hui-iconfont">&#xe6d5;</i></a>--}}
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <ul class="dropDown-menu menu radius box-shadow">
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    退出
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                    <li id="Hui-skin" class="dropDown right dropDown_hover"> <a href="javascript:;" class="dropDown_A" title="换肤"><i class="Hui-iconfont" style="font-size:18px">&#xe62a;</i></a>
                        <ul class="dropDown-menu menu radius box-shadow">
                            <li><a href="javascript:;" data-val="default" title="默认（黑色）">默认（黑色）</a></li>
                            <li><a href="javascript:;" data-val="blue" title="蓝色">蓝色</a></li>
                            <li><a href="javascript:;" data-val="green" title="绿色">绿色</a></li>
                            <li><a href="javascript:;" data-val="red" title="红色">红色</a></li>
                            <li><a href="javascript:;" data-val="yellow" title="黄色">黄色</a></li>
                            <li><a href="javascript:;" data-val="orange" title="橙色">橙色</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>
{{--左侧菜单栏--}}
<aside class="Hui-aside">
    <div class="menu_dropdown bk_2">
        <dl id="menu-article">
            <dt><i class="Hui-iconfont">&#xe62c;</i> 用户管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
            <dd>
                <ul>
                    <li><a data-href="{{ url('userList')}}" data-title="用户列表" href="javascript:void(0)">用户列表</a></li>
                    <li><a data-href="{{ url('userOnlineList')}}" data-title="在线用户" href="javascript:void(0)">在线用户</a></li>
                    <li><a data-href="{{ url('userAdd')}}" data-title="添加用户" href="javascript:void(0)">添加用户</a></li>
                </ul>
            </dd>
        </dl>

        <dl id="menu-picture">
            <dt><i class="Hui-iconfont">&#xe671;</i> 基础地图<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
            <dd>
                <ul>
                    <li><a data-href="{{ url('mapC7')}}" data-title="基础地图" href="javascript:void(0)">C7地图</a></li>
                    <li><a data-href="{{ url('map331')}}" data-title="基础地图" href="javascript:void(0)">331地图</a></li>
                    <li><a data-href="{{ url('ATLSmap')}}" data-title="基础地图" href="javascript:void(0)">奥特莱斯地图</a></li>
                    <li><a data-href="{{ url('routeMap')}}" data-title="路径导航" href="javascript:void(0)">路径导航</a></li>
{{--                    <li><a data-href="{{ url('ATLSmap')}}" data-title="奥莱示范模拟" href="javascript:void(0)">奥莱示范模拟</a></li>--}}
                    <li><a data-href="http://121.28.103.199:5561/iserver/iClient/for3D/webgl/zh/examples/S3M_331.html" data-title="三维地图浏览" href="javascript:void(0)">三维地图浏览</a></li>
                </ul>
            </dd>
        </dl>

        <dl id="menu-picture">
            <dt><i class="Hui-iconfont">&#xe6c1;</i> 热力图<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
            <dd>
                <ul>
                    <li><a data-href="{{ url('bluSignalHeatMap')}}" data-title="信号强度热力图" href="javascript:void(0)">信号强度热力图</a></li>
                    <li><a data-href="{{ url('peopleIn331')}}" data-title="人口分布热力图" href="javascript:void(0)">人口分布热力图</a></li>
                    <li><a data-href="{{ url('hdopHeatMap')}}" data-title="精度因子热力图" href="javascript:void(0)">精度因子热力图</a></li>
                </ul>
            </dd>
        </dl>

        <dl id="menu-picture">
            <dt><i class="Hui-iconfont">&#xe665;</i> 检索查询<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
            <dd>
                <ul>
                    <li><a data-href="{{ url('nameSearch')}}" data-title="名称检索" href="javascript:void(0)">名称检索</a></li>
                    <li><a data-href="{{ url('extentSearch')}}" data-title="扩展检索" href="javascript:void(0)">扩展检索</a></li>
                </ul>
            </dd>
        </dl>

        <dl id="menu-picture">
            <dt><i class="Hui-iconfont">&#xe622;</i> 消息管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
            <dd>
                <ul>
                    <li><a data-href="{{ url('groupList')}}" data-title="群组列表" href="javascript:void(0)">广播群组列表</a></li>
                    <li><a data-href="{{ url('pushToMore')}}" data-title="用户群发" href="javascript:void(0)">消息广播</a></li>
                    <li><a data-href="{{ url('pushToOne')}}" data-title="点对点发送" href="javascript:void(0)">消息私信</a></li>
                </ul>
            </dd>
        </dl>

        <dl id="menu-picture">
            <dt><i class="Hui-iconfont">&#xe61a;</i> 位置大数据分析<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
            <dd>
                <ul>
                    <li><a data-href="{{ url('nameSelect')}}" data-title="精准位置评估" href="javascript:void(0)">精准位置评估</a></li>
                    <li><a data-href="{{ url('areaPerception')}}" data-title="区域感知" href="javascript:void(0)">区域感知示范系统</a></li>
                </ul>
            </dd>
        </dl>

    </div>
</aside>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>
<section class="Hui-article-box">
    <div id="Hui-tabNav" class="Hui-tabNav hidden-xs">
        <div class="Hui-tabNav-wp">
            <ul id="min_title_list" class="acrossTab cl">
                <li class="active">
                    <span title="我的桌面" data-href="{{ url('welcome') }}">我的桌面</span>
                    <em></em></li>
            </ul>
        </div>
        <div class="Hui-tabNav-more btn-group"><a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d4;</i></a><a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d7;</i></a></div>
    </div>
    <div id="iframe_box" class="Hui-article">
        <div class="show_iframe">
            <div style="display:none" class="loading"></div>
            <iframe scrolling="yes" frameborder="0" src="{{ url('welcome') }}"></iframe>
        </div>
    </div>
</section>

<div class="contextMenu" id="Huiadminmenu">
    <ul>
        <li id="closethis">关闭当前 </li>
        <li id="closeall">关闭全部 </li>
    </ul>
</div>
<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="/lib/Hui/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/lib/Hui/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/lib/Hui/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="/lib/Hui/static/h-ui.admin/js/H-ui.admin.js"></script> <!--/_footer 作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="/lib/Hui/lib/jquery.contextmenu/jquery.contextmenu.r2.js"></script>
</body>
</html>