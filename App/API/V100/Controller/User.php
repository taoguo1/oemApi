<?php
namespace App\API\V100\Controller;
use Core\Extend\Redis;
use Core\Lib;
use Core\DB\DBQ;


class User extends BaseUser
{ 
    protected $uid          = null;
    protected $userModel   = null;
    protected $token        = null;
    protected $app_id       = null;

    public function __construct(){
        parent::__construct();
        
        $this->uid = $this->headerData['UID'];
        $this->token = $this->headerData['TOKEN'];
//         $this->app_id = $this->headerData['APPID'];
        $this->app_id = Lib::request('appid');
        
        $userModelPath = "\\App\\API\\" . $this->headerData['VERSION'] . "\\Model\\User";
        $billModelPath = "\\App\\API\\" . $this->headerData['VERSION'] . "\\Model\\Bill";
        $this->userModel = new $userModelPath();
        $this->modelBill = new $billModelPath();
 
    }

    /**
     * 注销
     */
    public function logout()
    {
        //$data = $this->userModel->logout($this->uid,$this->token,$this->app_id);
        if (empty($this->uid) || empty($this->token)) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '注销失败'));
        }
        //DBQ::del('token', ['uid' => $uid ,'token'=>$token]);
        //删除redis存储的登录信息
        $redis = Redis::instance('token');
        //$redisObj =  RedisAliMulti::getRedisInstance(REDIS['token'], 0);
        //$redis = $redisObj->getConnect();
        $redis->zRemRangeByScore($this->app_id.'_user_token',$this->uid,$this->uid);
        Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '注销成功'));
    }


    /**
     * 设置支付密码
     */
    public function setPayPassword()
    {

        $userInfo['pay_password'] = Lib::post('pay_password');
        $data = $this->userModel->setPayPassword($this->uid, $userInfo,$this->userInfo);
        Lib::outputJson($data);
    }
    /**
     * 设置推送
     */
    public function editPush()
    {
        $isPush = Lib::post('is_push');

        $data = $this->userModel->editPush($this->uid, $isPush);
        Lib::outputJson($data);
    }

    /**
     * 修改头像
     */
    public function editAvatar()
    {
        $data = $this->userModel->editAvatar($this->uid, $_FILES);

        Lib::outputJson($data);
    }
    /**
     * 获取用户资料
     */
    public function getUserInfo(){
        $data = $this->userModel->getUserInfoByUserId($this->uid);
        $notice=lib::loadFile('Config/Notice.php');
        $rerst=[];
        if(!empty($data) && !empty($notice)){
            $rerst=array_merge($data,$notice);
        }

        Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '请求成功', 'data' => $rerst));
    }

    /**
     * 身份照片正面上传
     */
    public function idcardUploadFace()
    {
        $data = $this->userModel->idcardUploadFace($_FILES);
        Lib::outputJson($data);
    }

    /**
     * 身份照片背面上传
     */
    public function idcardUploadBack()
    {
        $data = $this->userModel->idcardUploadBack($_FILES);
        Lib::outputJson($data);
    }
    /**
     * 手持身份照片上传
     */
    public function idcardUpload()
    {
        $data = $this->userModel->idcardUpload($_FILES);
        Lib::outputJson($data);
    }
    /**
     * 实名认证
     */
    public function identity()
    {
        $identity['real_name'] = Lib::post('real_name');
        $identity['appid'] = Lib::post('appid');
        $identity['id_card'] = Lib::post('id_card');
        $identity['address'] = Lib::post('address');
        $identity['sex'] = Lib::post('sex');
        $identity['birth'] = Lib::post('birth');
        $identity['idcard_scan_img1'] = Lib::post('idcard_scan_img1');
        $identity['idcard_scan_img2'] = Lib::post('idcard_scan_img2');
        $identity['idcard_scan_img3'] = Lib::post('idcard_scan_img3');
        $data = $this->userModel->identity($this->uid, $identity);
        Lib::outputJson($data);
    }

    /**
     * 获取信用卡
     */
    public function getCreditCard(){
        $data = $this->userModel->getCreditCard($this->uid);
        Lib::outputJson($data);
    }

    /**
     * 获取储蓄卡
     */
    public function getDebitCard(){
        $data = $this->userModel->getDebitCard($this->uid);
        Lib::outputJson($data);
    }
    /**
     * 获取储蓄卡和信用卡
     */
    public function getAllCard(){
        $data = $this->userModel->getAllCard($this->uid);
        Lib::outputJson($data);
    }

    /**
     * 获取用户实名认证信息
     */
    public function getIsIdCardAuth(){
        $data = $this->userModel->getUserInfoByUserId($this->uid);
        if(!empty($data)){
            $dataExt['uid']             = $data['uid'];
            $dataExt['is_id_card_auth'] = $data['is_id_card_auth'];
            $data = array('status' => 'success', 'code' => 1000, 'msg' => '获取成功', 'data' =>$dataExt);
        }else{
            $data = array('status' => 'fail', 'code' => 1000, 'msg' => '请求失败', 'data' => '');
        }
        Lib::outputJson($data);
    }
    /**
     * 用户绑定邀请码
     */
    public function userBindInviteCode() {
        $invite_code = trim(Lib::post('invite_code'));
        if (empty($invite_code)) {
            $data = array('status' => 'fail', 'code' => 1000, 'msg' => '邀请码不能为空');
        }
        $row_code = DBQ::getRow('invite_code', '*',['code' => $invite_code]);
        if (empty($row_code)) {
            $data = array('status' => 'fail', 'code' => 1000, 'msg' => '邀请码不存在');
        }
        $ret = $this->userModel->userBindInviteCode($this->uid, $invite_code);
        if ($ret) {
            $data = array('status' => 'success', 'code' => 1000, 'msg' => '绑定成功！');
        } else {
            $data = array('status' => 'fail', 'code' => 1000, 'msg' => '绑定失败！');
        }
        Lib::outputJson($data);
    }





    //用户充值判定金额范围
    public function rechargeCheckMoney(){
        $getAllHeaders         = Lib::getAllHeaders();
        $data['user_id']       = $getAllHeaders['UID'];
        $data['utype']       = $getAllHeaders['UTYPE'];
        $data['money']         = Lib::post('money');
        
        $m = new \App\API\V100\Model\CardPay();
        $result = $m->rechargeCheckMoney($data);
        Lib::outputJson($result);
    } 


    //用户收款判定金额范围
    public function CheckGetMoney(){
        $getAllHeaders         = Lib::getAllHeaders();
        $data['user_id']       = $getAllHeaders['UID'];
        $data['utype']       = $getAllHeaders['UTYPE'];
        $data['money']         = Lib::post('money');
        
        $m = new \App\API\V100\Model\CardPay();
        $result = $m->CheckGetMoney($data);
        Lib::outputJson($result);
    } 
    /**
     * 用户充值
     */

    public function recharge()
    {
        $getAllHeaders         = Lib::getAllHeaders();
        $data['UID']       = $getAllHeaders['UID'];
        $data['card_no']       = Lib::post('card_no');
        $data['money']         = Lib::post('money');
        $data['pay_password']  = Lib::post('pay_password');
        $data['appid']         = Lib::post('appid');
        $m = new \App\API\V100\Model\CardPay();
        $result = $m->userRecharge($data);
        //$notice=lib::loadFile('Config/Notice.php');
        //$result=array_merge($result,$notice);
        Lib::outputJson($result);
    }
    
    
    /**
     * 鉴权消费
     */
    
    public function payauth()
    {
        $getAllHeaders         = Lib::getAllHeaders();
        $data['UID']       = $getAllHeaders['UID'];
        $data['card_no']       = Lib::post('card_no');
        $data['pay_password']  = Lib::post('pay_password');
        $data['appid']         = Lib::post('appid');
        $m = new \App\API\V100\Model\CardPay();
        $result = $m->payauth($data);
        //$notice=lib::loadFile('Config/Notice.php');
        //$result=array_merge($result,$notice);
        Lib::outputJson($result);
    }
    
    
    //用户提现判断金额是否在规定范围内
    public function checkMoney(){
        $getAllHeaders         = Lib::getAllHeaders();
        $data['UID']       = $getAllHeaders['UID'];
        $data['utype']       = $getAllHeaders['UTYPE'];
        $data['money']         = Lib::post('money');
        
        $m = new \App\API\V100\Model\CardPay();
        $result = $m->checkMoney($data);
        Lib::outputJson($result);
    } 

    /**
     * 用户提现
     */
    public function takeCash()
    {
        $getAllHeaders         = Lib::getAllHeaders();
        $data['UID']       = $getAllHeaders['UID'];
        $data['card_no']       = Lib::post('card_no');
        $data['money']         = Lib::post('money');
        $data['pay_password']  = Lib::post('pay_password');
        $data['appid']         = Lib::post('appid');
        $m = new \App\API\V100\Model\CardPay();
        $result = $m->takeCash($data);
        Lib::outputJson($result);
    }
    /**
     * 修改登录密码
     */
    public function editLoginPassword()
    {
        $user_Info['password'] = Lib::post('password');
        $user_Info['new_password'] = Lib::post('new_password');
        $user_Info['re_new_password'] = Lib::post('re_new_password');

        $data = $this->userModel->editLoginPassword($this->uid, $user_Info,$this->userInfo);
        Lib::outputJson($data);
    }
    /**
     * 检查修改支付密码的用户信息
     */
    public function checkSetPayPasswordInfo()
    {
        $user_Info['password'] = Lib::post('password');
        $user_Info['real_name'] = Lib::post('real_name');
        $user_Info['id_card'] = Lib::post('id_card');

        $data = $this->userModel->checkSetPayPasswordInfo($this->uid, $user_Info,$this->userInfo);
        Lib::outputJson($data);
    }
    /**
     * 更新子商户费率
     */
    public function balanceUserRate()
    {
        $userCode = !empty(Lib::post('userCode')) ? Lib::post('userCode') : "";
        $V = "\\App\\API\\" . $this->headerData['VERSION'] . "\\Model\\Pay";
        $pay = new $V();
        $data = $pay->balanceUserRate($userCode);
        if ($data) {
            Lib::outputJson(array('status' => 'success', 'code' => 1000,'msg' => '设置成功'));
        } else {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000,'msg' => '设置失败'));
        }
        
    }
    

}

