<?php
namespace App\API\V100\Model;
use Core\Extend\IDAuth;
use Core\DB\DBQ;
use Core\Lib;
use Exception;
class AgentCard extends Base
{
    public function getList($condition)
    {
        $data = DBQ:: getAll('credit_card (C)',[
            "C.id",
            "C.status",
            "C.bank_id",
            "C.bank_name",
            "C.real_name",
            "C.bill_day",
            "C.repayment_day",
            "C.start_repayment_day",
            "C.end_repayment_day",
            "C.expiry_date",
            "C.card_no",
            'C.is_default',
            "C.create_time"
        ],$condition);
        if(!empty($data)){
            foreach ($data as $k => &$v){
                $bank = Lib::getOneBankConfig($v['bank_id']);
                $v['logo']    = $bank['logo'];
                $v['back_image']    = $bank['back_image'];
                $v['card_no']=Lib::aesDecrypt($v['card_no']);
                $v['real_name']=Lib::curNameHide(Lib::aesDecrypt($v['real_name']));
                $v['expiry_date']=Lib::aesDecrypt($v['expiry_date']);
                $v['create_time']=\Core\Lib::uDate('Y-m-d',$v['create_time']);
            }
        }
        return $data;
    }
    //修改卡状态
    public function card_status($isCard,$uid)
    {
        $arr = DBQ::getRow('credit_card', '*', [
            'status' =>1,
            'user_id ' => $uid,
            'user_type ' => 2
        ]);
        ($arr) ? $is_default = 0: $is_default = 1 ;
        DBQ::upd('credit_card',[
            'status'  => 2,
            'is_default' => $is_default,
            'last_update_time'  =>  Lib::getMs()
        ], [
            'id' =>$isCard['id'],
        ]);
        return 2;
    }
    //绑定信用卡
    public function add($data)
    {
        if (!\ctype_digit($data['agent_id'])) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => 'uid不合法'));
        }
        if (empty($data['bank_id']) || !(\ctype_digit($data['bank_id']))) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '银行id不存在或者格式错误'));
        }
        if (empty($data['mobile']) || !(Lib::checkMobile( $data['mobile']))){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '手机号为空或格式错误'));
        }
        if (empty($data['real_name'])){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '持卡人姓名不能为空'));
        }
        $isCardd=Lib::isIdCard($data['id_card']);
        if (!$isCardd){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '身份证号码有误'));
        }
        if (!\ctype_digit($data['card_no'])){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '信用卡号为空或格式错误'));
        }
        if (!\ctype_digit($data['cvn'])){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '安全码不能为空或格式错误'));
        }

        if (empty($data['expiry_date'])){

            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '信用卡有效期不能为空'));
        }

        //判断日期是否正确
        $expiry_data=$data['expiry_date'];

        if(strlen($expiry_data)!=4){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '日期长度不正确'));
        }
        if (date('H') < 8) {
            Lib::outputJson( array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '每天的绑卡时间为8:00-22:00'
            ));
        }
        if (date('H') > 23) {
            Lib::outputJson(array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '每天的绑卡时间为8:00-22:00'
            ));
        }

        $year=date('y');
        $hou2=substr($expiry_data,-2);
        $qian2=substr($expiry_data,0,2);
        if(intval($hou2)<intval($year)){
            //年份不正确
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '日期不正确'));
        }
        if(intval($hou2)==intval($year)&&intval($year)==intval(date('y'))){
           if(intval($qian2)<intval(date('m'))){
                //月份不正确 
                Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '日期不正确')); 
           }
        }
        if(intval($qian2)>12){
            //月份不正确
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '日期不正确'));  
        }

        if(intval($year)>99){
            //年份不正确
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '日期不正确')); 
        }
        if(intval($hou2)<intval($qian2)){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '日期不正确'));
        }

        $data['card_no']=\preg_replace('# #','',$data['card_no']);


        $where['user_id'] = $data['agent_id'];
        $where['user_type'] = $data['user_type'];
        $where['card_no'] = Lib::aesEncrypt($data['card_no']);
        $isCard = DBQ::getRow('credit_card', '*', $where);


        if($isCard['status'] == 1) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '信用卡号已绑定'));
        }
        //验证输入信息是否与注册信息一致
        $condition = null;
        $condition['AND']['id'] = $data['agent_id'];
        $condition['AND']['id_card'] = Lib::aesEncrypt($data['id_card']);

        $result = DBQ::getRow('agent', '*', $condition);
        if (empty($result)) Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '验证输入信息与注册信息不一致'));
/*         //验证银行卡四要素
        $exdata = [
            'name' =>$data['real_name'],
            'bankno' =>$data['card_no'],
            'idnumber' =>$data['id_card'],
            'mobile' => $data['mobile']
        ];
        $cardVerification = Lib::httpPostUrlEncode(EX_SERVICE.'exchange/RealnameAuth/cardFourItemsAndImage',$exdata);
        if(empty($cardVerification)){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '银行卡信息认证失败！'));
        } */
        $authBankSta = Lib::bankAuthAli($data['real_name'],$data['id_card'],$data['card_no'],$data['mobile']);
        if (empty($authBankSta)) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '银行卡认证失败'));
        }
        $authBank = json_decode($authBankSta,true);
        if (!empty($authBank['respCode']) && $authBank['respCode'] != '0000') {
            $msg = !empty($authBank['respMessage']) ? $authBank['respMessage'] : '银行卡认证失败！';
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => $msg));
        }
        $dictionary = Lib::loadFile('Config/Dictionary.php');
        $is_default=0;
        $default = DBQ::getRow('credit_card', '*', ['user_id'=>$data['agent_id'],'user_type'=>2]);
        if(empty($default['id'])){
            $is_default = 1;//没信用卡设置为默认
        }

        $cvn = Lib::aesEncrypt($data['cvn']);
        if ($data['smSign'] == 'secTag') {
            //第2次
            //验证短信验证码
            $sms_result = $this-> checkCodeValidity($data['mobile'], $data['smsCode'],$data['appid'], $check_reg = true, $type = 10);
            if (empty($data['sysId'])){
                Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '绑卡失败，协议号不能为空'));
            }
            if ($sms_result['status'] == 'success')
            {
                $order = Lib::createOrderNo();
                
                $sysId = isset($data['sysId']) ? $data['sysId'] : '';
                $creditCard_data = [
                    'user_id' => $data['agent_id'],
                    'bank_id' => $data['bank_id'],
                    'bank_name' => $data['bank_name'],
                    'mobile' => Lib::aesEncrypt($data['mobile']),
                    'lb_mobile' => \substr_replace($data['mobile'], '****', 3, 4),
                    'real_name' => Lib::aesEncrypt($data['real_name']),
                    'card_no' => Lib::aesEncrypt($data['card_no']),
                    'cvn' => $cvn,
                    'id_card' => Lib::aesEncrypt($data['id_card']),
                    'expiry_date' => Lib::aesEncrypt(\str_replace('/', '', $data['expiry_date'])),
                    'channel_type' => 2,
                    'status' => 1,
                    'channel_code' => $dictionary['channel'][2]['code'],
                    'userOrderSn' => $order,//商户订单号
                    'sysId' => $sysId,//协议号
                    'create_time' => Lib::getMs(),
                    'user_type' => 2,
                    'is_default'=>$is_default
                ];
                DBQ::add("credit_card", $creditCard_data);
                $bind_sta = DBQ::insertId();
                
                if ($bind_sta) {

                    $bindCard_data = [
                        'user_id' => $data['agent_id'],
                        'user_type' => 2,
                        'bank_id' => $data['bank_id'],
                        'bank_name' => $data['bank_name'],
                        'card_no' => Lib::aesEncrypt($data['card_no']),
                        'id_card' => Lib::aesEncrypt($data['id_card']),
                        'card_type' => 1,
                        'status' => 1,
                        'description' => '代理绑卡',
                        'channel' => 2,
                        'create_time' => Lib::getMs()
                    ];
                    $this->db->insert("bind_card", $bindCard_data);
                    
                }

                Lib::outputJson(array('status' => 'success', 'code' => 10000, 'msg' => '绑定成功！'));
            }else{
                Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '绑定失败，短信验证码有误！'));
            }
           
        }else{
            //第一次鉴权信用卡接口
            if(empty($data['orderId']) && empty($data['sysId'])){
                Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '绑定失败,验证码不正确！' ));
            }else{
                $user_info = DBQ::getRow('agent_ext', '*', ['agent_id' => $data['agent_id']]);
                if (empty($user_info['userCode'])) {
                    Lib::SdkUserReg($user_info['agent_id']);
                    $user_info = DBQ::getRow('agent_ext', '*', ['agent_id' => $data['agent_id']]);
                }
                //判断提交商户信息是否超过五分钟
                $lasttime= Lib::getMs()-(5*60*1000);
                if($user_info['authTime']>$lasttime){
                    Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '对不起，实名认证过5分钟才能绑卡'));
                }
                $mobile = $data['mobile'];
                $bankNo = $data['card_no'];
                
                
                $expiry_date = \str_replace('/', '', $data['expiry_date']);
                $sdk_sign = md5('[' . ZF_VERSION . ']|[' . $user_info['userCode'] . ']|[' . $mobile . ']|[2]|[' . $bankNo . ']|[' . $data['cvn'] . ']|[' . $expiry_date . ']|[' . MERCHANT_ID . ']|[' . $data['orderId'] . ']|[' . $data['smsCode'] . ']' . ZF_SIGN_IN);
                $datapost = array(
                    'action' => 'SdkPayAuthConfirm',
                    'version' => ZF_VERSION,
                    'merSn' => MERCHANT_ID,//大商户号
                    'userCode' => $user_info['userCode'],//子商编号
                    'cardType' => 2,//银行卡类型
                    'bankNo' => $bankNo,//银行卡号
                    'bankPhone' => $mobile,//绑定手机号码
                    'bankCvn' => $data['cvn'],//信用卡后三位
                    'bankValidityDay' => $expiry_date,//信用卡有效期
                    'smsCode' => $data['smsCode'],//信用卡有效期
                    'bankCode' => $data['bankCode'],//信用卡有效期
                    'orderId' => $data['orderId'],//信用卡有效期
                    'Sign' => $sdk_sign
                );
                $payModelPath = new Pay();
                $payModelPath->balanceUserRate($user_info['userCode']);
                $ret = Lib::httpPostUrlEncode(ZF_URL, $datapost);
                $sdk_ret = json_decode($ret, true);
                
                if ($sdk_ret['error'] == 0) {
                    $order = Lib::createOrderNo();
                    
                    $sysId = isset($sdk_ret['sysId']) ? $sdk_ret['sysId'] : '';
                    $creditCard_data = [
                        'user_id' => $data['agent_id'],
                        'bank_id' => $data['bank_id'],
                        'bank_name' => $data['bank_name'],
                        'mobile' => Lib::aesEncrypt($data['mobile']),
                        'lb_mobile' => \substr_replace($data['mobile'], '****', 3, 4),
                        'real_name' => Lib::aesEncrypt($data['real_name']),
                        'card_no' => Lib::aesEncrypt($data['card_no']),
                        'cvn' => $cvn,
                        'id_card' => Lib::aesEncrypt($data['id_card']),
                        'expiry_date' => Lib::aesEncrypt(\str_replace('/', '', $data['expiry_date'])),
                        'channel_type' => 2,
                        'status' => 1,
                        'channel_code' => $dictionary['channel'][2]['code'],
                        'userOrderSn' => $order,//商户订单号
                        'sysId' => $sysId,//协议号
                        'create_time' => Lib::getMs(),
                        'user_type' => 2,
                        'is_default'=>$is_default
                    ];
                    DBQ::add("credit_card", $creditCard_data);
                    $bind_sta = DBQ::insertId();
                    
                    if ($bind_sta) {
                        $bindCard_data = [
                            'user_id' => $data['agent_id'],
                            'user_type' => 2,
                            'bank_id' => $data['bank_id'],
                            'bank_name' => $data['bank_name'],
                            'card_no' => Lib::aesEncrypt($data['card_no']),
                            'id_card' => Lib::aesEncrypt($data['id_card']),
                            'card_type' => 1,
                            'status' => 1,
                            'description' => '代理绑卡',
                            'channel' => 2,
                            'create_time' => Lib::getMs()
                        ];
                        $this->db->insert("bind_card", $bindCard_data);
                        
                    }

                    Lib::outputJson(array('status' => 'success', 'code' => 10000, 'msg' => '绑定成功！'));
                    
                } else {
                    $this->delCreditCard($isCard['id']);
                    Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '绑定失败！' . $sdk_ret['error_msg']));
                }
            }
               
         }
    }
    //删除，解绑信用卡
    public function deletions($where)    {
        $result = $this->delCreditCard($where['id']);
        if ($result)
        {
            $arr = DBQ::getRow('credit_card', '*', [
                'status' =>1,
                'user_id ' => $where['user_id'],
                'user_type ' => 2
            ]);
            if($arr){
                DBQ::upd('credit_card',[
                    'is_default' =>1,
                ], ['id'=>$arr['id'],'user_id ' => $where['user_id'],'user_type ' => 2]);
            }
            return true;
        } else {
            return false;
        }
    }
    //请求支付解绑
    public function delCreditCard($bank_id)
    {
        $bank_info = DBQ::getRow('credit_card','*',['id' => $bank_id]);
        $user_ext  = DBQ::getRow('agent_ext','*',['agent_id' => $bank_info['user_id']]);
        if (empty($user_ext['userCode'])) {
            Lib::SdkUserReg($bank_info['user_id']);
            $user_ext  = DBQ::getRow('agent_ext','*',['agent_id' => $bank_info['user_id']]);
        }
        if (!empty($bank_info['sysId']))
        {
            $datapost=[];
            $datapost['action'] ='SdkCardClean';
            $datapost['version'] =ZF_VERSION;
            $datapost['merSn'] = MERCHANT_ID;//大商户号
            $datapost['userCode'] = $user_ext['userCode'];//子商编号
            $datapost['cardType'] = 2;//银行卡类型
            $datapost['sysId'] = $bank_info['sysId'];//银行卡协议号
            $sing=md5('['.$datapost['version'].']|['.$datapost['userCode'].']|['.$datapost['sysId'].']|['.$datapost['cardType'].']|['.$datapost['merSn'].']'.ZF_SIGN);
            $datapost['Sign'] =$sing;

            $result=Lib::httpPostUrlEncode(ZF_URL,$datapost);
            $res= json_decode($result,true);
            if ($res['error'] == 0){
                DBQ::del('credit_card',['id' => $bank_id]);
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }

    }
    //设置信用卡状态
    public function edit($defaultData ,$where)
    {

        $res = DBQ::getRow('credit_card', '*', [
            'is_default' =>1,
            'user_id ' => $where['user_id'],
            'user_type ' => 2
        ]);
        if($res){
            DBQ::upd('credit_card',['is_default' => 0], [
                'id' =>$res['id'],
            ]);
        }
        return  DBQ::upd('credit_card',$defaultData, $where);
    }
    //代理信用卡鉴权消费
    public function SdkPayAuthPay($data)
    {

        if (!\ctype_digit($data['UID'])) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => 'UID不合法'
            );
        }
        if (empty($data['card_no'])) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '银行卡号不能为空'
            );
        }
        if (empty($data['pay_password'])) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '支付密码不能为空'
            );
        }

        // 验证支付密码
        $condition = null;
        $condition['AND']['id'] = $data['UID'];
        $condition['AND']['pay_password'] = Lib::compilePassword($data['pay_password']);
        $row = DBQ::getRow('agent', '*', $condition);
        if (empty($row)){
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '支付密码有误！'
            );
        }

        $user_ext = DBQ::getRow('agent_ext', '*', [
            'agent_id' => $data['UID']
        ]);

        // 验证代理是否与卡匹配
        $condition = null;
        $condition['AND']['card_no'] = Lib::aesEncrypt($data['card_no']);
        $condition['AND']['user_id'] = $data['UID'];
        $condition['AND']['status'] = 1;
        $conditIon['ADN']['user_type'] = 2;
        $card_info = DBQ::getRow('credit_card', '*', $condition); // 信用卡
        if (empty($card_info)){
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '银行卡不存在！'
            );
        }

        $userOrderSn = Lib::createOrderNo();
        $sysId = $card_info['sysId'];

        //md5,[version]|[userCode]|[sysId]|[cardType]|[userOrderSn]|[merSn]入款密钥
        $sdk_sign = md5('['.ZF_VERSION.']|['.$user_ext['userCode'].']|['.$sysId.']|[2]|['.$userOrderSn.']|['.MERCHANT_ID.']'.ZF_SIGN_IN);
        $datapost = array(
            'action'    => 'SdkPayAuthPay',
            'version'   => ZF_VERSION,
            'merSn'     => MERCHANT_ID,//大商户号
            'userCode'  => $user_ext['userCode'],//子商编号
            'sysId'     => $sysId,
            'cardType'  => 2,   //银行卡类型
            'userOrderSn'  => $userOrderSn,
            'Sign'      => $sdk_sign
        );

        $result = Lib::httpPostUrlEncode(ZF_URL,$datapost);
        $ret= json_decode($result,true);
        if ($ret['error'] == 0) {
            return array(
                'status' => 'success',
                'code' => 1000,
                'data' => $datapost,
                'msg' => '鉴权消费成功！'
            );
        }else{
            return array(
                'status' => 'fail',
                'code' => 1000,
                'data' => $datapost,
                'msg' => '鉴权消费失败！'
            );
        }
    }

}