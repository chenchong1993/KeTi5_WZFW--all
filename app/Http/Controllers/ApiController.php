<?php
/**
 * Created by PhpStorm.
 * TerminalUser: Administrator
 * Date: 2018/9/23
 * Time: 10:42
 */

namespace App\Http\Controllers;
use App\Bluetooth;
use App\HeatMapData;
use App\Obs;
use App\Past_Locations;
use App\Sensor;
use App\TerminalUser;
use App\Wifi;
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
    public function apiUserAdd(Request $request)
    {


        //控制器验证，如果通过继续往下执行，如果没通过抛出异常返回当前视图。
        if ($request->isMethod('POST')){
            $this->validate($request,[
                'username'=>'required',
                'password'=>'required',
                'email'   =>'required',
                'sex'     =>'required',
                'phone'     =>'required',
            ],[
                'required'=>':attribute 为必填项'
            ],[
                'username'=>'用户名',
                'password'=>'密码',
                'email'   =>'email',
                'sex'     =>'性别',
                'phone'     =>'手机号',
            ]);
        }

//从表单视图传过来的输入信息
        $username = $request->input('username');
        $password = $request->input('password');
        $email    = $request->input('email');
        $sex      = $request->input('sex');
        $mobile      = $request->input('phone');
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
                echo "未知错误";
            }else{
                echo  "用户名已被占用";
            }

        }else{
            $uid = substr($response, -19, 17);
            $users = new TerminalUser();
            $users->uid = $uid;
            $users->name = $username;
            $users->password = $password;
            $users->sex = $sex;
            $users->email = $email;
            $users->phone = $mobile;
            $users->status = "0";
            if ($users->save()){
                echo "添加成功";
//                return redirect('userList')->with('success','添加成功');
            }else{
                echo "数据库存储错误";
                return redirect('userAdd')->with('error','数据库存储错误');
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

        $uid = rq('uid');
        $username = rq('username');
        $password = rq('password');
        $phone = rq('phone');
        $email = rq('email');
        $sex = rq('sex');
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
            'x' => '',
            'y' => '',
            'lng' => 'required',
            'lat' => 'required',
            'floor' => 'required',
            'orien' => '',
            'location_method' => 'required'
//            location_method = 0 指纹
//            location_method = 1 混合
//            location_method = 2 视觉
        ]);

        if ($validator->fails())
            return err(1, $validator->messages());

        $uid = rq('uid');
        $x = rq('x');
        $y = rq('y');
        $lng = rq('lng');
        $lat = rq('lat');
        $floor = rq('floor');
        $orien = rq('orien');
        $location_method = rq('location_method');

        $users =RtCoo::where('uid',$uid)->first();
        if ($users){
//            更新
            $users->x = $x;
            $users->y = $y;
            $users->lng = $lng;
            $users->lat = $lat;
            $users->floor = $floor;
            $users->orien = $orien;
            $users->location_method = $location_method;
            $users->save();

        }
        else{
            //插入
            $users = new RtCoo();
            $users->uid = $uid;
            $users->x = $x;
            $users->y = $y;
            $users->lng = $lng;
            $users->lat = $lat;
            $users->floor = $floor;
            $users->orien = $orien;
            $users->location_method = $location_method;
            $users->save();

        }

        $userLocation = new Coo();
        $userLocation->uid = $uid;
        $userLocation->x = $x;
        $userLocation->y = $y;
        $userLocation->lng = $lng;
        $userLocation->lat = $lat;
        $userLocation->floor = $floor;
        $userLocation->orien = $orien;
        $users->location_method = $location_method;
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
     * 终端根据用户名获取UID接口
     */
    public function apiGetUid()
    {
        $validator = Validator::make(rq(), [
            'username' => 'required|string',
        ]);

        if ($validator->fails())
            return err(1, $validator->messages());

        $user_name = rq('username');

        $user = TerminalUser::where("username" ,'like', '%'.$user_name.'%')->get();
        if ($user->isEmpty()){
            return  err(1, $validator->messages());
        }
        else{
            return $user[0]->uid;
        }

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
        return suc($users);
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
            'x' => '',
            'y' => '',
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

        $wifiData = new Wifi();
        $bluData = new  Bluetooth();
        $sensorData = new Sensor();
//wifi表上传
        $wifiData->uid=$uid;
        $wifiData->lng=$lng;
        $wifiData->lat=$lat;
        $wifiData->x=$x;
        $wifiData->y=$y;
        $wifiData->floor=$floor;
        $wifiData->orien=$orien;
        $wifiData->wifi = $wifi;
//蓝牙表上传
        $bluData->uid=$uid;
        $bluData->lng=$lng;
        $bluData->lat=$lat;
        $bluData->x=$x;
        $bluData->y=$y;
        $bluData->floor=$floor;
        $bluData->orien=$orien;
        $bluData->blue_tooth = $blue_tooth;

//传感器表上传

        $sensorData->uid=$uid;
        $sensorData->lng=$lng;
        $sensorData->lat=$lat;
        $sensorData->x=$x;
        $sensorData->y=$y;
        $sensorData->floor=$floor;
        $sensorData->orien=$orien;
        $sensorData->sensor = $sensor;

        $wifiData->save();
        $bluData->save();
        $sensorData->save();
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
}