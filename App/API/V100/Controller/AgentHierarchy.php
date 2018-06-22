<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 2018/2/21
 * Time: 16:29
 */
namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\DB\DBQ;
use Core\Lib;
use Core\Extend\Redis;

class AgentHierarchy extends Controller
{
    public $m;
    public $headers;
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->headers = Lib::getAllHeaders();
        $model = "\\App\API\\".$this->headers['VERSION']."\\Model\\AgentHierarchy";
        $this->m = new $model;
    }
    public function index(){
        $agent_id = $this->headers['UID'];
        $condition['AND']['A.pid']=$agent_id;
        $pageArr = Lib::setPagePars ();
        $pageArr['numPerPage'] = empty(Lib::post('numPerPage'))  ?8:Lib::post('numPerPage');
        $datalist = $this->m->getList($pageArr,$condition);

        foreach($datalist['list'] as $k => $v){
            $datalist['list'][$k]['create_time_str']= Lib::uDate('Y-m-d H:i',$v['create_time']);

        }
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '获取我的代理成功',
            'data' => $datalist
        ];
        Lib::outputJson($data);
    }

    public function myUser(){
        $agent_id = $this->headers['UID'];
        $condition['AND']['U.agent_id']  = $agent_id;
        $pageArr = Lib::setPagePars ();
        $pageArr['numPerPage'] = empty(Lib::post('numPerPage'))?8:Lib::post('numPerPage');
        $datalist = $this->m->getUserList($pageArr,$condition);
        foreach($datalist['list'] as $k => $v){
            $datalist['list'][$k]['create_time_str'] = Lib::uDate('Y-m-d H:i',$v['create_time']);

        }
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '获取我的用户成功',
            'data' => $datalist
        ];
        Lib::outputJson($data);
    }

    public function getMyProfit(){
        $agent_id = $this->headers['UID'];
        $dataSum = $this->m->getProfit($agent_id);
        $dataSum=$dataSum?Lib::formatMoney($dataSum * 100 / 100, 2):'0.00';
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '获取我的总分润成功',
            'data' => $dataSum
        ];
        Lib::outputJson($data);
    }


    public function tradeInviteCode(){
        $uid = $this->headers['UID'];
        $agent_id = Lib::post('agent_id');
        if(!$agent_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'agent_id不能为空',
            ];
            Lib::outputJson($data);
        }
        //4.28获取前台传来的邀请码id

        $startId =  Lib::post("startId");
        $endId  = Lib::post('endId');

        if(empty($startId) || empty($endId) || $endId < $endId || !is_numeric($startId) || !is_numeric($endId) || $startId < 0 || $endId < 0){
            [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '请输入合法的邀请码',
            ];
            Lib::outputJson($data);
        }
        $inviteCode = $this->m->getInviteCode($startId,$endId,$uid);

        if( !$inviteCode ) {
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '邀请码ID错误 请查看输入ID',
            ];
            Lib::outputJson($data);
        }
        $inviteCodeId = array_column($inviteCode,'id');

        $num = count($inviteCodeId);


        if(!$num){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '邀请码数量不能为空',
            ];
            Lib::outputJson($data);
        }
        if($num > 100) {
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '最多下发100条邀请码',
            ];
            Lib::outputJson($data);
        }

//        $code = Lib::post('code');
//        if(!$code){
//            $data = [
//                'status' => 'fail',
//                'code' => 10011,
//                'msg' => '手机验证码不能为空',
//            ];
//            Lib::outputJson($data);
//        }

//         $app_id = $this->headers['APPID'];
//        $app_id = Lib::request('appid');
//        $row = $this->m->getUser($uid);
//        $mobile = $row['mobile'];
//        $redis = Redis::instance('msg');
//        $mobile_redis = $redis->zRangeByScore($app_id.'_code',$mobile,$mobile,['withscores'=>true,'limit'=>[0,10]]);

//        $verifyCode = '';
//        if (!empty($mobile_redis)) {
//            foreach ($mobile_redis as $k => $v) {
//                $row_redis = null;
//                $row_redis = json_decode($k,true);
//                if ($row_redis['code'] == $code &&  $row_redis['mobile'] == $mobile && $row_redis['code_type'] == 7) {
//                    $verifyCode = $row_redis;
//                    break;
//                }
//            }
//        }
//        if(!$verifyCode){
//            $data = [
//                'status' => 'fail',
//                'code' => 10011,
//                'msg' => '手机验证码错误',
//            ];
//            Lib::outputJson($data);
//        }

        $result = $this->m->editTrade($uid,$agent_id,$inviteCodeId,$num);
        if($result){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '下发成功'.$num.'个邀请码',
                'data' => $result
            ];
            Lib::outputJson($data);
        }else{
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '下发邀请码失败',
                'data' => $result
            ];
            Lib::outputJson($data);
        }
    }

    public function add(){
        $agent_id = $this->headers['UID'];
        if(!$agent_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '代理UID不能为空',
            ];
            Lib::outputJson($data);
        }

        //查询出此代理的级别
        $level = $this->m->getLevel($agent_id);

        if($level > 16) {
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '您的代理级别为16级不能发展下级代理',
            ];
            Lib::outputJson($data);
        }
        //获取前台传来的邀请码
        $startId =  Lib::post("startId");
        $endId  = Lib::post('endId');

        if(empty($startId) || empty($endId) || $endId < $endId || !floor($startId)==$startId || !floor($endId)==$endId || $startId < 0 || $endId < 0){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '请输入合法的邀请码',
            ];
            Lib::outputJson($data);
        }
        $inviteCode = $this->m->getInviteCode($startId,$endId,$agent_id);
        if( !$inviteCode ) {
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '邀请码ID错误 请查看输入ID',
            ];
            Lib::outputJson($data);
        }
        $inviteCodeId = array_column($inviteCode,'id');

        $num = count($inviteCodeId);


        if($num > 100) {
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => "一次最多下发100条邀请码",
            ];
            Lib::outputJson($data);
        }
        if(!$num){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => "邀请码数量不能为空",
            ];
            Lib::outputJson($data);
        }
        if($num >= 1){
            $codesum = $this->m->getCodes($agent_id,$inviteCodeId);
            if($codesum < $num){
                $data = [
                    'status' => 'fail',
                    'code' => 10011,
                    'msg' => '你的邀请码不够了',
                ];
                Lib::outputJson($data);
            }
        }


        $nickname = Lib::post('nickname');
        if(!$nickname){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'nickname不能为空',
            ];
            Lib::outputJson($data);
        }
        $password = Lib::post('password');
        if(!$password){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'password不能为空',
            ];
            Lib::outputJson($data);
        }


        $rate = intval(Lib::post('rate'));
        if( $rate<0 || $rate>99 ){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '还款分润比例为1-99的整数',
            ];
            Lib::outputJson($data);
        }
//        if($rate < 0){
//            $data = [
//                'status' => 'fail',
//                'code' => 10011,
//                'msg' => '分润比例不能为空',
//            ];
//            Lib::outputJson($data);
//        }
        $skrate = intval(Lib::post('skrate'));
        if( $skrate<0 || $skrate>99){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '收款分润比例为1-99的整数',
            ];
            Lib::outputJson($data);
        }

//        if($skrate < 0){
//            $data = [
//                'status' => 'fail',
//                'code' => 10011,
//                'msg' => '收款分润比例不能为空',
//            ];
//            Lib::outputJson($data);
//        }
        $mobile = Lib::post('mobile');
        if(!$mobile){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '手机号码不能为空',
            ];
            Lib::outputJson($data);
        }
        //验证用户验证码
        $baseModel= "\\App\API\\".$this->headers['VERSION']."\\Model\\Base";
        $verify = new $baseModel();
        $verifyCode = Lib::post('code');
        $check_code_validity = $verify->checkCodeValidity($mobile,$verifyCode,Lib::request('appid'),true,9);
        if ($check_code_validity['status'] != 'success') {
            Lib::outputJson($check_code_validity);
        }

        $insertData = [
            'mobile' => $mobile,
            'nickname' => $nickname,
            'password' => Lib::compilePassword($password),
            'pid' => $agent_id,
            'rate' => $rate,
            'skrate'=>$skrate,
            'level' => 1,
            'create_time' => Lib::getMs(),
        ];
        $rs = $this->m->getUser($agent_id);
        if($rs['rate'] < $rate){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '还款分润比例不能大于当前代理',
            ];
            Lib::outputJson($data);
        }

        if($rs['skrate'] < $skrate){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '收款分润比例不能大于当前代理',
            ];
            Lib::outputJson($data);
        }

        $r = $this->m->getUserExists($mobile);
        if($r){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '手机号码已经被使用',
            ];
            Lib::outputJson($data);
        }

        $ret = $this->m->addAgent($insertData,$num,$codesum,$inviteCodeId);
        if($ret){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '添加代理成功 下发了'.$num."个邀请码",
                'data' => $ret
            ];
            Lib::outputJson($data);
        }else{
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '添加失败',
                'data' => $ret
            ];
            Lib::outputJson($data);
        }
    }

    public function setRate(){
        $uid = $this->headers['UID'];
        $rate = intval(Lib::post('rate'));
        if( $rate<0||$rate>99){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '还款分润只能为1-99的整数',
            ];
            Lib::outputJson($data);
        }

        $agent_id = Lib::post('agent_id');
        if(!$agent_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'agent_id不能为空',
            ];
            Lib::outputJson($data);
        }

        $code = Lib::post('code');
        if(!$code){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '手机验证码不能为空',
            ];
            Lib::outputJson($data);
        }
//         $app_id = $this->headers['APPID'];
        $app_id = Lib::request('appid');
        $row = $this->m->getUser($uid);
        $mobile = $row['mobile'];
        $redis = Redis::instance('msg');
        $mobile_redis = $redis->zRangeByScore($app_id.'_code',$mobile,$mobile,['withscores'=>true,'limit'=>[0,1000]]);

        $verifyCode = '';
        if (!empty($mobile_redis)) {
            foreach ($mobile_redis as $k => $v) {
                $row_redis = null;
                $row_redis = json_decode($k,true);
                if ($row_redis['code'] == $code &&  $row_redis['mobile'] == $mobile && $row_redis['code_type'] == 6) {
                    $verifyCode = $row_redis;
                    break;
                }
            }
        }
        if(!$verifyCode){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '手机验证码错误',
            ];
            Lib::outputJson($data);
        }
        if($row['rate'] < $rate){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '还款分润比例不能大于当前代理',
            ];
            Lib::outputJson($data);
        }
        //查询出此代理下级代理最大的分润比例
        $maxRate  = $this->m->maxRate($agent_id);
        if( $maxRate > $rate ) {
            $data = [
                'status' => 'fail',
                'code' => 1000,
                'msg' => '不能小于该代理下级的最大还款分润比例',
            ];
            Lib::outputJson($data);
        }

        $ret = $this->m->editAgentRate($rate,$agent_id);
        if($ret >= 0){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '修改分润比例成功',
                'data' => $ret
            ];
            Lib::outputJson($data);
        }else{
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '修改分润比例失败',
                'data' => $ret
            ];
            Lib::outputJson($data);
        }
    }

    public function setSkRate(){
        $uid = $this->headers['UID'];
        $skrate = intval(Lib::post('skrate'));

        if( $skrate<0 || $skrate>99){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '收款分润比例',
            ];
            Lib::outputJson($data);
        }

//        if($skrate < 0){
//            $data = [
//                'status' => 'fail',
//                'code' => 10011,
//                'msg' => '收款分润比例不能为空',
//            ];
//            Lib::outputJson($data);
//        }

        $agent_id = Lib::post('agent_id');
        if(!$agent_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'agent_id不能为空',
            ];
            Lib::outputJson($data);
        }

        $code = Lib::post('code');
        if(!$code){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '手机验证码不能为空',
            ];
            Lib::outputJson($data);
        }

//         $app_id = $this->headers['APPID'];
        $app_id = Lib::request('appid');
        $row = $this->m->getUser($uid);
        $mobile = $row['mobile'];
        $redis = Redis::instance('msg');
        $mobile_redis = $redis->zRangeByScore($app_id.'_code',$mobile,$mobile,['withscores'=>true,'limit'=>[0,1000]]);
        /*
        $data = [
            'status' => 'fail',
            'code' => 10011,
            'msg' => '下发邀请码失败',
            'data' => $mobile,
            'appId' => $app_id,
            'redis' => $mobile_redis
        ];
        Lib::outputJson($data);
        //var_dump($mobile_redis);
        */

        $verifyCode = '';
        if (!empty($mobile_redis)) {
            foreach ($mobile_redis as $k => $v) {
                $row_redis = null;
                $row_redis = json_decode($k,true);
                if ($row_redis['code'] == $code &&  $row_redis['mobile'] == $mobile && $row_redis['code_type'] == 8) {
                    $verifyCode = $row_redis;
                    break;
                }
            }
        }
        if(!$verifyCode){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '手机验证码错误',
            ];
            Lib::outputJson($data);
        }

        if($row['skrate'] < $skrate){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '收款分润比例不能大于当前代理',
            ];
            Lib::outputJson($data);
        }
        // 查找出当前代理的下级代理的最大还款分润

        //查询出此代理下级代理最大的分润比例
        $maxSkrate  = $this->m->maxSkRate($agent_id);
        if( $maxSkrate > $skrate ) {
            $data = [
                'status' => 'fail',
                'code' => 1000,
                'msg' => '不能小于该代理下级的最大收款分润比例',
            ];
            Lib::outputJson($data);
        }

        $ret = $this->m->editAgentSkRate($skrate,$agent_id);
        if($ret >= 0){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '修改分润比例成功',
                'data' => $ret
            ];
            Lib::outputJson($data);
        }else{
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '修改分润比例失败',
                'data' => $ret
            ];
            Lib::outputJson($data);
        }
    }



    public function addNum(){
        $agent_id = $this->headers['UID'];
        if(!$agent_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '代理UID不能为空',
            ];
            Lib::outputJson($data);
        }

        //查询出此代理的级别
        $level = $this->m->getLevel($agent_id);

        if($level > 16) {
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '您的代理级别为16级不能发展下级代理',
            ];
            Lib::outputJson($data);
        }

        $num = Lib::post('num');
        if($num > 100) {
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => "一次最多下发100条邀请码",
            ];
            Lib::outputJson($data);
        }
        if(!$num){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => "邀请码数量不能为空",
            ];
            Lib::outputJson($data);
        }
        if($num >= 1){
            $codesum = $this->m->getCodeSums($agent_id);
            if($codesum < $num){
                $data = [
                    'status' => 'fail',
                    'code' => 10011,
                    'msg' => '你的邀请码不够了',
                ];
                Lib::outputJson($data);
            }
        }


        $nickname = Lib::post('nickname');
        if(!$nickname){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'nickname不能为空',
            ];
            Lib::outputJson($data);
        }
        $password = Lib::post('password');
        if(!$password){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'password不能为空',
            ];
            Lib::outputJson($data);
        }


        $rate = intval(Lib::post('rate'));
        if( $rate<0 || $rate>99 ){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '还款分润比例为1-99的整数',
            ];
            Lib::outputJson($data);
        }
//        if($rate < 0){
//            $data = [
//                'status' => 'fail',
//                'code' => 10011,
//                'msg' => '分润比例不能为空',
//            ];
//            Lib::outputJson($data);
//        }
        $skrate = intval(Lib::post('skrate'));
        if( $skrate<0 || $skrate>99){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '收款分润比例为1-99的整数',
            ];
            Lib::outputJson($data);
        }

//        if($skrate < 0){
//            $data = [
//                'status' => 'fail',
//                'code' => 10011,
//                'msg' => '收款分润比例不能为空',
//            ];
//            Lib::outputJson($data);
//        }
        $mobile = Lib::post('mobile');
        if(!$mobile){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '手机号码不能为空',
            ];
            Lib::outputJson($data);
        }
        //验证用户验证码
        $baseModel= "\\App\API\\".$this->headers['VERSION']."\\Model\\Base";
        $verify = new $baseModel();
        $verifyCode = Lib::post('code');
        $check_code_validity = $verify->checkCodeValidity($mobile,$verifyCode,Lib::request('appid'),true,9);
        if ($check_code_validity['status'] != 'success') {
            Lib::outputJson($check_code_validity);
        }

        $insertData = [
            'mobile' => $mobile,
            'nickname' => $nickname,
            'password' => Lib::compilePassword($password),
            'pid' => $agent_id,
            'rate' => $rate,
            'skrate'=>$skrate,
            'level' => 1,
            'create_time' => Lib::getMs(),
        ];
        $rs = $this->m->getUser($agent_id);
        if($rs['rate'] < $rate){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '还款分润比例不能大于当前代理',
            ];
            Lib::outputJson($data);
        }

        if($rs['skrate'] < $skrate){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '收款分润比例不能大于当前代理',
            ];
            Lib::outputJson($data);
        }

        $r = $this->m->getUserExists($mobile);
        if($r){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '手机号码已经被使用',
            ];
            Lib::outputJson($data);
        }

        $ret = $this->m->addAgentNum($insertData,$num,$codesum);
        if($ret){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '添加代理成功',
                'data' => $ret
            ];
            Lib::outputJson($data);
        }else{
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '添加失败',
                'data' => $ret
            ];
            Lib::outputJson($data);
        }
    }



    public function tradeInviteCodeNum(){
        $uid = $this->headers['UID'];
        $agent_id = Lib::post('agent_id');
        if(!$agent_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'agent_id不能为空',
            ];
            Lib::outputJson($data);
        }

        $num = Lib::post('num');

        if(!$num){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '邀请码数量不能为空',
            ];
            Lib::outputJson($data);
        }
        if($num > 100) {
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '最多下发100条邀请码',
            ];
            Lib::outputJson($data);
        }

//        $code = Lib::post('code');
//        if(!$code){
//            $data = [
//                'status' => 'fail',
//                'code' => 10011,
//                'msg' => '手机验证码不能为空',
//            ];
//            Lib::outputJson($data);
//        }

//         $app_id = $this->headers['APPID'];
//        $app_id = Lib::request('appid');
//        $row = $this->m->getUser($uid);
//        $mobile = $row['mobile'];
//        $redis = Redis::instance('msg');
//        $mobile_redis = $redis->zRangeByScore($app_id.'_code',$mobile,$mobile,['withscores'=>true,'limit'=>[0,10]]);

//        $verifyCode = '';
//        if (!empty($mobile_redis)) {
//            foreach ($mobile_redis as $k => $v) {
//                $row_redis = null;
//                $row_redis = json_decode($k,true);
//                if ($row_redis['code'] == $code &&  $row_redis['mobile'] == $mobile && $row_redis['code_type'] == 7) {
//                    $verifyCode = $row_redis;
//                    break;
//                }
//            }
//        }
//        if(!$verifyCode){
//            $data = [
//                'status' => 'fail',
//                'code' => 10011,
//                'msg' => '手机验证码错误',
//            ];
//            Lib::outputJson($data);
//        }

        $result = $this->m->editTradeNum($uid,$agent_id,$num);
        if($result){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '下发邀请码成功',
                'data' => $result
            ];
            Lib::outputJson($data);
        }else{
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '下发邀请码失败',
                'data' => $result
            ];
            Lib::outputJson($data);
        }
    }



    /**
     *
     * 前端 传开始结束id 获取邀请码数量
     */

        public function getSumInviteCode()
        {

            $agent_id = $this->headers['UID'];
            if(!$agent_id){
                $data = [
                    'status' => 'fail',
                    'code' => 10011,
                    'msg' => '代理UID不能为空',
                ];
                Lib::outputJson($data);
            }

            $startId =  Lib::post("startId");
            $endId  = Lib::post('endId');

            if(empty($startId) || empty($endId) || $endId < $endId || !floor($startId)==$startId || !floor($endId)==$endId || $startId < 0 || $endId < 0){
                $data = [
                    'status' => 'fail',
                    'code' => 10011,
                    'msg' => "请输入合法ID",
                ];
                Lib::outputJson($data);
            }
            $inviteCode = $this->m->getInviteCode($startId,$endId,$agent_id);
            if( !$inviteCode ) {
                $data = [
                    'status' => 'fail',
                    'code' => 10011,
                    'msg' => '邀请码ID错误 请查看输入ID',
                ];
                Lib::outputJson($data);
            }
            $inviteCodeId = array_column($inviteCode,'id');

            $num = count($inviteCodeId);


            if($num > 100) {
                $data = [
                    'status' => 'fail',
                    'code' => 10011,
                    'msg' => "一次最多下发100条邀请码",
                ];
                Lib::outputJson($data);
            }
            if(!$num){
                $data = [
                    'status' => 'fail',
                    'code' => 10011,
                    'msg' => "邀请码数量不能为空",
                ];
                Lib::outputJson($data);
            }
            if($num >= 1){
                $codesum = $this->m->getCodes($agent_id,$inviteCodeId);
                if($codesum < $num){
                    $data = [
                        'status' => 'fail',
                        'code' => 10011,
                        'msg' => '你的邀请码不够了',
                    ];
                    Lib::outputJson($data);
                }
            }
             $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => $num,
            ];
            Lib::outputJson($data);
        }

}