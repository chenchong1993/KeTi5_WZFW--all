<?php
/**
 * Created by PhpStorm.
 * TerminalUser: Administrator
 * Date: 2018/9/23
 * Time: 10:42
 */

namespace App\Http\Controllers;
use App\areaPercept;
use App\GridSendInfo;
use App\groupMembers;
use App\HeatMapData;
use App\Obs;
use App\Past_Locations;
use App\sendGroups;
use App\Sensor;
use App\TerminalUser;
use App\TerminalUserCar;
use Couchbase\TerminalUserSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
//use Maatwebsite\Excel\Excel;
use Excel;
use function PHPSTORM_META\type;


class ApiController extends Controller
{
    /**
     * 测试路由
     */
    public function apiTest()
    {
        return 0;
    }
    /**
     * 终端根据用户名获取UID接口
     */
    public function apiGetUid()
    {
        $validator = Validator::make(rq(), [
            'username' => 'required',
        ]);

        if ($validator->fails())
            return err(1, $validator->messages());

        $user_name = rq('username');

        $user = TerminalUser::where("name" ,'like', '%'.$user_name.'%')->get();
        if ($user->isEmpty()){
            return  err(1, $validator->messages());
        }
        else{
            return $user[0]->uid;
        }

    }
    /**
     * [根据UID查询用户列表]
     * @return [type] [description]
     */
    public function getUsersByUid(){
        $validator = Validator::make(rq(), [
            'uid' => 'required',
        ]);

        if ($validator->fails())
            return err(1, $validator->messages());

        $users = TerminalUser::where('uid', rq('uid'))->get();

        return suc(['users'=>$users]);
    }



    /**
     * 用户登陆接口
     */
    public function apiLogin()
    {
        $validator = Validator::make(rq(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails())
            return err(1, $validator->messages());
        $username = rq('username');
        $password = rq('password');
        $uid = $this->apiGetUid($username);
        $userInfo =TerminalUser::where("uid",'=',$uid)->first();
        if ($userInfo){
            if ($userInfo->password == $password){
                $userInfo->status = 1;
                if($userInfo->save()){
                    return $uid;
                }
            }else{
                echo "密码错误";
            }
        }else{
            echo "该用户尚未注册";
        }
    }
    /**
     * 用户登出接口
     */
    public function apiLogout(){
        $validator = Validator::make(rq(), [
            'username' => 'required',
        ]);
        if ($validator->fails())
            return err(1, $validator->messages());
        $username = rq('username');
        $uid = $this->apiGetUid($username);
        $userInfo =TerminalUser::where("uid",'=',$uid)->first();
        $userInfo->status = 0;
        if($userInfo->save()){
            echo "登出成功";
        }
    }
    /**
     * 封装json格式的POST请求
     */
    public function apiPostJson($url,$data)
    {
        header("Content-type:application/json;charset=utf-8");
        //这里需要注意的是这里php会自动对json进行编码，而一些java接口不自动解码情况（中文）
        $json_data = json_encode($data,JSON_UNESCAPED_UNICODE);
//$json_data = json_encode($data);
//curl方式发送请求
        $ch = curl_init();
//设置请求为post
        curl_setopt($ch, CURLOPT_POST, 1);
//请求地址
        curl_setopt($ch, CURLOPT_URL, $url);
//json的数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//显示请求头
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
//请求头定义为json数据
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type:application/json;charset=utf-8',
                'Content-Length: '.strlen($json_data)
            )
        );
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * 用户注册接口
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function apiUserAdd()
    {

        //控制器验证，如果通过继续往下执行，如果没通过抛出异常返回当前视图。
        $validator = Validator::make(rq(), [
            'username' => 'required',
            'password' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'sex' => 'required',
        ]);
        if ($validator->fails())
            return err(1, $validator->messages());

        $username = rq('username');
        $password = rq('password');
        $phone = rq('phone');
        $email = rq('email');
        $sex = rq('sex');
        if ($sex==10) {
            $sex="保密";
        }
        elseif ($sex==0) {
            $sex="男";
        }
        elseif ($sex==1) {
            $sex="女";
        }
//获取uid的过程如下。
//如果接口返回的数据为json，这里需要先定义数据类型为json
        $url = "http://121.28.103.199:5583/service/user/v1/signup?appid=10";
        $data = array('username'=>$username,'password'=>$password,'admin'=>false);
//调用封装的json请求方法
        $response = $this->apiPostJson($url,$data);
//将返回的字符串进行分析
        //1. 验证用户名是否重复
        //2. 截取出uid存入用户数据库
//两种返回结果
        //1. { "result": true, "error": "", "data": 30152179566247939 }
        //2. { "result": false, "error": "用户名已被占用", "data": null }
//判断是否注册成功
        if(strpos($response,"true")==false){
            if (strpos($response,"用户名已被占用")==false){
                return err(1, '未知错误');
            }else{
                return err(1, '用户名已被占用');
            }

        }else{
            $uid = substr($response, -19, 17);
            $users = new TerminalUser();
            $users->uid = $uid;
            $users->name = $username;
            $users->password = $password;
            $users->sex = $sex;
            $users->email = $email;
            $users->phone = $phone;
            $users->status = "0";
            if ($users->save()){
                return err(1,'添加成功');
            }else{
                return err(1, '数据库存储错误');
            }

        }

    }
    /**
     * 修改用户资料接口
     */
    public function apiUserUpdate()
    {
        //控制器验证，如果通过继续往下执行，如果没通过抛出异常返回当前视图。
        $validator = Validator::make(rq(), [
            'username' => 'required',
            'password' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'sex' => 'required',
        ]);
        if ($validator->fails())
            return err(1, $validator->messages());

        $username = rq('username');
        $password = rq('password');
        $phone = rq('phone');
        $email = rq('email');
        $sex = rq('sex');
        $uid = $this->apiGetUid($username);
        $userInfo =TerminalUser::where("uid",'=',$uid)->first();
        $userInfo->name = $username;
        $userInfo->password = $password;
        $userInfo->phone = $phone;
        $userInfo->email = $email;
        $userInfo->sex = $sex;
        $userInfo->save();
        if ($userInfo->save()){
            echo "修改成功";
        }
    }
    /**
     * 删除用户接口
     */
    public function apiUserDelete()
    {
        $uid = rq('uid');
        $userInfo = TerminalUser::where("uid",'=',$uid)->first();;
        if ($userInfo->delete()){
            return 0;
        }else{
            return 1;
        }
    }
    /**
     * 查询结果接口
     */
    public function apiSearchResult(Request $request)
    {
        if ($request->isMethod('POST')){
            $this->validate($request,[
                'content'=>'required',
                'type'=>'required',
            ],[
                'required'=>':attribute 为必填项'
            ],[
                'content'=>'查询内容',
                'type'=>'查询类型',
            ]);
        }

        $content = $request->input('content');
        $type = $request->input('type');
        if ($type==10) {
            $type="username";
        }
        elseif ($type==20) {
            $type="address";
        }
        else{
            $type="物名";
        }

        $user = TerminalUser::where($type ,'like', '%'.$content.'%')->get();
        if ($user->isEmpty()){
            echo '查询结果不存在';
        }
        else{
            return view('search.Result',['users' => $user]);
        }

    }
    /**
     * 添加用户的位置信息 也就是终端用户实时位置上报
     * 1.添加到位置数据库
     * 2.添加或者更新实时位置数据库
     */
    public function apiAddTerminalUserLocation()
    {
        $validator = Validator::make(rq(), [
            'uid' => 'required',
            'x' => 'required',
            'y' => 'required',
            'lng' => 'required',
            'lat' => 'required',
            'floor' => 'required',
            'orien' => '',
            'location_method' => 'required'
//            location_method = 0 指纹
//            location_method = 1 混合
//            location_method = 2 视觉
//            location_method = 3 伪卫星
        ]);

        if ($validator->fails())
            return err(1, $validator->messages());

        $lat331= 38.24773062;
        $lng331=114.34898073;

        $xC7 = 4212828.38;
        $yC7 = 538240.68;

        $xoutlets= 4202883.4;
        $youtlets = 534384.3;

        $uid = rq('uid');
        $x = rq('x');
        $y = rq('y');
        $lng = rq('lng');
        $lat = rq('lat');
        $floor = rq('floor');
        $orien = rq('orien');
        $location_method = rq('location_method');
        $users =TerminalUser::where('uid',$uid)->first();
        if ($users){
            if (sqrt((pow(($lat-$lat331),2)+pow(($lng-$lng331),2)))<0.05){
                $users->address = "331";
            }
//            更新
            if($x>1000000){
                $users->x = $x;
                $users->y = $y;
            }elseif ($x<1000000){
                $users->x = $y;
                $users->y = $x;
            }
            if ($lat<100){
                $users->lng = $lng;
                $users->lat = $lat;
            }
            elseif ($lat>100){
                $users->lng = $lat;
                $users->lat = $lng;
            }
            $users->floor = $floor;
            $users->orien = $orien;
            $users->location_method = $location_method;
            $users->save();

        }
        else{
            //插入
            $users = new TerminalUser();
            $users->uid = $uid;
            if($x>1000000){
                $users->x = $x;
                $users->y = $y;
            }elseif ($x<1000000){
                $users->x = $y;
                $users->y = $x;
            }
            if ($lat<100){
                $users->lng = $lng;
                $users->lat = $lat;
            }
            elseif ($lat>100){
                $users->lng = $lat;
                $users->lat = $lng;
            }
            $users->floor = $floor;
            $users->orien = $orien;
            $users->location_method = $location_method;
            $users->save();

        }

        $userLocation = new Past_Locations();
        $userLocation->uid = $uid;
        if($x>1000000){
            $userLocation->x = $x;
            $userLocation->y = $y;
        }elseif ($x<1000000){
            $userLocation->x = $y;
            $userLocation->y = $x;
        }
        if ($lat<100){
            $userLocation->lng = $lng;
            $userLocation->lat = $lat;
        }
        elseif ($lat>100){
            $userLocation->lng = $lat;
            $userLocation->lat = $lng;
        }
        $userLocation->floor = $floor;
        $userLocation->orien = $orien;
        $userLocation->location_method = $location_method;
        $userLocation->save();
        return suc();
    }
    /**
     * @param $uid
     * @return mixed
     * 根据UID获取用户信息
     */
    public function aipGetTerminalUserInfo($uid)
    {
        $TerminalUserInfo = TerminalUser::where("uid",'=',$uid)->get();
        echo $TerminalUserInfo;
    }

    /**
     * 名称搜索接口
     */
    public function apiNameSeach(){
        $validator = Validator::make(rq(), [
            'searchKeyWord' => 'required|string',
        ]);

        if ($validator->fails())
            return err(1, $validator->messages());

        $content = rq('searchKeyWord');
        $type = rq('searchType');
        if ($type==1) {
            $type="name";
        }
        elseif ($type==2) {
            $type="address";
        }
        elseif ($type==3){
            $type="物名";
        }
        $user = TerminalUser::where($type ,'like', '%'.$content.'%')->get();
        if ($user->isEmpty()){
            echo '查询结果不存在';
        }
        else{
//            echo '0000';
            return suc($user);
        }
    }
    /**
     * 返回所有用户的最新位置信息列表
     */
    public function apiGetAllUserNewLocationList()
    {
        $users = TerminalUser::get();
        return suc(['users'=>$users]);
    }

    /**
     * 观测数据上传接口
     * 上传wifi,蓝牙，传感器，分别存储到不同的表里
     */
    public function apiAddObs()
    {
        $validator = Validator::make(rq(), [
            'uid' => 'required',
            'lng' => 'required',
            'lat' => 'required',
            'x' => 'required',
            'y' => 'required',
            'floor' => 'required',
            'orien' => 'required',
            'wifi' => '',
            'bluetooth'=>'',
            'sensor'=>''
        ]);

        if ($validator->fails())
            return err(1, $validator->messages());

        $uid=rq('uid');
        $lng=rq('lng');
        $lat=rq('lat');
        $x = rq('x');
        $y = rq('y');
        $floor=rq('floor');
        $orien=rq('orien');
        $wifi=rq('wifi');
        $blue_tooth=rq('bluetooth');
        $sensor=rq('sensor');

        $obsData = new Obs();
        $obsData->uid=$uid;
        $obsData->lng=$lng;
        $obsData->lat=$lat;
        $obsData->x=$x;
        $obsData->y=$y;
        $obsData->floor=$floor;
        $obsData->orien=$orien;
        $obsData->wifi = $wifi;
        $obsData->blue_tooth = $blue_tooth;
        $obsData->sensor = $sensor;

        $obsData->save();
        return suc();

    }

    /**
     * 读热力图数据
     */
    public function heatMapData(){

        $validator = Validator::make(rq(), [
            'type' => 'required',
            'floor'=>'required'
        ]);

        if ($validator->fails())
            return err(1, $validator->messages());

        $type = rq('type');
        $floor = rq('floor');
        $heatMapData = HeatMapData::where("type" ,'=', $type)->where("floor" ,'=', $floor)->get();
//        $data = json_decode($heatMapData[0]->data, true);
        $data = $heatMapData[0]->data;
        $dataobj = json_decode($data);

//        return $dataobj;
        return $data;
        var_dump($dataobj);

    }

    /**
     * 保存文件,csv格式
     * 检索一段时间内的位置数据并导出为文件
     */
    public function fileExport(){
        ini_set('memory_limit','500M');
        set_time_limit(0);//设置超时限制为0分钟
//        $cellData = TerminalUser::select('uid','uid','uid')->limit(5)->get()->toArray();

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
        $userPositionList->toArray();
        $cellData[0] = array('参与评估方简称','用户识别码','时间','楼层','X','Y','Z');
        for($i=1;$i<count($userPositionList);$i++){
            $cellData[$i][0] = 'BLH';
            $cellData[$i][1] = $userPositionList[$i]->uid;
            $cellData[$i][2] = $userPositionList[$i]->created_at;
            $cellData[$i][3] = $userPositionList[$i]->floor;
            $cellData[$i][4] = $userPositionList[$i]->lat;
            $cellData[$i][5] = $userPositionList[$i]->lng;
            $cellData[$i][6] = '0';
        }
        Excel::create('位置信息',function($excel) use ($cellData){
            $excel->sheet('location', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('csv');
        die;

    }


    /**
     * [根据名字模糊查询用户列表]
     * @return [type] [description]
     */
    public function getUsersByName(){
        $validator = Validator::make(rq(), [
            'name' => 'required',
            'floor' => 'required'
        ]);

        if ($validator->fails())
            return err(1, $validator->messages());

        $users = TerminalUser::where('name', 'like', '%'.rq('name').'%')->where('floor', '=', rq('floor').'%')->get();

        return suc(['users'=>$users]);
    }


    /**
     * [根据名字模糊查询用户列表]
     * @return [type] [description]
     */
    public function getCarByName(){
        $validator = Validator::make(rq(), [
            'name' => 'required',
        ]);

        if ($validator->fails())
            return err(1, $validator->messages());

        $users = TerminalUserCar::where('name', 'like', '%'.rq('name').'%')->get();

        return suc(['users'=>$users]);
    }
    /**
     * 添加发布信息
     */
    public function msgTxAdd(){

        $validator = Validator::make(rq(), [
            'content' => 'required|max:255',
        ]);

        if ($validator->fails())
            return err(1, $validator->messages());


//        $msg_tx = new MsgTx();
//        $msg_tx->content = rq('content');
//
//        if (rq('uid')) {
//            $terminal_user = TerminalUser::where('uid',rq('uid'))->first();
//            $msg_tx->terminal_user_id = $terminal_user->id;
//        }
//
//        $msg_tx->save();

        return suc();
    }

    /**
     * 添加接收信息
     */
    public function msgRxAdd(){

        $validator = Validator::make(rq(), [
            'content' => 'required|max:255',
            'uid' => 'required'
        ]);

        if ($validator->fails())
            return err(1, $validator->messages());

//        $msg_rx = new MsgRx();
//        $msg_rx->content = rq('content');
//
//        $terminal_user = TerminalUser::where('uid',rq('uid'))->first();
//        $msg_rx->terminal_user_id = $terminal_user->id;
//
//        $msg_rx->save();

        return suc();
    }

    /**
     * 添加群组成员
     */
    public function memberAdd()
    {
        $validator = Validator::make(rq(), [
            'id' => 'required',
            'uid' => 'required',
        ]);

        if ($validator->fails())
            return err(1, $validator->messages());

        $groupID = rq('id');
        $uid = rq('uid');
        $groupINFO = sendGroups::where('id', $groupID)->first();
        $userInfo = TerminalUser::where('uid', $uid)->first();
        $name =  $userInfo->name;
        $f = strpos($groupINFO->member, $uid);
        if ($f === false) {
            if ($groupINFO) {
//            更新
                $groupINFO->member = $groupINFO->member . $uid . ";";
                $groupINFO->memberName = $groupINFO->memberName . $name . ";";
                $groupINFO->save();

            }
            return suc("添加成功");
        } else {
            return suc("该成员已存在");
        }
    }
    /**
     * 获取群组成员列表
     */
    public function memberList(){
        $validator = Validator::make(rq(), [
            'id' => 'required',
        ]);

        if ($validator->fails())
            return err(1, $validator->messages());

        $groupID = rq('id');
        $groupINFO = sendGroups::where('id', $groupID)->first();
//        echo $groupINFO->memberName;
//        echo $groupINFO->member;
        $nameArr = explode(";", $groupINFO->memberName,-1);
        $idArr = explode(";", $groupINFO->member,-1);
        $member = array();
//        $member = new groupMembers();
//        for ($i=0; $i<sizeof($nameArr); $i++) {
//            $member[$i]->name = $nameArr[$i];
//            $member[$i]->id = $idArr[$i];
//
//        }
        return suc(['names'=>$nameArr,'ids'=>$idArr]);
    }

    /**
     * 移除群组成员
     */
    public function memberDel()
    {
        $validator = Validator::make(rq(), [
            'id' => 'required',
            'uid' => 'required',
            'name' => 'required',
        ]);

        if ($validator->fails())
            return err(1, $validator->messages());

        $groupID = rq('id');
        $uid = rq('uid');
        $name = rq('name');
        $groupINFO = sendGroups::where('id', $groupID)->first();
        $f = strpos($groupINFO->member, $uid);
//        return $f;
        if ($f !== false) {
            if ($groupINFO) {
                $groupINFO->member = str_replace($uid.";","",$groupINFO->member);
                $groupINFO->memberName = str_replace($name.";","",$groupINFO->memberName);
                $groupINFO->save();
                return suc("移除成功");
            }
        } else {
            return suc("该成员已移除");
        }
    }
    /**
     * 添加群组
     */
    public function apiAddGroup(){
        $validator = Validator::make(rq(), [
            'groupname' => 'required',
            'admin' => 'required',
        ]);

        if ($validator->fails())
            return err(1, $validator->messages());
        $groupName = rq('groupname');
        $admin = rq('admin');
        $describe = rq('describe');
        $groupInfo = new sendGroups();
        $groupInfo->groupName = $groupName;
        $groupInfo->admin = $admin;
        $groupInfo->describe = $describe;

        if ($groupInfo->save()){
            return suc("新建群组成功");
        }else{
            return suc("新建群组失败");
        }
    }
    /**
     * 解散群组
     */
    public function apiDelGroup()
    {
        $validator = Validator::make(rq(), [
            'id' => 'required',
        ]);

        if ($validator->fails())
            return err(1, $validator->messages());

        $groupID = rq('id');
        $groupINFO = sendGroups::where('id', $groupID)->first();
        if ($groupINFO->delete()){
            return suc("解散成功");;
        }else{
            return suc("解散失败");;
        }


    }
    /**
     * 添加区域位置感知边界范围点集合
     */
    public function apiAddPointList(){
        $validator = Validator::make(rq(), [
            'pointList' => 'required',
        ]);

        if ($validator->fails())
            return err(1, $validator->messages());
        $id = rq('id');
        $describe = rq('describe');
        $pointList = rq('pointList');

        if (areaPercept::where('describe', $describe)->first()||areaPercept::where('pointList', $pointList)->first()){
            return suc("该区域或描述已存在");
        }elseif ($pointList=="[]"){
            return suc("请重新选择边界");
        }else{
            $areaInfo = new areaPercept();
            $areaInfo->pointList = $pointList;
            $areaInfo->describe = $describe;

            if ($areaInfo->save()){
                return suc("感知区域创建成功");
            }else{
                return suc("感知区域创建失败");
            }
        }
    }
    /**
     * 从数据库获取已存在的位置感知区域
     */
    public function apiGetUserAndArea()
    {
        $users = TerminalUser::get();
        $areaInfo = areaPercept::get();
        return suc(['users'=>$users,'areas'=>$areaInfo]);
    }
    /**
     * 删除用户接口
     */
    public function apiAreaDelete()
    {
        $id = rq('id');
        $areaInfo = areaPercept::where("id",'=',$id)->first();;
        if ($areaInfo->delete()){
            return 0;
        }else{
            return 1;
        }
    }
}