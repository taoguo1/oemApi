<?php
namespace App\API\V100\Model;
use Core\DB\DBQ;
use Core\Extend\Redis;
use Core\Lib;
use Exception;


class Agent extends Base
{

    /**
     * 通过代理id获取代理信息
     * @param $agent_id
     * @return array|bool
     */
    public function getAgentInfoByAgentId($agent_id)
    {

        if (empty($agent_id)) return false;
        $data = DBQ::getRow('agent(A)', [
            '[>]agent_ext(AE)' => ['A.id' => 'agent_id']
        ],
            [
                'A.id(aid)',
                'A.mobile',
                'A.password',
                'A.pay_password',
                'A.nickname',
                'A.real_name',
                'A.id_card',
                'A.pid',
                'A.level',
                'A.rate',
                'A.skrate',
                'A.status',
                'A.is_id_card_auth',
                'A.create_time',
                'A.avatar',
                'AE.agent_id',
                'AE.total_commission',
                'AE.invite_code_num',
                'AE.idcard_scan_img1',
                'AE.idcard_scan_img2',
                'AE.idcard_scan_img3',
            ],
            ['A.id' => $agent_id]);
        $total_commission = $data['total_commission'];
        $data['id_card'] = Lib::aesDecrypt($data['id_card']);

        //$creditCard = DBQ::getCount('credit_card', ['user_id' => $agent_id ,'status'=>1]);
        //$creditCard = DBQ::getCount('credit_card', ['user_id' => $agent_id]);
        //$data['credit_card_count']    = $creditCard?$creditCard:0;
        //$debitCard = DBQ::getCount('debit_card', ['user_id' => $agent_id,'status'=>1]);
        $debitCard = DBQ::getCount('credit_card', ['user_id' => $agent_id,'user_type'=>2]);
        $creditCard = DBQ::getCount('debit_card', ['user_id' => $agent_id,'user_type'=>2]);
        $data['debitcard_count']    = $debitCard?$debitCard:0;
        $data['creditCard']=$creditCard?$creditCard:0;
        $data['debit_card_count']=($creditCard+$debitCard)?($creditCard+$debitCard):0;

        $used_sum = DBQ::getCount('invite_code_trade(A)','*',['A.after_agent_id'=>$agent_id,'A.status'=>3]);
        $data['used_sum']    = $used_sum?$used_sum:0;
        $invite_code_num = DBQ::getCount("invite_code","*",['agent_id'=>$agent_id,"status[!]" =>3 ]) ;
        $data['invite_code_num'] = $invite_code_num ? $invite_code_num: 0;
        //未使用
        if($this->getSys($agent_id)) {
            $unused_sum = DBQ::getCount('invite_code_trade(A)','*',['A.after_agent_id'=>$agent_id,'A.status'=>[1,2]]);

        } else {

            $unused_sum = DBQ::getCount('invite_code_trade(A)','*',['A.after_agent_id'=>$agent_id,'A.status'=>2]);
        }
        $data['unused_sum']    = $unused_sum?$unused_sum:0;

        //if (!empty($data['id_card'])) $data['id_card'] = Lib::aesEncrypt($data['id_card']);


        $withdraw_sum = DBQ::getSum('bill',['amount'],['bill_type'=>3,'agent_id'=>$agent_id]);
        $data['withdraw_sum']    = $withdraw_sum ?Lib::formatMoney($withdraw_sum * 100 / 100, 2):'0.00';
        //$data['withdraw_left']    = $total_commission - $data['withdraw_sum'];

        //获取代理余额
        $money = DBQ::getSum('agent_account', 'amount',['agent_id'=>$agent_id]);
        $data['withdraw_left']    = $money?Lib::formatMoney($money * 100 / 100, 2):'0.00';
        return $data;
    }

    /**
     * 通过手机号码获取代理信息
     * @param $agent_id
     * @return array|bool
     */
    public function getAgentInfoByMobile($mobile)
    {
        if (empty($mobile)) return false;
        $data = DBQ::getRow('agent(A)', [
            '[>]agent_ext(AE)' => ['A.id' => 'agent_id']
        ],
            [
                'A.id(aid)',
                'A.mobile',
                'A.password',
                'A.nickname',
                'A.real_name',
                'A.id_card',
                'A.pid',
                'A.level',
                'A.rate',
                'A.status',
                'A.is_id_card_auth',
                'A.create_time',
                'AE.agent_id',
                'AE.total_commission',
                'AE.invite_code_num',
                'AE.id_card_scan_img1',
                'AE.id_card_scan_img2',
                'AE.id_card_scan_img3',
            ],
            ['A.mobile' => $mobile]);
			
			$data['id_card']=Lib::aesDecrypt($data['id_card']);
			
        return $data;
    }

    /**
     * 通过代理id获取代理的其他信息
     * @param $agent_id
     * @return array|bool
     */
    public function getAgentExtByAgentId($agent_id)
    {
        return DBQ::getRow('agent_ext', '*', ['id' => $agent_id]);
    }

    /**
     * 通过代理id获取用户列表
     * @param $agent_id
     */
    public function getAgentListByAgentId($condition)
    {
        return DBQ::getAll('agent(A)', [
            '[>]agent_ext(AE)' => ['A.id' => 'agent_id']
        ],
            [
                'A.id',
                'A.nickname',
                'AE.total_commission',
                'A.create_time',
            ], $condition);
    }


    /**
     * 通过token获取代理
     * @param $token
     * @return array|bool
     */
    public function getAgentIdByToken($token)
    {
        if (empty($token)) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => 'token不能为空');
        }
        return DBQ::getRow('agent_login_log', '*', ['token' => $token]);
    }

    /**
     * 登录
     * @param $register_info
     */
    public function login($agentInfo)
    {
        if (empty($agentInfo['mobile']) || empty($agentInfo['password'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '登录失败');
        }
        // 验证手机号是否存在
        $condition = null;
        $condition ['AND'] ['mobile'] = $agentInfo['mobile'];
        $agentInfoExt = DBQ::getRow('agent', '*', $condition);
        if(empty($agentInfoExt)){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '该用户不存在');
        }
        if($agentInfoExt['password'] != Lib::compilePassword($agentInfo['password'])){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '账号或密码错误');
        }

        if ($agentInfoExt['status'] != 1) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '该用户已被禁用');
        }
        //获取token
        $transactionId  = false;
        $this->db->pdo->beginTransaction();
        try{
            //退出所有登录设备
            $redis = Redis::instance('token');
            $redis->zRemRangeByScore($agentInfo['app_id'].'_agent_token',$agentInfoExt['id'],$agentInfoExt['id']);
            $token = md5($agentInfoExt['real_name'] . strval(Lib::getMs()) . strval(rand(0, 999999)));
            $tokenData = ['uid' => $agentInfoExt['id'], 'token' => $token, 'utype' => $agentInfo['type'], 'create_time' => Lib::getMs()];
            //$this->db->insert('token', $tokenData);
            //$tokenId    = $this->db->id();
            $a = $redis->zAdd($agentInfo['app_id'].'_agent_token',$agentInfoExt['id'],json_encode($tokenData));
            $count_redis = $redis->zCount($agentInfo['app_id'].'_agent_token',$agentInfoExt['id'],$agentInfoExt['id']);
            if($count_redis > 0){
                $transactionId  = true;
                $this->db->pdo->commit();
            }else{
                $this->db->pdo->rollBack();
            }
        }catch (Exception $e){
            $this->db->pdo->rollBack();
            throw $e;
        }

        if ($transactionId) {
            $agentData = ['agent_id' => $agentInfoExt['id'], 'device_id' => $agentInfo['device_id'], 'device_os' => $agentInfo['device_os'], 'token' => $token, 'create_time' => Lib::getMs()];
            DBQ::add('agent_login_log', $agentData);
            return array('status' => 'success', 'code' => 1000, 'msg' => '登录成功', 'uid'=>$agentInfoExt['id'],'token' => $token);
        } else {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '登录失败');
        }

    }

    /**
     * 忘记密码
     * @param $agentInfo
     */
    public function resetPasswords($agentInfo)
    {
        if (empty($agentInfo['mobile'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '手机号码不能为空');
        }
        if (empty($agentInfo['code'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '验证码不能为空');
        }
        if (empty($agentInfo['password'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '密码不能为空');
        }
        if (empty($agentInfo['repassword'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '确认密码不能为空');
        }
        if ($agentInfo['password'] != $agentInfo['repassword']) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '密码和确认密码不一致');
        }

        //校样验证码
        $checkCodeValidity = $this->checkCodeValidity($agentInfo['mobile'], $agentInfo['code'],$agentInfo['app_id'], true ,2);
        if ($checkCodeValidity['status'] !== 'success') {
            return $checkCodeValidity;
        }

        //修改密码
        $agentData = [
            'password' => Lib::compilePassword($agentInfo['password']),
        ];
        DBQ::upd('agent', $agentData, ['mobile' => $agentInfo['mobile']]);
        //修改短信验证码状态
        $this->setCaptchaStatus($checkCodeValidity['verifycode']);
        return array('status' => 'success', 'code' => 1000, 'msg' => '修改成功');
    }

    /**
     * 注销
     * @param $token
     */
    public function logout($aid,$token,$app_id)
    {
        if (empty($aid) || empty($token)) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '注销失败');
        }

        $redis = Redis::instance('token');
        $redis->zRemRangeByScore($app_id."_agent_token",$aid,$aid);
        return array('status' => 'success', 'code' => 1000, 'msg' => '注销成功');
    }

    /**
     * 重置密码
     * @param $register_info
     */
    public function setPassword($aid, $agent_info, $agent_info_ext)
    {
        if (empty($aid) || empty($agent_info) || empty($agent_info_ext)) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '修改失败');
        }
        if (empty($agent_info['mobile'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '手机号码不能为空');
        }
        if (empty($agent_info['code'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '验证码不能为空');
        }
        if (empty($agent_info['orgpassword'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '原密码不能为空');
        }
        if (empty($agent_info['password'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '密码不能为空');
        }
        if (empty($agent_info['repassword'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '确认密码不能为空');
        }
        if ($agent_info['password'] != $agent_info['repassword']) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '密码和确认密码不一致');
        }

        //校样验证码
        $checkCodeValidity = $this->checkCodeValidity($agent_info['mobile'], $agent_info['code'], true, 2);
        if ($checkCodeValidity['status'] != 'success') {
            return $checkCodeValidity;
        }

        //检验原密码
        if ($agent_info_ext['password'] != Lib::compilePassword($agent_info['orgpassword'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '原密码错误');
        }

        if ($agent_info_ext['password'] != Lib::compilePassword($agent_info['password'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '原密码与新密码不能一致');
        }
        //修改密码
        $userData = [
            'password' => Lib::compilePassword($agent_info['password']),
        ];
        DBQ::upd('agent', $userData, ['id' => $aid]);
        //修改短信验证码状态
        $this->setCaptchaStatus($checkCodeValidity['verifycode']);
        return array('status' => 'success', 'code' => 1000, 'msg' => '修改成功');
    }

    /**
     * 重置密码
     * @param $register_info
     */
    public function editLoginPassword($aid, $agent_info,$agent_info_ext)
    {
        if (empty($aid) || empty($agent_info) || empty($agent_info_ext)) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '修改失败');
        }
        if (empty($agent_info['password'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '原密码不能为空');
        }
        if (empty($agent_info['new_password'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '密码不能为空');
        }
        if (empty($agent_info['re_new_password'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '确认密码不能为空');
        }
        if ($agent_info['new_password'] != $agent_info['re_new_password']) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '密码和确认密码不一致');
        }
        if ($agent_info_ext['password'] != Lib::compilePassword($agent_info['password'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '登录密码错误!');
        }
        //检验原密码
        if ($agent_info_ext['password'] == Lib::compilePassword($agent_info['new_password']))
        {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '原密码与新密码不能一致');
        }
        //修改密码
        $userData = ['password' => Lib::compilePassword($agent_info['new_password'])];
        DBQ::upd('agent', $userData, ['id' => $aid]);
        //修改短信验证码状态
        return array('status' => 'success', 'code' => 1000, 'msg' => '修改成功');
    }

    /**
     * 添加代理商
     * @param  $agent_info
     */
    public function addAgent($agent_info, $agent_info_ext)
    {
        if (empty($agent_info) || empty($agent_info_ext)) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '添加失败');
        }
        $agent_info_arr = $this->getAgentInfoByMobile($agent_info['mobile']);
        if (!empty($agent_info_arr)) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '手机号码已存在');
        }
        if ($agent_info_ext['rate'] < $agent_info['rate']) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '下级代理还款分润比例不能大于上级代理');
        }
        if ($agent_info_ext['skrate'] < $agent_info['skrate']) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '下级代理收款分润比例不能大于上级代理');
        }

        if ($agent_info_ext['invite_code_num'] < $agent_info['invite_code_num']) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '下级代理邀请码数量不能大于上级代理');
        }
        $agentData = [
            'mobile' => $agent_info['mobile'],
            'password' => Lib::compilePassword($agent_info['password']),
            'nickname' => $agent_info['nickname'],
            'rate' => $agent_info['rate'],
            'skrate' => $agent_info['skrate'],
            'create_time' => Lib::getMs()
        ];
        //代理级别
        $agent_mod = new \App\ADMIN\Model\Agent();
        if (!empty($agent_info_ext['pid'])) {
            $level = $agent_mod->getSelfAgentLevel($agent_info_ext['pid']);
            if (!empty($level)) {
                $agentData['level'] = $level;
            }
        }
        $this->db->insert('agent', $agentData);
        $agentExtData = [
            'agent_id' => $this->db->id(),
            'invite_code_num' => $agent_info['invite_code_num']
        ];
        DBQ::add('agent_ext', $agentExtData);
        return array('status' => 'success', 'code' => 1000, 'msg' => '修改成功');
    }

    /**
     * 修改头像
     * @param $uid
     * @param $avatar
     * @return array
     */
    public function editAvatar($aid, $file)
    {
        //上传头像
        if (empty($aid) || empty($file['file']['name'])) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '修改失败'));
        }

        $fileUrl = $this->uploadFileOss($file['file']['tmp_name']);
        if (!$fileUrl) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '修改失败'));
        }
        $userData = ['avatar' => $fileUrl];
        DBQ::upd('agent', $userData, ['id' => $aid]);
        return array('status' => 'success', 'code' => 1000, 'msg' => '修改成功', 'avatar' => $fileUrl);
    }

    /**
     * 设置支付密码
     * @param $register_info
     */
    public function setPayPassword($aid, $agentInfo,$ext_agent_info)
    {
        if (empty($aid) || empty($agentInfo)) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '设置失败1');
        }
        if (empty($agentInfo['pay_password'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '支付密码不能为空');
        }
        if ($ext_agent_info['pay_password'] == Lib::compilePassword($agentInfo['pay_password'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '新密码和旧密码相同');
        }
        //设置支付密码
        $userData = [
            'pay_password' => Lib::compilePassword($agentInfo['pay_password']),
        ];
        $rowCount = DBQ::upd('agent', $userData, ['id' => $aid]);
        //修改短信验证码状态
        //$this->setCaptchaStatus($checkCodeValidity['verifycode']['id']);
        if ($rowCount == 1) {
            return array('status' => 'success', 'code' => 1000, 'msg' => '设置成功');
        } else {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '设置失败2');
        }
    }


    /**
     * 身份认证
     * @param $identity
     */
    public function identity($aid, $identity,$appid=null)
    {
        if (empty($aid) || empty($identity)) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '认证失败');
        }

        //验证姓名身份证号
/*      $auth = IDAuth::instance();
        $idCardAuth = $auth->twoItem($identity['real_name'],$identity['id_card']);
        $idCardAuthobj = json_decode($idCardAuth);
        if($idCardAuthobj->key != '0000'){
            return array('status' => 'fail', 'code' => 1000, 'msg' => $idCardAuthobj->msg);
        }*/
        if(empty($identity['real_name'])){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '真实姓名不能为空');
        }
        if(empty($identity['id_card'])){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '身份证号不能为空');
        }
        if(empty($identity['address'])){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '地址不能为空');
        }
        if(empty($identity['sex'])){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '性别不能为空');
        }
        if(empty($identity['birth'])){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '生日不能为空');
        }
        if(empty($identity['idcard_scan_img1'])){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '请上传身份证正面');
        }
        if(empty($identity['idcard_scan_img2'])){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '请上传身份证背面');
        }
        if(empty($identity['idcard_scan_img3'])){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '请上传手持身份证图片');
        }

        $agentInfo=DBQ::getRow('agent','*',['id'=>$aid]);
        if($agentInfo['is_id_card_auth']==1){
            return array('status' => 'fail', 'code' => 1000, 'msg' =>'您已认证过，无需重复认证');
        }


        $agentData = [
            'real_name' => $identity['real_name'],
            'id_card' => Lib::aesEncrypt($identity['id_card']),
            'address'   => $identity['address'],
            'sex' => $identity['sex'],
            'birth' => $identity['birth'],
            'is_id_card_auth' => 1,
        ];
        DBQ::upd('agent', $agentData, ['id' => $aid]);

        $condition = null;
        $condition ['AND'] ['agent_id'] = $aid;
        $agentInfoExt = DBQ::getRow('agent_ext', '*', $condition);
        if (empty($agentInfoExt)) {
            $agentInfoData['agent_id']    = $aid;
            if(!empty($identity['idcard_scan_img1'])){
                $agentInfoData['idcard_scan_img1'] = $identity['idcard_scan_img1'];
            }
            if(!empty($identity['idcard_scan_img2'])){
                $agentInfoData['idcard_scan_img2'] = $identity['idcard_scan_img2'];
            }
            if(!empty($identity['idcard_scan_img3'])){
                $agentInfoData['idcard_scan_img3'] = $identity['idcard_scan_img3'];
            }
            DBQ::add('agent_ext', $agentInfoData);
        } else {
            $agentInfoData  = null;
            if(!empty($identity['idcard_scan_img1'])){
                $agentInfoData['idcard_scan_img1'] = $identity['idcard_scan_img1'];
            }
            if(!empty($identity['idcard_scan_img2'])){
                $agentInfoData['idcard_scan_img2'] = $identity['idcard_scan_img2'];
            }
            if(!empty($identity['idcard_scan_img3'])){
                $agentInfoData['idcard_scan_img3'] = $identity['idcard_scan_img3'];
            }
            DBQ::upd('agent_ext', $agentInfoData, ['agent_id' => $aid]);
        }
        DBQ::upd('agent', ['is_id_card_auth'=>1], ['id' => $aid]);

        $regres=Lib::SdkAgentReg($aid);
        Lib::SdkAgentRate($aid);
        return array('status' => 'success', 'code' => 1000, 'msg' => '认证成功 '.$regres);

    }


    /**
     * 获取信用卡
     * @param $uid
     * @return array|bool
     */
    public function getCreditCard($uid){
        if(empty($uid))array('status' => 'fail', 'code' => 1000, 'msg' => '获取失败');
        $data = DBQ::getAll('credit_card', ["id", "bank_name", "card_no"],["user_id" => $uid,'status'=>1]);
        if(!empty($data)){
            foreach($data as $k=>$v){
                $cardNo    = Lib::aesDecrypt($v['card_no']);
                $data[$k]['card_no_ext']    = Lib::strReplace($cardNo,0,4);
            }
            return array('status' => 'success', 'code' => 1000, 'msg' => '获取成功','data'=>$data);
        }
        return array('status' => 'fail', 'code' => 1000, 'msg' => '暂无绑定信用卡','data'=>'');
    }

    /**
     * 获取储蓄卡
     * @param $uid
     * @return array|bool
     */
    public function getDebitCard($uid){
        if(empty($uid))array('status' => 'fail', 'code' => 1000, 'msg' => '获取失败');
        $data = DBQ::getAll('debit_card', ["id", "bank_name", "card_no"],["user_id" => $uid,'status'=>1]);
        if(!empty($data)){
            foreach($data as $k=>$v){
                $cardNo    = Lib::aesDecrypt($v['card_no']);
                $data[$k]['card_no_ext']    = Lib::strReplace($cardNo,0,4);
            }
            return array('status' => 'success', 'code' => 1000, 'msg' => '获取成功','data'=>$data);
        }
        return array('status' => 'fail', 'code' => 1000, 'msg' => '暂无绑定储蓄卡','data'=>'');
    }

    /**
     * 上传身份证正面
     * @param $file
     */
    public function idcardUploadFace($file)
    {
        if (empty($file['file']['name'])) {
            Lib::outputJson(array('status' => 'fail', 'code' => $file['file']['name'], 'msg' => '上传失败'));
        }

        $fileUrl = $this->uploadFileOss($file['file']['tmp_name']);
        if (!$fileUrl) {
            Lib::outputJson(array('status' => 'fail', 'code' => 10001, 'msg' => '上传失败'));
        }
        //提取表单内容
        $result = json_decode(Lib::ocrIdcard($fileUrl),true);
        if(empty($result) || !$result['success']){
            Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '身份证信息有误'));
        }
        if(!isset($result['name'])){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '获取失败','img'=>$fileUrl,'result'=>$result));
        }

        $data   = array();
        $data['real_name']  = $result['name'];
        $data['id_card']  = $result['num'];
        $data['address']  = $result['address'];
        $data['sex']  = $result['sex'];
        $data['birth']  = $result['birth'];
        $data['file_url']  = $fileUrl;
        Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '上传成功', 'data' => $data,'r'=>$result));
    }

    /**
     * 上传身份证背面
     * @param $file
     */
    public function idcardUploadBack($file)
    {
        if (empty($file['file']['name'])) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '上传失败'));
        }

        $fileUrl = $this->uploadFileOss($file['file']['tmp_name']);
        if (!$fileUrl) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '上传失败'));
        }
        //提取表单内容
        $result = json_decode(Lib::ocrIdcard($fileUrl,'back'),true);
        if(empty($result) || !$result['success']){
            Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '身份证信息有误'));
        }
        $nowDate = date('Ymd');
        if(!isset($result['end_date'])){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '身份证失败失败','img'=>$fileUrl,'result'=>$result));
        }


        if($nowDate > $result['end_date']){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '身份证已过期','img'=>$fileUrl,'result'=>$result));
        }

        $data   = array();
        /*
        $data['issue']  = $result['showapi_res_body']['flag'];
        $data['start_date']  = $result['showapi_res_body']['effBeginDate'];
        $data['end_date']  = $result['showapi_res_body']['effEndDate'];
        */
        $data['file_url']  = $fileUrl;
        Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '上传成功', 'data' => $data));
    }

    /**
     * 手持身份证上传
     * @param $file
     */
    public function idcardUpload($file)
    {
        if (empty($file['file']['name'])) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '上传失败'));
        }

        $fileUrl = $this->uploadFileOss($file['file']['tmp_name']);
        if (!$fileUrl) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '上传失败'));
        }
        $data['file_url']  = $fileUrl;
        Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '上传成功', 'data' => $data));
    }
    public function checkSetPayPasswordInfo($uid, $agent_info, $save_agent_info)
    {
        if (empty($uid) || empty($agent_info)) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '验证失败1');
        }
        if (empty($agent_info['password'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '密码不能为空');
        }
        if (empty($agent_info['real_name'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '姓名不能为空');
        }
        if (empty($agent_info['id_card'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '身份证不能为空');
        }
        $password = Lib::compilePassword($agent_info['password']);
        $id_card = $agent_info['id_card'];
        $real_name = $agent_info['real_name'];
        //return  $password.'--->'.$id_card.'--->'.$real_name;
        if ($save_agent_info['password'] == $password && $save_agent_info['id_card'] == $id_card && $save_agent_info['real_name'] == $real_name ) {
            return array('status' => 'success', 'code' => 1000, 'msg' => '验证通过');
        } else {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '验证失败');
        }

    }

    private function getSys($agent_id) {
        $result = DBQ::getRow("invite_code_trade","*",[
            "after_agent_id" => $agent_id,
            'before_agent_id'=>0,
        ]);
        if($result)
            return true;
        return false;

    }



}