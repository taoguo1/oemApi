<?php
namespace App\API\V100\Model;
use Core\Extend\IDAuth;
use Core\DB\DBQ;
use Core\Lib;
use Exception;
use Core\Base\Model;
class CreditCard extends Base
{
    public $payModelPath = null;
    public function __construct() {
        $this->payModelPath = new Pay();
    }
    //查询用户列表
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
            'user_type ' => 1
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
        if (!\ctype_digit($data['user_id'])) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => 'uid不合法'));
        }
        if (empty($data['bank_id']) || !(\ctype_digit($data['bank_id']))) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '银行id不存在或者格式错误'));
        }
        if (empty($data['mobile']) || !(Lib::checkMobile( $data['mobile']))){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '手机号为空或格式错误'));
        }
        if (empty($data['bank_name'])){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '银行卡不能为空!'));
        }
        if (empty($data['real_name'])){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '持卡人姓名不能为空'));
        }
        if (!\ctype_digit($data['card_no'])){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '信用卡号为空或格式错误'));
        }
        if (!\ctype_digit($data['cvn'])){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '安全码不能为空或格式错误'));
        }

        if(!(Lib::isIdCard($data['id_card']))){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '身份证号不能为空或格式错误'));
        }

        if (empty($data['expiry_date'])){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '信用卡有效期不能为空'));
        }
        if (empty($data['smsCode'])){ 
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '短信验证码不能为空'));
        }
        if (empty($data['bankCode'])){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '银行编码不能为空'));
        }
        //判断日期是否正确
        $expiry_data=$data['expiry_date'];

        if(strlen($expiry_data)!=4){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '日期长度不正确'));
        }
        $start_time = strtotime(date('Y-m-d')." 08:00:00");
        $end_time = strtotime(date('Y-m-d')." 22:00:00");
        if (($start_time > time()) || ($end_time < time())) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '绑卡时间为8:00至22:00'));
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

        //$cardVerification=$this->cardVerification($data['real_name'],$data['card_no'],$data['id_card'],$data['mobile']);
        /*if (!$cardVerification){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '身份证银行卡认证失败'));
        } */
        $where['user_id'] = $data['user_id'];
        $where['user_type'] = 1;
        $where['card_no'] = Lib::aesEncrypt($data['card_no']);
        $isCard = DBQ::getRow('credit_card', '*', $where);

/*         if($isCard['status'] == -1) {
            $isCard['status']=$this->card_status($isCard,$data['user_id']);
        } */
        if($isCard['status'] == 1) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '信用卡号已绑定'));
        }
        //验证输入信息是否与注册信息一致
/*         $condition = null;
        $condition['AND']['id'] = $data['user_id'];
        $condition['AND']['id_card'] = Lib::aesEncrypt($data['id_card']);
        $result = DBQ::getRow('user', '*', $condition);
        if (empty($result)) Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '验证输入信息与注册信息不一致')); */

 
        $dictionary = Lib::loadFile('Config/Dictionary.php');
        $default = DBQ::getRow('credit_card', '*', ['user_id'=>$data['user_id'],'user_type' => 1]);
        if(empty($default))$creditCard_data['is_default'] = 1;//没信用卡设置为默认 

        //鉴权信用卡接口
        $user_info = DBQ::getRow('user_ext','*',['user_id' => $data['user_id']]);
        if (empty($user_info['userCode']))
        {
            Lib::SdkUserReg($user_info['user_id']);
            $user_info = DBQ::getRow('user_ext','*',['user_id' => $data['user_id']]);
        }
        //判断提交商户信息是否超过五分钟
        $charge_time = Lib::getMs()-$user_info['authTime'];
        if ($charge_time < 5*60*1000) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '实名认证后五分钟后才可绑卡！'));  
        }
        $mobile = $data['mobile'];
        $bankNo = $data['card_no'];
        $cvn = Lib::aesEncrypt($data['cvn']);
        $orderId = $data['orderId'];
        $smsCode = $data['smsCode'];
        $bankCode = $data['bankCode'];
        //银行卡四要素认证
        $user_info_auth = DBQ::getRow('user','*',['id' => $data['user_id']]);
        $bankNo = \preg_replace('# #','',$bankNo);
        if (!empty($user_info_auth)) {
            $user_info_auth['id_card'] = Lib::aesDecrypt($user_info_auth['id_card']);
        }
/*         $cardVerification=$this->cardVerification($user_info_auth['real_name'],$bankNo,$user_info_auth['id_card'],$mobile);
        if (empty($cardVerification) || $cardVerification['status']=='fail') {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '银行卡信息认证失败'));
        }  */
        $authBankSta = Lib::bankAuthAli($user_info_auth['real_name'],$user_info_auth['id_card'],$bankNo,$mobile);
        if (empty($authBankSta)) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '银行卡认证失败'));
        }
        $authBank = json_decode($authBankSta,true);
        if (!empty($authBank['respCode']) && $authBank['respCode'] != '0000') {
            $msg = !empty($authBank['respMessage']) ? $authBank['respMessage'] : '银行卡认证失败！';
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => $msg));
        }
        
        
        $expiry_date = \str_replace('/','',$data['expiry_date']);
        if (!empty($data['smSign']) && $data['smSign'] == 'secTag') 
        {
            $user_info = DBQ::getRow('user','*',['id' => $data['user_id']]);
            //验证短信验证码
            $sms_result = $this-> checkCodeValidity($mobile, $data['smsCode'],$data['appid'], $check_reg = true, $type = 10);
            if (empty($data['sysId'])){
                Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '绑卡失败，协议号不能为空'));
            }
            if ($sms_result['status'] == 'success')
            {
                //插入账单
                $orderNomber = Lib::createOrderNo();
                if (empty($isCard) || $isCard['status'] != 2) {
                    $sysId = isset($data['sysId']) ? $data['sysId'] : '';
                    $creditCard_data = [
                        'user_id' => $data['user_id'],
                        'bank_id' => $data['bank_id'],
                        'bank_name' => $data['bank_name'],
                        'mobile' => Lib::aesEncrypt($data['mobile']),
                        'lb_mobile' => \substr_replace($data['mobile'], '****', 3, 4),
                        'real_name' => Lib::aesEncrypt($data['real_name']),
                        'card_no' => Lib::aesEncrypt($data['card_no']),
                        'cvn' => $cvn,
                        'id_card' => Lib::aesEncrypt($data['id_card']),
                        'expiry_date' => Lib::aesEncrypt(\str_replace('/', '', $data['expiry_date'])),
                        'bill_day' => $data['bill_day'],
                        'channel_type' => 2,
                        'status' => 1,
                        'channel_code' => $dictionary['channel'][2]['code'],
                        //'sysOrderSn' => $sdk_ret['sysOrderSn'],//平台订单号
                        'userOrderSn' => $orderNomber,//商户订单号
                        'sysId' => $sysId,//协议号
                        'create_time' => Lib::getMs(),
                        'user_type'   => 1
                    ];
                    DBQ::add("credit_card", $creditCard_data);
                    /*
                    //添加用户流水
                    $user_account = array(
                        'user_id' =>  $data['user_id'],
                        'amount' => VALIDATECARD_POUNDAGE*(-1),
                        'order_sn' => $orderNomber,
                        'desciption' => "鉴权手续费",
                        'in_type' => 1,
                        'channel' => 2, // 1易联2易宝
                        'is_pay' => '1', // -1未支付 1已支付
                        'create_time' => Lib::getMs()
                    );
                    DBQ::add('user_account', $user_account);
                    //添加账单表记录
                    $bill_data = array(
                        'user_id' => $data['user_id'],
                        'plan_id' => 0,
                        'amount' => VALIDATECARD_POUNDAGE,
                        'poundage' => 0,
                        'rpoundage' => 0,
                        'bill_type' => 5,
                        'card_type' => 1,
                        'bank_id' => $data['bank_id'],
                        'bank_name' => $data['bank_name'],
                        'card_no' => Lib::aesEncrypt($data['card_no']),
                        'status' => 1,//执行状态(1成功-1失败0默认状态)
                        'order_sn' => $orderNomber,
                        'channel' => 2,//1易联2易宝
                        'is_pay' => 1,
                        'sysOrderSn' => $orderNomber,//平台订单号
                        'create_time' => Lib::getMs()
                    );
                    DBQ::add('bill', $bill_data);
                    
                    $logsData = array('signMsg' => '绑定信用卡插入账单');
                    Lib::recordLogs(LOGS_PATH,array_merge($bill_data,$logsData));
                    //同步数据到总服务器
                    $bill_data = [
                        'user_id' => $data['user_id'],
                        'amount' => VALIDATECARD_POUNDAGE,
                        'bill_type' => 5,
                        'card_type' => 1,
                        'status' => 1,
                        'order_sn' => $orderNomber,
                        'is_pay' => 1,
                        'create_time' => Lib::getMs(),
                        'poundage' => 0,
                        'appid' => $data["appid"],
                        'sysOrderSn' => $orderNomber,//平台订单号
                        'agent_id' => 0,
                        'orderId'  => $orderId,
                        'transaction_id' => 0,
                        'version'=>OEM_CTRL_URL_VERSION
                    ];
                    Lib::httpPostUrlEncode(MAINURL, $bill_data);
                    */
                } else {
                    //Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '该卡已绑定,未认证！'));
                    $credit_card = ['userOrderSn' => $orderNomber];
                    DBQ::upd('credit_card', $credit_card, ['card_no' => Lib::aesEncrypt($data['card_no']),'user_id' => $data['user_id'],'user_type' => 1]);
                    //更新订单表
                    $bill_data  = ['order_sn' =>$orderNomber];
                    $bill_sdk = DBQ::getRow('bill','*',['order_sn' => $isCard['userOrderSn']]);
                    DBQ::upd('bill', $bill_data, ['id' => $bill_sdk['id']]);
                }
                Lib::outputJson(array('status'=>'success','code'=>1000,'msg'=>'绑定成功'));
            } else {
                Lib::outputJson(array('status'=>'fail','code'=>1000,'msg'=>'绑定失败！验证码有误'));
            }
        } else {
            //
            if (empty($data['orderId']) && empty($data['sysId'])) {
                Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '短信验证码有误！'));
            }
            if (empty($data['orderId'])){
                Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '鉴权订单号不能为空'));
            }
            //md5,[version]|[userCode]|[bankPhone]|[cardType]|[bankNo]|[bankCvn]|[bankValidityDay]|[merSn]|[orderId]|[smsCode]入款密钥
            $sdk_sign = md5('['.ZF_VERSION.']|['.$user_info['userCode'].']|['.$mobile.']|[2]|['.$bankNo.']|['.$data['cvn'].']|['.$expiry_date.']|['.MERCHANT_ID.']|['.$orderId.']|['.$smsCode.']'.ZF_SIGN_IN);
            $datapost = array(
                'action'    => 'SdkPayAuthConfirm',
                'version'   => ZF_VERSION,
                'merSn'     => MERCHANT_ID,//大商户号
                'userCode'  => $user_info['userCode'],//子商编号
                'cardType'  => 2,//银行卡类型
                'bankNo'    => $bankNo,//银行卡号
                'bankPhone' => $mobile,//绑定手机号码
                'bankCvn'   => $data['cvn'],//信用卡后三位
                'orderId'   => $orderId,//短信订单号
                'smsCode'   => $smsCode,//验证码
                'bankCode'  => $bankCode,//银行编码
                'bankValidityDay' => $expiry_date,//信用卡有效期
                'Sign'      => $sdk_sign
            );
            $this->payModelPath->balanceUserRate($user_info['userCode']);
            $ret=Lib::httpPostUrlEncode(ZF_URL,$datapost);
            $sdk_ret = json_decode($ret,true);
            $logsData = array('signMsg' => '请求情况');
            Lib::tempLog('card.txt',$sdk_ret,'Pay');
            if ($sdk_ret['error'] == 0)
            {
                $orderNomber = Lib::createOrderNo();
                if (empty($isCard) || $isCard['status'] != 2) {
                    $sysId = isset($sdk_ret['sysId']) ? $sdk_ret['sysId'] : '';
                    $creditCard_data = [
                        'user_id' => $data['user_id'],
                        'bank_id' => $data['bank_id'],
                        'bank_name' => $data['bank_name'],
                        'mobile' => Lib::aesEncrypt($data['mobile']),
                        'lb_mobile' => \substr_replace($data['mobile'], '****', 3, 4),
                        'real_name' => Lib::aesEncrypt($data['real_name']),
                        'card_no' => Lib::aesEncrypt($data['card_no']),
                        'cvn' => $cvn,
                        'id_card' => Lib::aesEncrypt($data['id_card']),
                        'expiry_date' => Lib::aesEncrypt(\str_replace('/', '', $data['expiry_date'])),
                        'bill_day' => $data['bill_day'],
                        'channel_type' => 2,
                        'status' => 1,
                        'channel_code' => $dictionary['channel'][2]['code'],
                        //'sysOrderSn' => $sdk_ret['sysOrderSn'],//平台订单号
                        'userOrderSn' => $orderNomber,//商户订单号
                        'sysId' => $sysId,//协议号
                        'create_time' => Lib::getMs(),
                        'user_type'   => 1
                    ];
                    Lib::tempLog('card.txt',$creditCard_data,'Pay');
                    DBQ::add("credit_card", $creditCard_data);
                  /*   //添加用户流水
                    $user_account = array(
                        'user_id' =>  $data['user_id'],
                        'amount' => VALIDATECARD_POUNDAGE*(-1),
                        'order_sn' => $orderNomber,
                        'desciption' => "鉴权手续费",
                        'in_type' => 1,
                        'channel' => 2, // 1易联2易宝
                        'is_pay' => '1', // -1未支付 1已支付
                        'create_time' => Lib::getMs()
                    );
                    DBQ::add('user_account', $user_account);
                    //添加账单表记录
                    $bill_data = array(
                        'user_id' => $data['user_id'],
                        'plan_id' => 0,
                        'amount' => VALIDATECARD_POUNDAGE,
                        'poundage' => 0,
                        'rpoundage' => 0,
                        'bill_type' => 5,
                        'card_type' => 1,
                        'bank_id' => $data['bank_id'],
                        'bank_name' => $data['bank_name'],
                        'card_no' => Lib::aesEncrypt($data['card_no']),
                        'status' => 1,//执行状态(1成功-1失败0默认状态)
                        'order_sn' => $orderNomber,
                        'channel' => 2,//1易联2易宝
                        'is_pay' => 1,
                        'sysOrderSn' => $orderNomber,//平台订单号
                        'create_time' => Lib::getMs()
                    );
                    DBQ::add('bill', $bill_data);
                    $logsData = array('signMsg' => '绑定信用卡插入账单');
                    Lib::recordLogs(LOGS_PATH,array_merge($bill_data,$logsData));
                    //同步数据到总服务器
                    $bill_data = [
                        'user_id' => $data['user_id'],
                        'amount' => VALIDATECARD_POUNDAGE,
                        'bill_type' => 5,
                        'card_type' => 1,
                        'status' => 1,
                        'order_sn' => $orderNomber,
                        'is_pay' => 1,
                        'create_time' => Lib::getMs(),
                        'poundage' => 0,
                        'appid' => $data["appid"],
                        'sysOrderSn' => $orderNomber,//平台订单号
                        'agent_id' => 0,
                        'orderId'  => $orderId,
                        'transaction_id' => 0,
                        'version'=>OEM_CTRL_URL_VERSION
                    ];
                    Lib::httpPostUrlEncode(MAINURL, $bill_data); */
                } else {
                    //Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '该卡已绑定,未认证！'));
                    $credit_card = ['userOrderSn' => $orderNomber];
                    DBQ::upd('credit_card', $credit_card, ['card_no' => Lib::aesEncrypt($data['card_no']),'user_id' => $data['user_id'],'user_type' => 1]);
                    //更新订单表
                    $bill_data  = ['order_sn' =>$orderNomber];
                    $bill_sdk = DBQ::getRow('bill','*',['order_sn' => $isCard['userOrderSn']]);
                    DBQ::upd('bill', $bill_data, ['id' => $bill_sdk['id']]);
                }

                Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '绑定成功！'));

            } else {
                $this->delCreditCard($isCard['id']);
                Lib::outputJson(array('status'=>'fail','code'=>1000,'msg'=>'绑定失败！'.$sdk_ret['error_msg']));
            }
        }

    }
    //删除，解绑信用卡
    public function deletions($where)    {
        $cardInfo = DBQ::getRow('credit_card','*',['id' => $where['id']]);
        if (!empty($cardInfo)) {
            $condition =[];
            $condition['AND']['user_id'] = $cardInfo['user_id'];
            $condition['AND']['card_no'] = $cardInfo['card_no'];
            $condition['AND']['status[!]'] = 3;
            $plan_info = DBQ::getRow('plan','*',$condition);
            if (!empty($plan_info)){
                Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '删除失败! 您的卡有任务在进行中'));
            }else{
                $result = $this->delCreditCard($where['id']);
                if ($result)
                {
                    $arr = DBQ::getRow('credit_card', '*', [
                        'status' =>1,
                        'user_id ' => $where['user_id'],
                        'user_type ' => 1
                    ]);
                    if($arr){
                        DBQ::upd('credit_card',[
                            'is_default' =>1,
                        ], ['id'=>$arr['id'],'user_id ' => $where['user_id'],'user_type ' => 1]);
                    }
                    Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '删除成功'));
                } else {
                    Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '删除失败'));
                }
            }
        }
        
    }
    //请求支付解绑
    public function delCreditCard($bank_id,$user_type = 1)
    {
        $bank_info = DBQ::getRow('credit_card','*',['id' => $bank_id]);
        $user_ext  = DBQ::getRow('user_ext','*',['user_id' => $bank_info['user_id']]);
        if (empty($user_ext['userCode'])) {
            Lib::SdkUserReg($bank_info['user_id']);
            $user_ext  = DBQ::getRow('user_ext','*',['user_id' => $bank_info['user_id']]);
        }
/*        if (!empty($bank_info['sysId']))
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
        }*/
        DBQ::del('credit_card',['id' => $bank_id]);
        return true;

    }
    //设置信用卡状态
    public function edit($defaultData ,$where)
    {
        $res = DBQ::getRow('credit_card', '*', [
            'is_default' =>1,
            'user_id ' => $where['user_id'],
             'user_type ' => 1
        ]);
        if($res){
            DBQ::upd('credit_card',['is_default' => 0], [
                'id' =>$res['id'],
            ]);
        }
        return  DBQ::upd('credit_card',$defaultData, $where);
    }
    //信用卡鉴权短信
    public function creditCardAuthSms($data)
    {
        if (empty($data['user_id'])) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => 'uid不合法'));
        }
        if (!\ctype_digit($data['card_no'])){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '信用卡号为空或格式错误'));
        }
        if (empty($data['mobile']) || !(Lib::checkMobile( $data['mobile']))){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '手机号为空或格式错误'));
        }
        if (!\ctype_digit($data['cvn']) || $data['cvn'] > 999){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '安全码不能为空或格式错误'));
        } 
        if(empty($data['bankCode'])){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '银行编码不能为空或格式错误'));
        }
        if (empty($data['expiry_date'])){
            
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '信用卡有效期不能为空'));
        }
        if (empty($data['appid'])){

            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => 'appid不能为空！'));
        }
        //判断日期是否正确
        $expiry_data = \str_replace('/','',$data['expiry_date']);
        
        if(strlen($expiry_data)!=4){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '日期长度不正确'));
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
        $start_time = strtotime(date('Y-m-d')." 08:00:00");
        $end_time = strtotime(date('Y-m-d')." 22:00:00");
        if (($start_time > time()) || ($end_time < time())) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '绑卡时间为8:00至22:00'));
        }
        $user_info = [];
        $utype = $data['utype'];
         //银行卡四要素鉴权1
        if ($utype == 1) {
            $user_info = DBQ::getRow('user','*',['id' => $data['user_id']]);
        } else {
            $user_info = DBQ::getRow('agent','*',['id' => $data['user_id']]);
        }
        if (!empty($user_info)) {
            $user_info['id_card'] = Lib::aesDecrypt($user_info['id_card']);
            
        }
        $data['card_no']=\preg_replace('# #','',$data['card_no']);
/*       $cardVerification=$this->cardVerification($user_info['real_name'],$data['card_no'],$user_info['id_card'],$data['mobile']);
        if (empty($cardVerification) || $cardVerification['status']=='fail'){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '银行卡信息认证失败'));
        } */ 
        $authBankSta = Lib::bankAuthAli($user_info['real_name'],$user_info['id_card'],$data['card_no'],$data['mobile']);
        if (empty($authBankSta)) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '银行卡认证失败'));
        }

        $authBank = json_decode($authBankSta,true);
        //日志
        Lib::tempLog('cardAL_sms.txt',$user_info,'Pay');
        Lib::tempLog('cardAL_sms.txt',$authBank,'Pay');
        if (!empty($authBank['respCode']) && $authBank['respCode'] != '0000') {
            $msg = !empty($authBank['respMessage']) ? $authBank['respMessage'] : '银行卡认证失败！';
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '绑卡失败，'.$msg));
        }
        
        //请求发送银行卡短息接口
        $mobile = $data['mobile'];
        $bankNo = $data['card_no'];
        $cvn    = $data['cvn'];
        $bankCode = $data['bankCode'];
        $appid = $data['appid'];
        $userCode = [];
        $userInfo = [];
        if ($utype == 1) {
            $userInfo = DBQ::getRow('user', '*', ['id' => $data['user_id']]);
            $userExt = DBQ::getRow('user_ext', '*', ['user_id' => $data['user_id']]);
            if (empty($userExt['userCode'])) {
                Lib::SdkUserReg($data['user_id']);
            }
            $userCode =  DBQ::getRow('user_ext', '*', ['user_id' => $data['user_id']]);
        } else {
            $userInfo = DBQ::getRow('agent', '*', ['id' => $data['user_id']]);
            $userExt = DBQ::getRow('agent_ext', '*', ['agent_id' => $data['user_id']]);
            if (empty($userExt['userCode'])) {
                Lib::SdkAgentReg($data['user_id']);
            }
            $userCode =  DBQ::getRow('agent_ext', '*', ['agent_id' => $data['user_id']]);
        }
        if (empty($userInfo) || empty($userExt)) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '用户信息异常!'));
        }
        
        //判断提交商户信息是否超过五分钟
        $charge_time = Lib::getMs()-$userCode['authTime'];
        if ($charge_time < 5*60*1000) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '实名认证后五分钟后才可绑卡！'));
        }
        //md5,[version]|[userCode]|[bankPhone]|[cardType]|[bankNo]|[bankCvn]|[bankValidityDay]|[merSn]入款密钥
        $sdk_sign = md5('['.ZF_VERSION.']|['.$userCode['userCode'].']|['.$mobile.']|[2]|['.$bankNo.']|['.$cvn.']|['.$expiry_data.']|['.MERCHANT_ID.']'.ZF_SIGN_IN);
        $datapost = array(
            'action'    => 'SdkPayAuthSms',
            'version'   => ZF_VERSION,
            'merSn'     => MERCHANT_ID,//大商户号
            'userCode'  => $userCode['userCode'],//子商编号
            'cardType'  => 2,//银行卡类型
            'bankNo'    => $bankNo,//银行卡号
            'bankPhone' => $mobile,//绑定手机号码
            'bankCvn'   => $cvn,//信用卡后三位
            'bankValidityDay' => $expiry_data,//信用卡有效期
            'bankCode' => $bankCode,
            'Sign'      => $sdk_sign
        );
        $ret=Lib::httpPostUrlEncode(ZF_URL,$datapost);
        $sdk_ret = json_decode($ret,true);
        $orderId = !empty($sdk_ret['orderId']) ? $sdk_ret['orderId'] : '';
        $channelOrderId = !empty($sdk_ret['channelOrderId']) ? $sdk_ret['channelOrderId'] : '';
        $sysId  = !empty($sdk_ret['sysId']) ? $sdk_ret['sysId'] : '';
        //日志
        Lib::tempLog('card_sms.txt',$datapost,'Pay');
        Lib::tempLog('card_sms.txt',$sdk_ret,'Pay');
        if ($sdk_ret['error'] == 0) {
            //Lib::outputJson(array('status' => 'success', 'code' => 1000, 'data' => ['orderId'=>$orderId,'sysId' => '']));
            if (!empty($sdk_ret['sysId'])) {
                //发送短信验证码
                $resultObj = Lib::sendSms($mobile,10,$appid,'',"SMS_129470288",$appid);
                $result = json_decode($resultObj,true);
                if ($result['Code'] == 'OK') {
                    Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => $sdk_ret['error_msg'], 'data' => ['orderId'=>$orderId,'sysId' => $sysId,'$channelOrderId'=>$channelOrderId],'smSign' => 'secTag'));
                } else {
                    Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => "短信发送失败！", 'data' => ['orderId'=>'','sysId' => ''],'smSign' => ''));
                }
            } else {
                Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => $sdk_ret['error_msg'],'data' => ['orderId'=> $orderId,'sysId' => $sysId],'smSign' => ''));
            }
        } else {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'data' => ['orderId'=>'','sysId' => ''],'msg' => '绑卡失败：'.$sdk_ret['error_msg']));
        }
    }

}