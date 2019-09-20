<?php

namespace App\Http\Controllers;

use App\Group;
use App\Past_Locations;
use App\Video;
use App\TerminalUser;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;

class PageController extends Controller
{
    /**
     * 测试
     */
    public function welcome()
    {
        return view('welcome');
    }

    /**
     * 首页
     */
    public function index()
    {
        return view('index');
    }
    //----------------------------------------------------------------用户管理模块---------------------------------------------------
    /**
     * 用户列表
     */
    public function userList()
    {
        $user = TerminalUser::all();
        return view('user.list',['users' => $user]);
    }
    /**
     *用户在线列表
     */
    public function  userOnlineList()
    {
        $user = TerminalUser::where('status' ,'=', '1')->get();
        if ($user->isEmpty()){
            return redirect('userOnlineList')->with('error','查询结果不存在');
        }
        else{
            return view('user.online',['users' => $user]);
        }
    }
    /**
     * 添加用户
     */
    public function userAdd()
    {
        return view('user.add');
    }
    /**
     * 更新用户资料
     */
    public function userUpdate()
    {
        $uid = rq('uid');
        $userInfo = TerminalUser::where("uid" ,'=', $uid)->get();
        return view('user.update',['userInfo'=>$userInfo]);
    }
   //-----------------------------------------------------------------------331地图模块----------------------------------------------
    /**
     * 331用户实时位置地图
     */
    public function normalMap331()
    {
        return view('map.normalmap331');
    }
    /**
     * 331历史轨迹图
     */
    public function userTrail331()
    {

        $uid = rq('uid');
        $startTime = rq('startTime');//"2018-10-22 11:36:07";//rq('startTime');
        $endTime = rq('endTime');//"2018-10-22 11:38:19";//rq('endTime');
        if ($startTime== '' or $endTime == ''){
            return '输入时间段为空';
        }
        $userPositionList = Past_Locations::where('uid' ,'=', $uid)->where('created_at', '>=', $startTime)->where('created_at', '<=', $endTime)->get();
        if ($userPositionList->isEmpty()){
            return '输入有误或该时间段内没有数据';
        }
        return view('map.userTrail331',['userPositionLists' => $userPositionList]);
    }
    /**
     * 331实时轨迹
     */
    public function userRtTrail331()
    {
        $uid = rq('uid');
        $userPositionList = TerminalUser::where('uid' ,'=', $uid)->get();
//        echo $userPositionList;
        return view('map.userRtTrail331',['userPositionLists' => $userPositionList]);
    }
    /**
     * 331人口分布热力图
     */
    public function peopleIn331()
    {
        return view('heatmap.peopleIn331');
    }
    //-----------------------------------------------------------------------C7地图模块-----------------------------------------------
    /**
     * C7用户实时位置地图
     */
    public function normalMapC7()
    {
        return view('map.normalmapC7');
    }
    /**
     * C7历史轨迹
     */
    public function userTrailC7()
    {
        $uid = rq('uid');
        $startTime = rq('startTime');//"2018-10-22 11:36:07";//rq('startTime');
        $endTime = rq('endTime');//"2018-10-22 11:38:19";//rq('endTime');
        if ($startTime== '' or $endTime == ''){
            return '输入时间段为空';
        }
        $userPositionList = Past_Locations::where('uid' ,'=', $uid)->where('created_at', '>=', $startTime)->where('created_at', '<=', $endTime)->get();
        if ($userPositionList->isEmpty()){
            return '输入有误或该时间段内没有数据';
        }
//        echo $userPositionList;
        return view('map.userTrailC7',['userPositionLists' => $userPositionList]);
    }
    /**
     * C7实时轨迹
     */
    public function userRtTrailC7()
    {
        $uid = rq('uid');
        $userPositionList = TerminalUser::where('uid' ,'=', $uid)->get();
        return view('map.userRtTrailC7',['userPositionLists' => $userPositionList]);
    }
    //---------------------------------------------------------------------奥莱地图模块------------------------------------------------
    /**
     * 奥特莱斯用户实时位置地图
     */
    public function normalMapATLS()
    {
        return view('map.normalmapATLS');
    }
    /**
     * 奥特莱斯实时轨迹
     */
    public function userRtTrailATLS()
    {
        $uid = rq('uid');
        $userPositionList = TerminalUser::where('uid' ,'=', $uid)->get();
//        return view('map.normalmapATLS');
        return view('map.userRtTrailATLS',['userPositionLists' => $userPositionList]);
    }
    /**
     * 奥特莱斯历史轨迹
     */
    public function userTrailATLS()
    {
        $uid = rq('uid');
        $startTime = rq('startTime');//"2018-10-22 11:36:07";//rq('startTime');
        $endTime = rq('endTime');//"2018-10-22 11:38:19";//rq('endTime');
        if ($startTime== '' or $endTime == ''){
            return '输入时间段为空';
        }
        $userPositionList = Past_Locations::where('uid' ,'=', $uid)->where('created_at', '>=', $startTime)->where('created_at', '<=', $endTime)->get();
        if ($userPositionList->isEmpty()){
            return '输入有误或该时间段内没有数据';
        }
//        echo $userPositionList;
        return view('map.userTrailATLS',['userPositionLists' => $userPositionList]);
    }
    //---------------------------------------------------------------------其他地图模块------------------------------------------------
    /**
     * 电子围栏地图DEMO
     */
    public function electricFenceDemo(){
        return view('map.electricFenceDemo');
    }
    /**
     * 路网图
     */
    public function routeMap()
    {
        return view('map.routemap');
    }
    /**
     * 热力图
     */
    public function heatMap()
    {
        return view('map.heatmap');
    }
    /**
     * 石家庄桥西区人口分布热力图
     */
    public function peopleHeatMap()
    {
        return view('heatmap.peopleHeatMap');
    }
    /**
     * wifi信号强度热力图
     */
    public function wifiSignalHeatMap()
    {
        return view('heatmap.wifiSignalHeatMap');
    }
    /**
     * 蓝牙信号强度热力图
     */
    public function bluSignalHeatMap()
    {
        return view('heatmap.bluSignalHeatMap');
    }
    /**
     * GDOP热力图
     */
    public function gdopHeatMap(){
        return view('heatmap.gdopHeatMap');
    }

    /**
     * hdop，pdop,vdop,rss
     */
    public function hdopHeatMap(){
        return view('heatmap.hdopHeatMap');
    }
    public function pdopHeatMap(){
        return view('heatmap.pdopHeatMap');
    }
    public function vdopHeatMap(){
        return view('heatmap.vdopHeatMap');
    }
    public function rssHeatMap(){
        return view('heatmap.rssHeatMap');
    }


    /**
     * 测试轨迹
     */
    public function test()
    {
        return view('test.test');
    }
    //-----------------------------------------------------------------------------查询模块-----------------------------------------
    /**
     * 名称查询
     */
    public function nameSearch()
    {
        return view('search.nameSearch');
    }

    /**
     * 扩展查询
     */
    public function extentSearch()
    {
        return view('search.extentSearch');
    }
    //--------------------------------------------------------------------推送模块---------------------------------------------------
    /**
     * 云推送私信
     */
    public function pushToOne()
    {
        $user = TerminalUser::where('status' ,'=', '1')->get();
//        $user = TerminalUser::all();
        if ($user->isEmpty()){
            return redirect('pushToOne')->with('error','当前无用户在线');
        }
        else{
            return view('push.one2one',['users' => $user]);
        }
    }

    /**
     *云推送群发
     */
    public function pushToMore()
    {
        $group = Group::all();
        return view('push.one2more',['groups' => $group]);

    }
}
