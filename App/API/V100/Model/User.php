<?php
namespace App\API\V100\Model;
use Core\DB\DB;
use Core\Extend\Redis;
use Core\DB\DBQ;
use Core\Lib;
use Exception;


class User extends Base
{

    /**
     * 通过用户id获取用户信息
     * @param $agent_id
     * @return array|bool
     */
    public function getUserInfoByUserId($user_id){
        //if(empty($user_id))return false;
        $data =  DBQ::getRow('user(U)',[
            '[>]user_ext(UE)' => ['U.id' => 'user_id']
        ],
        
            [
                'U.id(uid)',
                'U.mobile',
                'U.password',
                'U.pay_password',
                'U.real_name',
                'U.id_card',
                'U.sex',
                'U.birth',
                'U.avatar',
                'U.agent_id',
                'U.status',
                'U.is_id_card_auth',
                'U.is_push',
                'U.create_time',
                'UE.user_id',
                'UE.invite_code',
                'UE.idcard_scan_img1',
                'UE.idcard_scan_img2',
                'UE.idcard_scan_img3',
                'UE.userCode'
            ]
            ,
            ['U.id'=>$user_id]);
        $creditCard = DBQ::getCount('credit_card', ['user_id' => $user_id,'status'=>'1','user_type'=>1]);
        $data['credit_card_count']    = $creditCard?$creditCard:0;
        $debitCard = DBQ::getCount('debit_card', ['user_id' => $user_id,'status'=>'1','user_type'=>'1']);
        $data['debit_card_count']    = $debitCard?$debitCard:0;
		$data['id_card'] = !empty($data['id_card']) ? Lib::aesDecrypt($data['id_card']) : '';
        $red_money = DBQ::getSum('redpacket', 'money',['user_id' => $user_id]);
        $data['total']    = $red_money?$red_money:0;
        //获取用户余额
        $money = DBQ::getSum('user_account', 'amount',['user_id' => $user_id]);
        $data['balance']    = $money?Lib::formatMoney($money * 100 / 100, 2):'0.00';
        //获取可提现金额
        $data['canTxMoney']=lib::getMayUseMoney($user_id);
        return $data;
    }

    /**
     * 通过手机号码获取用户信息
     * @param $agent_id
     * @return array|bool
     */
    public function getUserInfoByMobile($mobile){
        if(empty($mobile))return false;
        $data =  DBQ::getRow('user(U)',[
            '[>]user_ext(UE)' => ['U.id' => 'user_id']
        ],
            [
                'U.id(uid)',
                'U.mobile',
                'U.password',
                'U.pay_password',
                'U.real_name',
                'U.id_card',
                'U.sex',
                'U.birth',
                'U.avatar',
                'U.agent_id',
                'U.status',
                'U.is_id_card_auth',
                'U.is_push',
                'U.create_time',
                'UE.user_id',
                'UE.balance',
                'UE.invite_code',
                'UE.idcard_scan_img1',
                'UE.idcard_scan_img2',
                'UE.idcard_scan_img3',
            ]
            ,
            ['U.mobile'=>$mobile]);
			$data['id_card']=Lib::aesDecrypt($data['id_card']);
        return $data;
    }

    /**
     * 通过代理id获取用户列表
     * @param $agent_id
     */
    public function getUserListByAgentId($agent_id)
    {
        return DBQ::getAll('user', ['real_name', ',avatar', 'create_time'], ['agent_id' => $agent_id]);
    }

    /**
     * 通过token获取用户
     * @param $agent_id
     * @return array|bool
     */
    public function getUserIdByToken($token)
    {
        if (empty($token)) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => 'token不能为空');
        }
        return DBQ::getRow('user_login_log', '*', ['token' => $token]);
    }


    
     /**
     * 用户注册
     * @param $register_info
     */
    public function userReg($registerInfo)
    {
        if (empty($registerInfo['mobile'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '手机号码不能为空');
        }
        if (empty($registerInfo['password'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '密码不能为空');
        }
        if (empty($registerInfo['code'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '验证码不能为空');
        }
        /*if(empty($registerInfo['invite_code'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '邀请码不能为空');
        }*/

		
         //校样验证码
        $check_code_validity = $this->checkCodeValidity($registerInfo['mobile'], $registerInfo['code'], $registerInfo['app_id'],false);
        if ($check_code_validity['status'] != 'success') {
            return $check_code_validity;
        } 

        $condition  =  null;
        $condition ['AND'] ['mobile'] = $registerInfo['mobile'];
        $userInfoExt = DBQ::getRow('user', '*', $condition);
        if (!empty($userInfoExt)) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '该手机号已注册');
        }
		
		
		if(!empty($registerInfo['invite_code'])) {
            //return array('status' => 'fail', 'code' => 1000, 'msg' => '邀请码不能为空');
            //获取邀请码
	        $where = null;
	        $where['AND']['code'] = $registerInfo['invite_code'];
	        $where['AND']['status[!]'] = 3;
	        //查询已经下发的邀请码
	        $codeRow = DBQ::getRow('invite_code',
	            ['agent_id','code','id'],$where);
	        if (empty($codeRow)) {
	            return array('status' => 'fail', 'code' => 1000, 'msg' => '您输入的邀请码不正确');
	        }
        }
        

        //注册
        if(!empty($registerInfo['invite_code'])) {
        	$userData = [
	            'mobile' => $registerInfo['mobile'],
	            'password' => Lib::compilePassword($registerInfo['password']),
	            'create_time' => Lib::getMs(),
	            'agent_id' => $codeRow['agent_id'],
	        ];
        }else{
        	$userData = [
	            'mobile' => $registerInfo['mobile'],
	            'password' => Lib::compilePassword($registerInfo['password']),
	            'create_time' => Lib::getMs(),
	        ];
        }
        
        $this->db->insert('user', $userData);
		if(!empty($registerInfo['invite_code'])) {
			
			DBQ::upd('invite_code',['status'=>3],['code'=>$codeRow['code']]);
	        DBQ::upd('invite_code_trade',['status'=>3,'use_time'=>Lib::getMs()],['code'=>$codeRow['code']]);
	        //修改所有拥有此邀请码代理 的 库存
	        $result = DBQ::getRow('invite_code',['agent_id'],['status'=>3,'code'=>$codeRow['code']]);
	        DBQ::upd('agent_ext',['invite_code_num[-]'=>1],$result);
		}
		
        

        //生成token
        $userId = $this->db->id();
        $userData   = $this->getUserInfoByUserId($userId);

        $token = md5($userData['real_name'] . strval(Lib::getMs()) . strval(rand(0, 999999)));
        $tokenData = ['uid' => $userId, 'token' => $token, 'utype' => $registerInfo['type'], 'create_time' => Lib::getMs()];
        $userData = ['user_id' => $userId, 'device_id' => $registerInfo['device_id'], 'device_os' => $registerInfo['device_os'], 'token' => $token, 'create_time' => Lib::getMs()];
         //DBQ::upd('user_ext',['invite_code'=>$registerInfo['invite_code']],['user_id'=>$registerInfo['user_id']]);


        if(!empty($registerInfo['invite_code'])) {
            $userData2=['user_id' => $userId,'invite_code'=>$registerInfo['invite_code']];
        }else{
            $userData2=['user_id' => $userId];
        }
        
        DBQ::add('user_ext', $userData2);
        $redis = Redis::instance('token');
        $redis->zAdd($registerInfo['app_id'].'_user_token',$userId,json_encode($tokenData));
        DBQ::add('user_login_log', $userData);
        //修改短信验证码状态
        $this->setCaptchaStatus($check_code_validity['verifycode']);
        return array('status' => 'success', 'code' => 1000, 'msg' => '注册成功','user_id'=>$userId , 'token'=>$token);
    }

    //绑定邀请码
    public function bindInviteCode($registerInfo){
        if(empty($registerInfo['invite_code'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '邀请码不能为空');
        }else{
             //判断邀请码
            $where = null;
            $where['AND']['code'] = $registerInfo['invite_code'];
            $where['AND']['status[!]'] = 3;
            //查询已经下发的邀请码
            $codeRow = DBQ::getRow('invite_code',
                ['agent_id','code','id'],$where);
            if (empty($codeRow)) {
                return array('status' => 'fail', 'code' => 1000, 'msg' => '您输入的邀请码不正确');
            }
            $codeRowInfo = DBQ::getOne('user_ext',
                ['invite_code'],['user_id'=>$registerInfo['user_id']]);
            if($codeRowInfo['invite_code']!=''){
                return array('status' => 'fail', 'code' => 1000, 'msg' => '您已绑定过代理，不能重复绑定');
            }
            DBQ::upd('user_ext',['invite_code'=>$registerInfo['invite_code']],['user_id'=>$registerInfo['user_id']]);
            //修改agent_id
            DBQ::upd('user',['agent_id'=>$codeRow['agent_id']],['id'=>$registerInfo['user_id']]);
            DBQ::upd('invite_code',['status'=>3],['code'=>$codeRow['code']]);
            DBQ::upd('invite_code_trade',['status'=>3,'use_time'=>Lib::getMs()],['code'=>$codeRow['code']]);
            //修改所有拥有此邀请码代理 的 库存
            $result = DBQ::getRow('invite_code',['agent_id'],['status'=>3,'code'=>$codeRow['code']]);
            DBQ::upd('agent_ext',['invite_code_num[-]'=>1],$result);
        }
        return array('status' => 'success', 'code' => 1000, 'msg' => '邀请码绑定成功');
            
       
    }
















    /**
     * 忘记密码
     * @param $register_info
     */
    public function resetPassword($user_info)
    {
        if (empty($user_info['mobile'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '手机号码不能为空');
        }
        if (empty($user_info['code'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '验证码不能为空');
        }
        if (empty($user_info['password'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '密码不能为空');
        }
        if (empty($user_info['repassword'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '确认密码不能为空');
        }
        if ($user_info['password'] != $user_info['repassword']) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '密码和确认密码不一致');
        }
        //校样验证码
        $checkCodeValidity = $this->checkCodeValidity($user_info['mobile'], $user_info['code'], $user_info['app_id'],true);
        if ($checkCodeValidity['status'] !== 'success') {
            return $checkCodeValidity;
        }

        //修改密码
        $userData = [
            'password' => Lib::compilePassword($user_info['password']),
        ];
        DBQ::upd('user', $userData, ['mobile' => $user_info['mobile']]);
        //修改短信验证码状态
        $this->setCaptchaStatus($checkCodeValidity['verifycode']);
        return array('status' => 'success', 'code' => 1000, 'msg' => '修改成功');
    }

    /**
     * 设置支付密码
     * @param $register_info
     */
    public function setPayPassword($uid, $userInfo,$ext_user_info)
    {
        if (empty($uid) || empty($userInfo)) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '设置失败');
        }
        if (empty($userInfo['pay_password'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '支付密码不能为空');
        }
        if ($ext_user_info['pay_password'] == Lib::compilePassword($userInfo['pay_password'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '新密码和旧密码相同');
        }
        //设置支付密码
        $userData = [
            'pay_password' => Lib::compilePassword($userInfo['pay_password']),
        ];
        $rowCount = DBQ::upd('user', $userData, ['id' => $uid]);
        //修改短信验证码状态
        //$this->setCaptchaStatus($checkCodeValidity['verifycode']['id']);
        if ($rowCount == 1) {
            return array('status' => 'success', 'code' => 1000, 'msg' => '设置成功');
        } else {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '设置失败');
        }
    }

    /**
     * 登录
     * @param $register_info
     */
    public function login($userInfo)
    {

        if (empty($userInfo['mobile']) || empty($userInfo['password'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '登录失败');
        }
        // 验证手机号是否正确
        $condition = null;
        $userInfoExt = DBQ::getRow('user', '*', ['mobile' => $userInfo['mobile']]);
        if($userInfoExt['password'] != Lib::compilePassword($userInfo['password'])){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '账号或密码错误');
        }

        if ($userInfoExt['status'] != 1) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '用户不存在或禁用');
        }

        //获取token
        $transactionId  = false;
        $this->db->pdo->beginTransaction();
        try{
            //退出所有登录设备
            $redis = Redis::instance('token');
            $redis->zRemRangeByScore($userInfo['app_id'].'_user_token',$userInfoExt['id'],$userInfoExt['id']);
            $token = md5($userInfoExt['real_name'] . strval(Lib::getMs()) . strval(rand(0, 999999)));
            $tokenData = ['uid' => $userInfoExt['id'], 'token' => $token, 'utype' => $userInfo['type'], 'create_time' => Lib::getMs(),'deviceid' => $userInfo['device_id']];
            //添加token登录信息到redis
            $redis->zAdd($userInfo['app_id'].'_user_token',$userInfoExt['id'],json_encode($tokenData));
            $count_redis = $redis->zCount($userInfo['app_id'].'_user_token',$userInfoExt['id'],$userInfoExt['id']);

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
            $userData = ['user_id' => $userInfoExt['id'], 'device_id' => $userInfo['device_id'], 'device_os' => $userInfo['device_os'], 'token' => $token, 'create_time' => Lib::getMs()];
            DBQ::add('user_login_log', $userData);
            return array('status' => 'success', 'code' => 1000, 'msg' => '登录成功', 'user_id' => $userInfoExt['id'], 'token' => $token);
        } else {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '登录失败');
        }
    }

    /**
     * 注销
     * @param $token
     */
    public function logout($uid,$token,$app_id)
    {
        if (empty($uid) || empty($token)) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '注销失败');
        }
        //DBQ::del('token', ['uid' => $uid ,'token'=>$token]);
        //删除redis存储的登录信息
        //$redis = Redis::instance('token');
        //$redisObj =  RedisAliMulti::getRedisInstance(REDIS['token'], 0);
        $redis = Redis::instance('token');
        //$redis = $redisObj->getConnect();
        //$redis->zRemRangeByScore($app_id.'_user_token',$uid,$uid);
        return array('status' => 'success', 'code' => 1000, 'msg' => '注销成功');
    }

    /**
     * 设置推送
     * @param $uid
     * @param $is_push
     */
    public function editPush($uid, $isPush)
    {
        if (empty($uid) || empty($isPush)) {
            Lib::outputJson(array('status' =>'fail', 'code' => 1000, 'msg' => '参数错误'));
        }
        $userData = [
            'is_push' => $isPush,
        ];
        DBQ::upd('user', $userData, ['id' => $uid]);
        Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '修改成功'));
    }

    /**
     * 修改头像
     * @param $uid
     * @param $avatar
     * @return array
     */
    public function editAvatar($uid, $avatar)
    {
        if (empty($uid) || empty($avatar['file']['name'])) {
            Lib::outputJson(array('status' => 'fail', 'code' => 10001, 'msg' => '修改失败'));
        }

        $fileUrl = $this->uploadFileOss($avatar['file']['tmp_name']);
        if (!$fileUrl) {
            Lib::outputJson(array('status' => 'fail', 'code' => 10002, 'msg' => '修改失败'));
        }
        $userData = ['avatar' => $fileUrl];
        DBQ::upd('user', $userData, ['id' => $uid]);
        return array('status' => 'success', 'code' => 1000, 'msg' => '修改成功', 'avatar' => $fileUrl);

    }

    /**
     * 上传身份证正面
     * @param $file
     */
    public function idcardUploadFace($file)
    {
        if (empty($file['file']['name'])) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '上传失败'));
        }

        $fileUrl = $this->uploadFileOss($file['file']['tmp_name']);
        if (!$fileUrl) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '上传失败'));
        }
        $data['real_name'] = "";
        $data['id_card'] = "";
        $data['address'] = "";
        $data['sex'] = "";
        $data['birth'] = "";
        $data['file_url'] = "";
        //提取表单内容
        $result = json_decode(Lib::ocrIdcard($fileUrl),true);
        $data   = array();

        if(isset($result['name']))
        {
            $data['real_name']  = $result['name'];
        }
        if(isset($result['num']))
        {
            $data['id_card']  = $result['num'];
        }
        if(isset($result['address']))
        {
            $data['address']  = $result['address'];
        }
        if(isset($result['sex']))
        {
            $data['sex']  = $result['sex'];
        }
        if(isset($result['birth']))
        {
            $data['birth']  = $result['birth'];
        }


        $data['file_url']  = $fileUrl;
        Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '上传成功', 'data' => $data));
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
            Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '身份证信息有误','img'=>$fileUrl));
        }
        $nowDate = date('Ymd');
        if(isset($result['end_date']))
        {
            if($nowDate > $result['end_date']){
                Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '身份证已过期'));
            }
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

    /**
     * 身份认证
     * @param $identity
     */
    public function identity($uid, $identity)
    {
        if (empty($uid) || empty($identity)) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '认证失败');
        }
        $data=[
            'name'=>$identity['real_name'],
            'idnumber'=>$identity['id_card'],
        ];

        $userInfo=DBQ::getOne('user','id_card',['id'=>$uid]);
        if(!empty($userInfo)){ 
            return array('status' => 'fail', 'code' => 1000, 'msg' =>'您已认证过，无需重复认证');
        }
        


        //认证姓名身份证号
        $idCardAuth  = Lib::httpPostUrlEncode(EX_SERVICE.'exchange/RealnameAuth/idTwoItems',$data);
        //日志
        Lib::tempLog('idTwoItems',$data,'Auth');
        Lib::tempLog('idTwoItems',$idCardAuth,'Auth');
        if(!$idCardAuth){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '身份证认证失败');
        }

        $idCardAuthobj = json_decode($idCardAuth);
        if(gettype($idCardAuthobj) == 'string'){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '身份证认证失败','error'=>$idCardAuthobj);
        }

        if($idCardAuthobj->code != '0000'){
            return array('status' => 'fail', 'code' => 1000, 'msg' => $idCardAuthobj->msg);
        }

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
        $user_data = [
            'real_name' => $identity['real_name'],
            'id_card' => Lib::aesEncrypt($identity['id_card']),
            'address'   => $identity['address'],
            'sex' => $identity['sex'],
            'birth' => $identity['birth'],
        ];
        DBQ::upd('user', $user_data, ['id' => $uid]);

        $condition = null;
        $condition ['AND'] ['user_id'] = $uid;
        $userInfoExt = DBQ::getRow('user_ext', '*', $condition);
        //if (!empty($userInfoExt)) { //修改 hqf 2018-03-13
        if (empty($userInfoExt)) {
            $userExtData = [
                'user_id' => $uid,
                'idcard_scan_img1' => $identity['idcard_scan_img1'],
                'idcard_scan_img2' => $identity['idcard_scan_img2'],
                'idcard_scan_img3' => $identity['idcard_scan_img3'],
            ];
            DBQ::add('user_ext', $userExtData);
        } else {
            $userExtData = [
                'idcard_scan_img1' => $identity['idcard_scan_img1'],
                'idcard_scan_img2' => $identity['idcard_scan_img2'],
                'idcard_scan_img3' => $identity['idcard_scan_img3'],
            ];
            DBQ::upd('user_ext', $userExtData, ['user_id' => $uid]);
        }
        DBQ::upd('user', ['is_id_card_auth'=>1], ['id' => $uid]);
        $res=Lib::SdkUserReg($uid);
        //设置费率
        Lib::SdkUserRate($uid);
        return array('status' => 'success', 'code' => 1000, 'msg' => '认证成功.接口信息：'.$idCardAuthobj->msg.' '.$res);
    }

    /**
     * 获取信用卡
     * @param $uid
     * @return array|bool
     */
    public function getCreditCard($uid){
        if(empty($uid))array('status' => 'fail', 'code' => 1000, 'msg' => '获取失败');
        $data = DBQ::getAll('credit_card', ["id", "bank_name", "card_no"],["user_id" => $uid,'status' => 1]);
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
        $data = DBQ::getAll('debit_card', ["id", "bank_name", "card_no"],["user_id" => $uid,'status' => 1]);
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
     * 获取储蓄卡和信用卡
     * @param $uid
     * @return array|bool
     */
    public function getAllCard($uid){
        if(empty($uid))array('status' => 'fail', 'code' => 1000, 'msg' => '获取失败');
        $debit_condition['AND']['D.user_id'] = $uid;
        $debit_condition['AND']['D.status'] = 1;
        $debit_condition['AND']['D.user_type'] =1;
        $condition['ORDER'] = [
            'D.is_default' => 'DESC'
        ];
        $credit_condition['AND']['C.user_id'] = $uid;
        $credit_condition['AND']['C.status'] = 1;
        $credit_condition['ORDER'] = [
            'C.is_default' => 'DESC'
        ];

        $debit_data  = DBQ::getAll('debit_card (D)', ["D.id","D.bank_id", "D.bank_name","D.card_no",'D.is_default'],$debit_condition);
        $credit_data  = DBQ::getAll('credit_card (C)',["C.id","C.bank_id","C.bank_name","C.card_no",'C.is_default'],$credit_condition);
        //$credit_data = DBQ::getAll('credit_card', ["id", "bank_name", "card_no",'is_default'],$credit_where);
        if(!empty($debit_data) || !empty($credit_data)){
            if (!empty($debit_data)) {
                foreach($debit_data as $k=>&$v){
                    $bank=lib::getOneBankConfig($v['bank_id']);
                    $v['logo'] = $bank['logo'];
                    //$debit_data[$k]['card_no_ext']    = Lib::strReplace($cardNo,0,4);
                    $v['card_no']    = Lib::aesDecrypt($v['card_no']);
                    $v['card_type'] = 2;
                }
            }
            if (!empty($credit_data)) {
                foreach($credit_data as $k=>&$v){
                    $bank2=lib::getOneBankConfig($v['bank_id']);
                    $v['logo']    = $bank2['logo'];
                    $v['back_image']    = $bank2['back_image'];
                    $v['card_no']    = Lib::aesDecrypt($v['card_no']);
                    //$credit_data[$k]['card_no_ext']    = Lib::strReplace($cardNo,0,4);
                    $v['card_type'] = 1;
                }
            }

            return array('status' => 'success', 'code' => 1000, 'msg' => '获取成功','data'=>array_merge($credit_data,$debit_data));
        }
        return array('status' => 'fail', 'code' => 1000, 'msg' => '暂无绑定银行卡','data'=>null);
    }

    /**
     *用户绑定邀请码
     * @param $uid
     * @param $invite_code
     * @return bool
     */
    public function userBindInviteCode($uid,$invite_code)
    {
        $row_code = DBQ::getRow('invite_code', '*',['code' => $invite_code]);
        //开启事物
        $this->db->pdo->beginTransaction();
        try {
            //更新dzz_user_ext
            $updObject = $this->db->update('user_ext', ['invite_code' => $invite_code],['user_id' => $uid]);
            $result1 = $updObject->rowCount();
            if (empty($result1)){
                $this->db->pdo->rollBack();
                return false;
            } else {
                //更新邀请码表的邀请码status
                $codeObject = $this->db->update('invite_code', ['status' => 3],['id' => $row_code['id']]);
                $result2 = $codeObject->rowCount();
                if ($result2) {
                    //更新用户表的代理agent_id
                    $userObject = $this->db->update('user', ['agent_id' => $row_code['id']],['id' => $uid]);
                    $result3 = $userObject->rowCount();
                    if ($result3) {
                        $this->db->pdo->commit();
                    } else {
                        $this->db->pdo->rollBack();
                        return false;
                    }
                } else {
                    $this->db->pdo->rollBack();
                    return false;
                }
            }
        }
        catch (Exception $e) {
            $this->db->pdo->rollBack();
            throw $e;
            return false;
        }
        return true;
    }
    /**
     * 重置密码
     * @param $register_info
     */
    public function editLoginPassword($id, $user_info,$ext_user_info)
    {
        if (empty($id) || empty($user_info) || empty($ext_user_info)) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '修改失败');
        }
        if (empty($user_info['password'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '原密码不能为空');
        }
        if (empty($user_info['new_password'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '密码不能为空');
        }
        if (empty($user_info['re_new_password'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '确认密码不能为空');
        }
        if ($user_info['new_password'] != $user_info['re_new_password']) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '密码和确认密码不一致');
        }
        if (Lib::compilePassword($user_info['new_password']) == $ext_user_info['password']) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '原密码和新密码不能一致');
        }
        //检验原密码
        if ($ext_user_info['password'] != Lib::compilePassword($user_info['password'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '原密码错误');
        }

        //修改密码
        $userData = ['password' => Lib::compilePassword($user_info['new_password']),];
        DBQ::upd('user', $userData, ['id' => $id]);
        //修改短信验证码状态
        return array('status' => 'success', 'code' => 1000, 'msg' => '修改成功');
    }
    /**
     * 检查修改支付密码的用户信息
     */
    public function checkSetPayPasswordInfo($uid, $user_info, $save_user_info)
    {
        if (empty($uid) || empty($user_info)) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '验证失败');
        }
        if (empty($user_info['password'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '密码不能为空');
        }
        if (empty($user_info['real_name'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '姓名不能为空');
        }
        if (empty($user_info['id_card'])) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '身份证不能为空');
        }
        $password = Lib::compilePassword($user_info['password']);
        $id_card = $user_info['id_card'];
        $real_name = $user_info['real_name'];
        if ($save_user_info['password'] == $password && $save_user_info['id_card'] == $id_card && $save_user_info['real_name'] == $real_name ) {
            return array('status' => 'success', 'code' => 1000, 'msg' => '验证通过');
        } else {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '验证失败');
        }

    }


}