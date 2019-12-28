<?php

function rq($key = null)
{
    return ($key == null) ? \Illuminate\Support\Facades\Request::all() : \Illuminate\Support\Facades\Request::get($key);
}

/**
 * @param null $data
 * @return array 成功返回0
 */
function suc($data = null)
{
    $ram = ['status' => 0];
    if ($data) {
        $ram['data'] = $data;
        return $ram;
    }
    return $ram;
}

/**
 * @param $code
 * @param null $data
 * @return array 失败返回错误码和信息
 */
function err($code, $data = null)
{
    if ($data)
        return ['status' => $code, 'data' => $data];
    return ['status' => $code];
}


Route::group(['middleware' => 'web'], function () {

//    Route::get('test', 'PageController@test');//测试

    Route::get('index', 'PageController@index')->middleware('auth');//主页
    Route::get('welcome', 'PageController@welcome');

    //用户管理
    Route::get('userList','PageController@userList'); //用户列表
    Route::get('userOnlineList','PageController@userOnlineList'); //在线用户列表
    Route::get('userAdd','PageController@userAdd'); //引导增加用户页面的路由
    Route::any('userUpdate','PageController@userUpdate'); //引导修改用户资料页面的路由
    //地图
    Route::any('routeMap','PageController@routeMap'); //路径规划
    Route::any('normalMap331','PageController@normalMap331'); //331用户实时分布
    Route::any('normalMapC7','PageController@normalMapC7'); //C7用户实时分布
    Route::any('normalMapATLS','PageController@normalMapATLS'); //奥特莱斯用户实时分布
    Route::any('userTrail331/','PageController@userTrail331'); //331 历史轨迹
    Route::any('userTrailC7/','PageController@userTrailC7'); //C7 历史轨迹
    Route::any('userTrailATLS/','PageController@userTrailATLS'); //奥特莱斯历史轨迹
    Route::any('userRtTrail331/','PageController@userRtTrail331'); //331 实时轨迹
    Route::any('userRtTrailATLS/','PageController@userRtTrailATLS'); //奥特莱斯实时轨迹
    Route::any('userRtTrailC7/','PageController@userRtTrailC7'); //C7 实时轨迹
    Route::any('electricFenceDemo','PageController@electricFenceDemo'); //331用户实时分布
//    奥莱演示
    Route::get('ATLSmap', 'PageController@ATLSmap');//奥特莱斯地图
    Route::get('ATLSmapF2', 'PageController@ATLSmapF2');//奥特莱斯地图
    Route::get('ATLSTriail', 'PageController@ATLSTriail');//奥特莱斯地图
    //普通查询
    Route::any('nameSearch','PageController@nameSearch'); //名称查询
    Route::any('extentSearch','PageController@extentSearch'); //扩展查询
    //消息推送
    Route::any('pushToOne','PageController@pushToOne'); //私信
    Route::any('pushToMore','PageController@pushToMore'); //群发
    //热力图
    Route::any('wifiSignalHeatMap','PageController@wifiSignalHeatMap'); //wifi信号强度热力图
    Route::any('bluSignalHeatMap','PageController@bluSignalHeatMap'); //蓝牙信号强度热力图
    Route::any('hdopHeatMap','PageController@hdopHeatMap'); //hdop热力图
    Route::any('vdopHeatMap','PageController@vdopHeatMap'); //vdop热力图
    Route::any('pdopHeatMap','PageController@pdopHeatMap'); //pdop热力图
    Route::any('gdopHeatMap','PageController@gdopHeatMap'); //gdop热力图
    Route::any('rssHeatMap','PageController@rssHeatMap'); //rss热力图
    Route::any('peopleHeatMap','PageController@peopleHeatMap'); //桥西区人口分布热力图
    Route::any('peopleIn331','PageController@peopleIn331'); //331人口分布热力图

    //测试路由
    Route::any('test','PageController@test'); //331人口分布热力图

    Route::group(['prefix' => 'api'], function () {

        Route::post('apiTest', 'ApiController@apiTest');//测试
        Route::post('apiUserAdd', 'ApiController@apiUserAdd');//添加用户
        Route::any('apiUserDelete','ApiController@apiUserDelete'); //删除用户
        Route::any('apiUserUpdate','ApiController@apiUserUpdate'); //修改用户资料
        Route::post('apiGetUid', 'ApiController@apiGetUid');//为终端获取用户UID
        Route::post('apiLogin', 'ApiController@apiLogin');//终端手机用户登陆接口
        Route::post('apiLogout', 'ApiController@apiLogout');//终端手机用户登出接口
        Route::post('apiAddTerminalUserLocation', 'ApiController@apiAddTerminalUserLocation');//终端上传用户坐标
        Route::post('apiNameSeach', 'ApiController@apiNameSeach');//名称搜索接口
        Route::get('apiGetAllUserNewLocationList', 'ApiController@apiGetAllUserNewLocationList');//从数据库中获取用户位置信息
        Route::post('apiAddObs', 'ApiController@apiAddObs');//终端观测数据
        Route::any('heatMapData', 'ApiController@heatMapData');//读热力图数据
        Route::any('fileExport/','ApiController@fileExport'); //Excel导出
//        用于奥莱示范
        Route::post('getUsersByName', 'ApiController@getUsersByName');//根据名字模糊查询用户
        Route::post('getUsersByPhone', 'ApiController@getUsersByPhone');//根据手机号查询用户
        Route::post('getUsersByUid', 'ApiController@getUsersByUid');//根据uid查询用户
        Route::post('getCarByName', 'ApiController@getCarByName');//根据名字模糊查询用户
        Route::post('msgTxAdd', 'ApiController@msgTxAdd');//添加发布信息
        Route::post('msgRxAdd', 'ApiController@msgRxAdd');//添加接收信息
//        用户电子围栏
        Route::post('apiInFences', 'ApiController@apiInFences');//电子围栏示例项目，上传坐标
        Route::get('apiGetLocationList', 'ApiController@apiGetLocationList');//电子围栏示例项目，获取坐标
    });

});

Route::get('/', 'PageController@index')->middleware('auth');
Route::get('/home', 'PageController@index')->middleware('auth');


Auth::routes();

Route::get('/home', 'HomeController@index');
