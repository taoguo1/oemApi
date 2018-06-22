<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/2/22
 * Time: 10:29
 */
namespace App\API\V100\Controller;

use Core\Lib;
use Core\DB\DBQ;

class Agent extends BaseAgent{


     protected $aid = null;
    protected $token    = null;
    protected $agentModel = null;

    public function __construct()
    {
        parent::__construct();
        //$this->aid = $this->agentInfo['aid'];
        $this->aid = $this->headerData['UID'];
        $this->token = $this->headerData['TOKEN'];
        $this->app_id = $this->headerData['APPID'];
        $agentModelPath = "\\App\\API\\" . $this->headerData['VERSION'] . "\\Model\\Agent";
        $this->agentModel = new $agentModelPath();
    }


    /**
     * 重置密码
     */
    public function resetPassword()
    {
        $agent_info['mobile'] = Lib::post('mobile');
        $agent_info['code'] = Lib::post('code');
        $agent_info['orgpassword'] = Lib::post('orgpassword');
        $agent_info['password'] = Lib::post('password');
        $agent_info['repassword'] = Lib::post('repassword');

        //重置密码
        $data = $this->agentModel->setPassword($this->aid, $agent_info, $this->agentInfo);
        Lib::outputJson($data);
    }


    /**
     * 注销
     */
    public function logout()
    {
        $data = $this->agentModel->logout($this->aid,$this->token,$this->app_id);
        Lib::outputJson($data);
    }

    /**
     * 添加代理商
     */
    public function addAgent()
    {
        $agent_info['nickname'] = Lib::post('nickname');
        $agent_info['mobile'] = Lib::post('mobile');
        $agent_info['rate'] = intval(Lib::post('rate'));
        $agent_info['skrate'] = intval(Lib::post('skrate'));
        $agent_info['password'] = Lib::post('password');
        $agent_info['invite_code_num'] = Lib::post('invite_code_num');
        //重置密码
        $data = $this->agentModel->addAgent($agent_info, $this->agent_info);
        Lib::outputJson($data);
    }

    /**
     * 获取代理信息
     */
    public function getAgentInfo()

    {
        $data = $this->agentModel->getAgentInfoByAgentId($this->aid);
        if($data){
            $notice=lib::loadFile('Config/Notice.php');
            $data=array_merge($data,$notice);
            Lib::outputJson(array('status' => 'success', 'code' => 10000, 'msg' => '请求成功', 'data' => $data));
        }else{
            Lib::outputJson(array('status' => 'success', 'code' => 10000, 'msg' => '请求成功', 'data' =>'' ));
        }

    }

    /**
     * 设置推送
     */

    public function editPush()
    {
        $is_push = Lib::post('is_push');

        $data = $this->agentModel->editPush($this->aid, $is_push);
        Lib::outputJson($data);
    }
    /**
     * 修改头像
     */
    public function editAvatar()
    {
        $data = $this->agentModel->editAvatar($this->aid, $_FILES);

        Lib::outputJson($data);
    }

    /**
     * 设置支付密码
     */
    public function setPayPassword()
    {
        $agentInfo['pay_password'] = Lib::post('pay_password');

        $data = $this->agentModel->setPayPassword($this->aid, $agentInfo,$this->agentInfo);
        Lib::outputJson($data);
    }

    /**
     * 修改登录密码
     */
    public function editLoginPassword()
    {
        $agent['password'] = Lib::post('password');
        $agent['new_password'] = Lib::post('new_password');
        $agent['re_new_password'] = Lib::post('re_new_password');
        $data = $this->agentModel->editLoginPassword($this->aid, $agent,$this->agentInfo);
        Lib::outputJson($data);
    }

    /**
     * 身份照片正面上传
     */
    public function idcardUploadFace()
    {
        $data = $this->agentModel->idcardUploadFace($_FILES);
        Lib::outputJson($data);
    }

    /**
     * 身份照片背面上传
     */
    public function idcardUploadBack()
    {
        $data = $this->agentModel->idcardUploadBack($_FILES);
        Lib::outputJson($data);
    }

    /**
     * 手持身份照片上传
     */
    public function idcardUpload()
    {
        $data = $this->agentModel->idcardUpload($_FILES);
        Lib::outputJson($data);
    }

    /**
     * 实名认证
     */
    public function identity()
    {
        $identity['real_name'] = Lib::post('real_name');
        $identity['id_card'] = Lib::post('id_card');
        $identity['address'] = Lib::post('address');
        $identity['sex'] = Lib::post('sex');
        $identity['birth'] = Lib::post('birth');
        $identity['idcard_scan_img1'] = Lib::post('idcard_scan_img1');
        $identity['idcard_scan_img2'] = Lib::post('idcard_scan_img2');
        $identity['idcard_scan_img3'] = Lib::post('idcard_scan_img3');
        $appid = Lib::request('appid');
        $data = $this->agentModel->identity($this->aid, $identity,$appid);



        Lib::outputJson($data);
    }


    /**
     * 获取信用卡
     */
    public function getCreditCard(){
        $data = $this->agentModel->getCreditCard($this->aid);
        Lib::outputJson($data);
    }

    /**
     * 获取储蓄卡
     */
    public function getDebitCard(){
        $data = $this->agentModel->getDebitCard($this->aid);
        Lib::outputJson($data);
    }

    /**
     * 获取代理新token
     */
    public function getNewAgentToken()
    {
        $aid = $this->aid;
        $token = $this->headerData['TOKEN'];
        $utype = $this->headerData['UTYPE'];
        $device_id = $this->headerData['DEVICEID'];
        $device_os = $this->headerData['SYSTEMTYPE'];
        // 数据库中验证token是否有效
        $row = DBQ::getRow('token', ['uid', 'token', 'utype', 'create_time'], ['uid' => $aid, 'token' => $token, 'utype' => $utype]);
        if (!empty($row)) {
            //过期TOKEN处理
            $nowTime = Lib::getMs();
            $poorTime = ($nowTime - $row['create_time']) / 1000;
            if ($poorTime >= (3600 * 2)) {
                $token = md5(Lib::getMs() . rand(0, 999999));
                DBQ::upd('token', ['token' => $token, 'create_time' => Lib::getMs()], ['uid' => $aid, 'utype' => $utype]);
                DBQ::add('agent_login_log', ['agent_id' => $aid, 'device_id' => $device_id, 'device_os' => $device_os, 'token' => $token, 'remarks' => '重新颁发TOKEN', 'create_time' => Lib::getMs()]);
                $data = ['status' => 'success', 'code' => 1000, 'msg' => '获取成功','token'=>$token];
            }else{
                $data = ['status' => 'success', 'code' => 1000, 'msg' => 'TOKEN未过期','token'=>$token];
            }
        } else {
            $data = ['status' => 'fail', 'code' => 1000, 'msg' => '无效TOKEN参数'];
        }
        Lib::outputJson($data);
    }

    /**
     * 获取代理实名认证信息
     */
    public function getIsIdCardAuth(){
        $data = $this->agentModel->getAgentInfoByAgentId($this->aid);
        if(!empty($data)){
            $dataExt['aid']             = $data['aid'];
            $dataExt['is_id_card_auth'] = $data['is_id_card_auth'];
            $data = array('status' => 'success', 'code' => 1000, 'msg' => '获取成功', 'data' =>$dataExt);
        }else{
            $data = array('status' => 'fail', 'code' => 1000, 'msg' => '请求失败', 'data' => '');
        }
        Lib::outputJson($data);
    }


    //代理提现判断金额是否在规定范围内
    public function checkMoney(){
        $getAllHeaders         = Lib::getAllHeaders();
        $data['agent_id']       = $getAllHeaders['UID'];
        $data['money']         = Lib::post('money');


        $agentModelPath = "\\App\\API\\" . $this->headerData['VERSION'] . "\\Model\\CardPay";
        $m = new $agentModelPath;
        $result = $m->checkMoneyByAgent($data);
        Lib::outputJson($result);
    }




    /**
     * 代理提现
     */
    public function agentTakeCash()
    {
        $getAllHeaders         = Lib::getAllHeaders();
        $data['user_id']       = $getAllHeaders['UID'];
        $data['card_no']       = Lib::post('card_no');
        $data['money']         = Lib::post('money');
        $data['pay_password']  = Lib::post('pay_password');
        $data['appid']         = Lib::post('appid');
        $agentModelPath = "\\App\\API\\" . $this->headerData['VERSION'] . "\\Model\\CardPay";
        $m = new $agentModelPath;
        $result = $m->agentTakeCash($data);

        //$notice=lib::loadFile('Config/Notice.php');
        //$result=array_merge($result,$notice);

        //Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '请求成功', 'data' => $data));
        Lib::outputJson($result);
    }
    /**
     * 检查修改支付密码的代理信息
     */
    public function checkSetPayPasswordInfo()
    {
        $agent_Info['password'] = Lib::post('password');
        $agent_Info['real_name'] = Lib::post('real_name');
        $agent_Info['id_card'] = Lib::post('id_card');

        $data = $this->agentModel->checkSetPayPasswordInfo($this->aid, $agent_Info,$this->agentInfo);
        Lib::outputJson($data);
    }

}
