<?php
/**
 * Created by PhpStorm.
 * User: hqf
 * Date: 2018/3/5
 * Time: 9:23
 */

namespace App\API\V100\Model;

use Core\Base\Model;
use Core\Lib;
use Core\DB\DBQ;
use Exception;
use Core\Extend\Redis;

class CardPay extends Model
{
    public $payModelPath = null;
    public function __construct() {
        parent::__construct();
        $this->payModelPath = new Pay();
    }
    
    //payauth
    public function payauth($data)
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
        $row = DBQ::getRow('user', '*', $condition);
        if (empty($row)){
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '支付密码有误！'
            );
        }
        
        $user_ext = DBQ::getRow('user_ext', '*', [
            'user_id' => $data['UID']
        ]);
        
        // 验证用户是否与卡匹配
        $condition = null;
        $condition['AND']['card_no'] = Lib::aesEncrypt($data['card_no']);
        $condition['AND']['user_id'] = $data['UID'];
        $condition['AND']['status'] = 1;
        $conditIon['ADN']['user_type'] = 1;
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
//         return array(
//             'status' => 'fail',
//             'code' => 1000,
//             'data' => $datapost,
//             'msg' => '银行卡不存在！'
//         );
        $ret_post = Lib::httpPostUrlEncode(ZF_URL,$datapost);
        $ret = json_decode($ret_post, true);
        Lib::tempLog('payauth.log', $ret,'Pay');
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

    
    //用户充值
    public function userRecharge($data)
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
        if (empty($data['money']) || (\ctype_digit($data))) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '充值金额格式错误'
            );
        }
        if ($data['money'] < 100) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '充值金额最少为100元'
            );
        }
        if ($data['money'] > 1000) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '充值金额上限为1000元'
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
        $row = DBQ::getRow('user', '*', $condition);
        if (empty($row))
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '支付密码有误！'
            );
        $start_time = strtotime(date('Y-m-d')." 08:00:00");
        $end_time = strtotime(date('Y-m-d')." 22:00:00");
        if (($start_time > time()) || ($end_time < time())) {
            return array('status' => 'fail', 'code' => 1000, 'msg' => '充值时间为8:00至22:00');
        }

        // 验证用户是否与卡匹配
        $condition = null;
        $condition['AND']['card_no'] = Lib::aesEncrypt($data['card_no']);
        $condition['AND']['user_id'] = $data['UID'];
        $condition['AND']['status'] = 1;
        $conditIon['ADN']['user_type'] = 1;
        $card_info = DBQ::getRow('credit_card', '*', $condition); // 信用卡
        /*
        return array(
            'status' => 'fail',
            'code' => 1000,
            'msg' => '银行卡不存在！',
            'data' => $card_info,
        );
        */
        if (empty($card_info))
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '银行卡不存在！'
            );

        $user_ext = DBQ::getRow('user_ext', '*', [
            'user_id' => $data['UID']
        ]);
        //判断绑卡是否超过五分钟
        $charge_time = Lib::getMs() - $card_info['create_time'];
        if ($charge_time < 5*60*1000) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '绑卡后五分钟才可充值！'));
        }
        if (empty($card_info['userCode'])) {
            Lib::SdkUserReg($data['UID']);
            $user_ext = DBQ::getRow('user_ext', '*', [
                'user_id' => $data['UID']
            ]);
        }
        $order_no = Lib::createOrderNo(); // 订单号
        //$cardType = empty($debit_result) ? 2 : 1; // 和接口的类型相反
        $cardType = 2;
        //$attach_data = $data['UID'] . "|" . $card_info['bank_id'] . "|" . $card_info['bank_name'] . "|" . $card_info['card_no'] . "|" . $cardType . "|" . $data['appid'];
        $notifyUrl = ZF_DIFF_PATH . 'notice/czResponce/?appid=' . $data['appid'];
        //$payModel = new Pay();
        //$notifyUrlBefore = FRAME_OPEN_URL . $data['appid']; // 同步跳转
        
        /**************************************************************************/
        $dataPost = array(
            'userCode' => $user_ext['userCode'],
            'sysId' => $card_info['sysId'],
            'cardType'=> 2,
        );

        $result = $this->payModelPath->payCardQuery($dataPost);
        /*
        return array(
            'status' => 'fail',
            'code' => 10010,
            'msg' => '该银行卡没有鉴权',
            'iscan' => $result,
        );
        */
        if(isset($result['isCan']) && $result['isCan'] == '否'){
            return array(
                'status' => 'fail',
                'code' => 10010,
                'msg' => '该银行卡没有鉴权',
                'isCan' => 0,
            );
        }
        /*************************************************************************/
        
        $data_sdk = array(
            'version' => ZF_VERSION,
            'userCode' => $user_ext['userCode'],
            'sysId' => $card_info['sysId'],
            //'notifyUrl' => $notifyUrl,
            'notifyUrl' => '',
            'cardType' => $cardType,
            'orderType' => 'C',
            'amount' => $data['money'],
            'userOrderSn' => $order_no,
            'attach' => '',
            //'notifyUrlBefore' => $notifyUrlBefore
            'notifyUrlBefore' => ''
        );
        $this->payModelPath->balanceUserRate($user_ext['userCode']);
        $result = $this->payModelPath->payDs($data_sdk);
        //充值改同步
        if ($result['error'] == 0) 
        {
            $user_id = $data['UID'];
            $bank_id = $card_info['bank_id'];
            $bank_name = $card_info['bank_name'];
            $card_no = $card_info['card_no'];
            $cardType = 2;
            $amount = $data['money'];
            $appid = !empty($data['appid']) ? $data['appid'] : '';
            $order_sn = !empty($result['userOrderSn']) ? $result['userOrderSn'] : '';
            // 用户账户充值金额
            $poundage = $amount * DEPOSIT_POUNDAGE / 10000; // 手续费
            $user_account = array(
                'user_id' => $user_id,
                'amount' => $amount,
                'order_sn' => $order_sn,
                'desciption' => "充值",
                'in_type' => 1,
                'channel' => 2, // 1易联2易宝
                'is_pay' => '1', // -1未支付 1已支付
                'create_time' => Lib::getMs()
            );
            DBQ::add('user_account', $user_account);
            // 用户账户充值手续费
            $user_account_poundage = array(
                'user_id' => $user_id,
                'amount' => $poundage * (- 1),
                'order_sn' => $order_sn,
                'desciption' => "充值手续费",
                'in_type' => 1,
                'channel' => 2, // 1易联2易宝
                'is_pay' => '1', // -1未支付 1已支付
                'create_time' => Lib::getMs()
            );
            DBQ::add('user_account', $user_account_poundage);
            

            $ext_money = $user_ext['balance'] + $amount - $poundage;
            DBQ::upd('user_ext', [
                'balance' => $ext_money
            ], [
                'user_id' => $user_id
            ]);
            
            // 添加账单表记录
            $bill_data = array(
                'user_id' => $user_id,
                'plan_id' => 0,
                'agent_id' => 0,
                'amount' => $amount,
                'poundage' => $poundage,
                'rpoundage' => 0,
                'bill_type' => 4,
                'card_type' => $cardType,
                'bank_id' => $bank_id,
                'bank_name' => $bank_name,
                'card_no' => $card_no,
                'status' => 1,
                'is_pay' => '1', // 未支付
                'order_sn' => $order_sn,
                'sysOrderSn' => $result['sysOrderSn'],
                'channel' => 2, // 1易联2易宝
                'create_time' => Lib::getMs()
            );
            DBQ::add('bill', $bill_data);
            //添加日志
            $logsData = array('signMsg' => '绑定信用卡回调插入账单');
			Lib::tempLog('recharge.txt',$bill_data,'Pay');

            //同步数据到总服务器
            $mbill_data = [
                'user_id' => $user_id,
                'amount' => $amount,
                'bill_type' => 4,
                'card_type' => $cardType,
                'bank_id' => $bank_id,
                'bank_name' => $bank_name,
                'card_no' => $card_no,
                'status' => 1,
                'order_sn' => $order_sn,
                'is_pay' => '1',
                'create_time' => Lib::getMs(),
                'poundage' => $poundage,
                'appid' => $data['appid'],
                'agent_id' => 0,
                'version'=>OEM_CTRL_URL_VERSION
            ];
            
            Lib::httpPostUrlEncode(MAINURL, $mbill_data);
            return array(
                'status' => 'success',
                'code' => 1000,
                'msg' => '请求成功！',
            );
        } else {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '充值失败:' . $result['error_msg'],
            );
        }
    }

    //用户提现判断金额是否在规定范围内
    public function checkMoney($data)
    {

        if ($data['money'] == '' || $data['money'] == '0.00' || $data['money'] == null) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '提现金额不能为空'
            );
        }
        // 获取可提现金额
        $userMoney = lib::getMayUseMoney($data['UID']);

        if ($data['money'] > $userMoney) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '提现金额不能大于账户余额'
            );
        }

        if ($data['money'] < 100) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '提现金额最少为100元'
            );
        }

        if ($data['money'] > 3000) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '提现金额最大为3000元'
            );
        }
        return array(
            'status' => 'success'
        );
    }

    //用户充值判断金额是否在范围内
    public function rechargeCheckMoney($data)
    {
        if ($data['money'] == '' || $data['money'] == '0.00' || $data['money'] == null) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '充值金额不能为空'
            );
        }
        if ($data['money'] < 100) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '充值金额最少为100元'
            );
        }
        return array(
            'status' => 'success'
        );
    }

    //判断收款金额是否在范围内
    public function CheckGetMoney($data)
    {
        if ($data['money'] == '' || $data['money'] == '0.00' || $data['money'] == null) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '收款金额不能为空'
            );
        }
        if ($data['money'] < 1000) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '收款金额最少为1000元'
            );
        }
        return array(
            'status' => 'success'
        );
    }

    //用户提现
    public function takeCash($data)
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
        if (empty($data['money'])) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '提现金额不能为空'
            );
        }
        if (empty($data['pay_password'])) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '支付密码不能为空'
            );
        }
        if ($data['money'] > 3000) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '提现金额上限为3000元'
            );
        }
        if ($data['money'] < 10) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '最小的提现金额为10元'
            );
        }

        if ((date('w') == 0) || (date('w') == 6)) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '非工作日不允许提现'
            );
        }
        if ((date('H') < 8) || (date('H') > 16)) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '每天的提现时间为8点-17点'
            );
        }

        $userMoney = Lib::getMayUseMoney($data['UID']);
        if ($userMoney < $data['money']){
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '账户余额不足！'
            );
        }

        //总平台查询子商户余额
        $mmoney=0;

        $user=DBQ::getRow('user_ext','*',['user_id'=>$data['UID']]);

        $Mmoney=$this->payModelPath->SdkBalanceQuery($user['userCode']);

        if($Mmoney['error']!=0){
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => $Mmoney['error_msg']
            );
        }else{
            $mmoney=!empty($Mmoney['balance'])?$Mmoney['balance']:0;
        }
        $NowMoney=DBQ::getSum('user_account','amount',['user_id'=>$data['UID']]);
        if ($mmoney != $NowMoney ){
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '账户流水异常,请联系管理员核对！'
            );
        }
        // 验证支付密码
        $condition = null;
        $condition['AND']['id'] = $data['UID'];
        $condition['AND']['pay_password'] = Lib::compilePassword($data['pay_password']);
        $row = DBQ::getRow('user', '*', $condition);
        if (empty($row))
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '支付密码有误！'
            );
        // 验证绑卡信息
        $condition = null;
        $condition['AND']['card_no'] = Lib::aesEncrypt($data['card_no']);
        $condition['AND']['user_id'] = $data['UID'];
        $condition['AND']['user_type'] = 1;
        $condition['AND']['status'] = 1;
        $debit_result = DBQ::getRow('credit_card', '*', $condition); // 储蓄卡
        if (empty($debit_result))
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '银行卡信息有误！'
            );
        //判断绑卡是否超过五分钟
        $charge_time = Lib::getMs() - $debit_result['create_time'];
        if ($charge_time < 5*60*1000) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '绑卡后五分钟才可提现！'));
        }
        
        
        // 查询单日提现金额
        $condition = null;
        $condition['AND']['user_id'] = $data['UID'];
        $condition['AND']['bill_type'] = 3;
        $condition['AND']['create_time[>=]'] = strtotime(date('Y-m-d', time()) . " 00:00:00") * 1000;
        $condition['AND']['create_time[<=]'] = strtotime(date('Y-m-d', time()) . " 23:59:59") * 1000;
        $day_sum_cash = DBQ::getSum('bill', 'amount', $condition);
        if ($day_sum_cash > MAX_WITHDRAW_DAY)
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '单日提现额度已超'
            );

        $userCode = DBQ::getRow('user_ext', [
            'userCode'
        ], [
            'user_id' => $data['UID']
        ]);
        if (empty($userCode['userCode'])) {
            Lib::SdkUserReg($data['UID']);
        }
        $debitcard = DBQ::getRow('credit_card', '*', [
            'user_type' => 1,
            'card_no' => Lib::aesEncrypt($data['card_no']),
            'user_id' => $data['UID']
        ]);
        /**************************************************************************/
        $dataPostAuth = array(
            'userCode' => $userCode['userCode'],
            'sysId' => $debitcard['sysId'],
            'cardType'=> 2,
        );
        $resultAuthRet = $this->payModelPath->payCardQuery($dataPostAuth);
        /*
         return array(
         'status' => 'fail',
         'code' => 10010,
         'msg' => '该银行卡没有鉴权',
         'iscan' => $result,
         );
         */
        if(isset($resultAuthRet['isCan']) && $resultAuthRet['isCan'] == '否'){
            return array(
                'status' => 'fail',
                'code' => 10010,
                'msg' => '该银行卡没有鉴权或者鉴权异常',
                'isCan' => 0,
            );
        }
        /*************************************************************************/
        //用户提现
        // 插入数据库，调用代付请求，如果失败 直接删除，否则 存入redis
        $order_no = Lib::createOrderNo();
        $this->db->pdo->beginTransaction();
        try {

            // 插入用户提现记录
            // 实际提现金额
            $user_account = array(
                'user_id' => $data['UID'],
                'amount' => $data['money'] * (-1),
                'order_sn' => $order_no,
                'desciption' => "提现",
                'in_type' => 1,
                'channel' => 2, // 1易联2易宝
                'is_pay' => '-1', // -1未支付，1已支付
                'status' => '-2',
                'create_time' => Lib::getMs()
            );
            $this->db->insert('user_account', $user_account);
            $req2 = $this->db->id();
            if (!$req2) {
                $this->db->pdo->rollBack();
                return array(
                    'status' => 'fail',
                    'code' => 1000,
                    'msg' => '提现失败'
                );
            } else {
                // 添加账单表记录
                $bill_data = array(
                    'user_id' => $data['UID'],
                    'plan_id' => 0,
                    'agent_id' => 0,
                    'amount' => $data['money'],
                    'poundage' => WITHDRAW_POUNDAGE,
                    'rpoundage' => 0,
                    'bill_type' => 3,
                    'card_type' => 2,
                    'bank_id' => $debit_result['bank_id'],
                    'bank_name' => $debit_result['bank_name'],
                    'card_no' => $debit_result['card_no'],
                    'is_pay' => '-1', // 未支付
                    'status' => -2,
                    'order_sn' => $order_no,
                    'userOrderSn' => $order_no,
                    'sysOrderSn' => '',
                    'channel' => 2, // 1易联2易宝
                    'create_time' => Lib::getMs()
                );
                $this->db->insert('bill', $bill_data);
                $req3 = $this->db->id();
                if ($req3) {
                    // 同步数据到总服务器

                    $bill_data = [
                        'user_id' => $data['UID'],
                        'amount' => $data['money'],
                        'bill_type' => 3,
                        'card_type' => 2,
                        'status' => -2,
                        'order_sn' => $order_no,
                        'is_pay' => '-1',
                        'create_time' => Lib::getMs(),
                        'poundage' => WITHDRAW_POUNDAGE,
                        'appid' => Lib::request("appid"),
                        'agent_id' => 0,
                        'version' => OEM_CTRL_URL_VERSION
                    ];

                    Lib::httpPostUrlEncode(MAINURL, $bill_data);
                    // 请求支付
                    $sdkdata = array(
                        'userCode' => $userCode['userCode'], // 子商编号
                        'cardType' => 2, // 银行卡类型
                        'orderType' => 'T', // 订单类型
                        'sysId' => $debitcard['sysId'], // 订单类型
                        'amount' => $data['money'] - WITHDRAW_POUNDAGE, // 金额
                        'poundage' => WITHDRAW_POUNDAGE, // 手续费 + 还款笔数费
                        'userOrderSn' => $order_no, //
                        // 'notifyUrl' => ZF_DIFF_PATH.'notice/txResponce/?appid='.$data['appid'], //异步通知地址
                        'notifyUrl' => ''
                    );
                    //$sdkpay = new Pay();
                    Lib::tempLog('userTX.txt',$sdkdata,'Pay');
                    $this->payModelPath->balanceUserRate($userCode['userCode']);
                    $sdkres = $this->payModelPath->payDf($sdkdata);
                    Lib::tempLog('userTX.txt',$sdkres,'Pay');
                    if ($sdkres['error'] == 0) {
                        $sysOrderSn = ''; // 平台订单号
                        $order_no = ''; // 订单号
                        if (isset($sdkres['userOrderSn'])) {
                            $order_no = $sdkres['userOrderSn'];
                        }
                        if (isset($sdkres['sysOrderSn'])) {
                            $sysOrderSn = $sdkres['sysOrderSn'];
                        }
                        if ($sdkres['status'] == 'FAILURE') {
                            // 扣除用户金额

                            DBQ::del('user_account', [
                                'order_sn' => $order_no
                            ]);
                            DBQ::del('bill', [
                                'order_sn' => $order_no
                            ]);
                        }
                        /*else {

                            $redis = Redis::instance('plan');
                            $socre = Lib::getMs();
                            $keySign = md5('[' . ZF_VERSION . ']|[' . $sysOrderSn . ']|[' . $order_no . ']|[' . MERCHANT_ID . ']' . ZF_SIGN_OUT); // md5,[version]|[sysOrderSn]|[userOrderSn]|[merSn]入款密钥
                            $js_redis = array(
                                'version' => ZF_VERSION,
                                'merSn' => MERCHANT_ID,
                                'sysOrderSn' => $sysOrderSn,
                                'userOrderSn' => $order_no,
                                'appid' => $data['appid'],
                                'Sign' => $keySign
                            );
                            $redis->zAdd('withdraw', $socre, json_encode($js_redis));
                        }*/
                        $this->db->pdo->commit();
                        //更新账单信息
                        $this->txResponceUpdate($order_no);
                        return array(
                            'status' => 'success',
                            'code' => 10000,
                            'msg' => '提现成功',
                            'data' => $order_no
                        );
                    } else {
                        DBQ::del('user_account', [
                            'order_sn' => $order_no
                        ]);
                        DBQ::del('bill', [
                            'order_sn' => $order_no
                        ]);
                        return array(
                            'status' => 'fail',
                            'code' => 10000,
                            'msg' => '提现失败:'.$sdkres['error_msg']
                        );
                    }
                } else {
                    $this->db->pdo->rollBack();
                    return array(
                        'status' => 'fail',
                        'code' => 1000,
                        'msg' => '提现失败'
                    );
                }
            }
        } catch (Exception $e) {
            $this->db->pdo->rollBack();
            throw $e;
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '提现失败,账单异常！'
            );
        }
    }
    //代理提现判断金额是否在规定范围内
    public function checkMoneyByAgent($data)
    {
        if ($data['money'] == '' || $data['money'] == '0.00' || $data['money'] == null) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '提现金额不能为空'
            );
        }
        // 获取账户余额
        $money = DBQ::getSum('agent_account', 'amount', [
            'agent_id' => $data['agent_id']
        ]);
        $userMoney['withdraw_left'] = $money ? $money : 0;
        if ($data['money'] > $userMoney['withdraw_left']) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '提现金额不能大于可提现余额'
            );
        }
        if ($data['money'] < 100) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '提现金额最少为100元'
            );
        }
        if ($data['money'] > 3000) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '提现金额最大为3000元'
            );
        }
        return array(
            'status' => 'success'
        );
    }
    //代理提现
    public function agentTakeCash($data)
    {
        if (!\ctype_digit($data['user_id'])) {
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
        if (empty($data['money'])) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '提现金额不能为空'
            );
        }

        if ($data['money'] > 3000 || $data['money'] < 100) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '提现金额范围为100元-3000元'
            );
        }

        if (empty($data['pay_password'])) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '支付密码不能为空'
            );
        }
        if ((date('w') == 0) || (date('w') == 6)) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '非工作日不允许提现'
            );
        }
        if ((date('H') < 8) || (date('H') > 16)) {
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '每天的提现时间为8点-17点'
            );
        }
        // 验证支付密码
        $condition = null;
        $condition['AND']['id'] = $data['user_id'];
        $condition['AND']['pay_password'] = Lib::compilePassword($data['pay_password']);
        $row = DBQ::getRow('agent', '*', $condition);
        if (empty($row))
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '代理支付密码有误！'
            );

        // 验证代理绑卡信息
        $condition = null;
        $condition['AND']['card_no'] = Lib::aesEncrypt($data['card_no']);
        $condition['AND']['user_id'] = $data['user_id'];
        $condition['AND']['status'] = 1;
        $condition['AND']['user_type'] = 2;
        $debit_result = DBQ::getRow('credit_card', '*', $condition);
        if (empty($debit_result))
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '绑卡信息有误！'
            );

        // 查询单日提现金额
        $condition = null;
        $condition['AND']['agent_id'] = $data['user_id'];
        $condition['AND']['type'] = 2;
        $condition['AND']['create_time[>=]'] = strtotime(date('Y-m-d', time()) . " 00:00:00") * 1000;
        $condition['AND']['create_time[<=]'] = strtotime(date('Y-m-d', time()) . " 23:59:59") * 1000;
        $day_sum_cash = DBQ::getSum('agent_account', 'amount', $condition);
        $day_sum_cash = $day_sum_cash * (-1);
        if ($day_sum_cash > MAX_WITHDRAW_DAY)
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '单日提现额度已超'
            );


        $money = DBQ::getSum('agent_account', 'amount', [
            'agent_id' => $data['user_id']
        ]);
        $agent_money = $money ? $money : 0;

        if ($agent_money < $data['money'])
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '账户余额不足！'
            );
        //总平台查询子商户余额
        $mmoney=0;

        $user=DBQ::getRow('agent_ext','*',['agent_id'=>$data['user_id']]);

        $Mmoney=$this->payModelPath->SdkBalanceQuery($user['userCode']);
        if($Mmoney['error']!=0){
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => $Mmoney['error_msg']
            );
        }else{
            $mmoney=!empty($Mmoney['balance'])?$Mmoney['balance']:0;
        }
        $NowMoney=DBQ::getSum('agent_account','amount',['agent_id'=>$data['user_id']]);

        if ($mmoney < $NowMoney){
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '账户流水异常,请联系管理员核对！'
            );
        }
        // 代付接口调用
        $agentCode = DBQ::getRow('agent_ext', [
            'userCode'
        ], [
            'agent_id' => $data['user_id']
        ]);
        if (empty($agentCode['userCode'])) {
            Lib::SdkAgentReg($data['user_id']);
        }
        $debitcard = DBQ::getRow('credit_card', '*', [
            'user_type' => 2,
            'card_no' => Lib::aesEncrypt($data['card_no']),
            'user_id' => $data['user_id']
        ]);
        //查询卡片是否鉴权
        //////////////////////////////
        $dataPostAuth = array(
            'userCode' => $agentCode['userCode'],
            'sysId' => $debitcard['sysId'],
            'cardType'=> 2,
        );

        $resultAuthRet = $this->payModelPath->payCardQuery($dataPostAuth);
        /*
         return array(
         'status' => 'fail',
         'code' => 10010,
         'msg' => '该银行卡没有鉴权',
         'iscan' => $result,
         );
         */
        if(isset($resultAuthRet['isCan']) && $resultAuthRet['isCan'] == '否'){
            return array(
                'status' => 'fail',
                'code' => 10010,
                'msg' => '该银行卡没有鉴权',
                'isCan' => 0,
            );
        }
        /////////////////////////////////
        // 插入数据库，调用代付请求，如果失败 直接删除，否则 存入redis
        $order_no = Lib::createOrderNo();
        //实际提现金额
        $nowmoney=$data['money']-WITHDRAW_POUNDAGE;

        $this->db->pdo->beginTransaction();
        try {
            // 扣除用户金额

            // 插入用户提现记录
            // 实际提现金额

            $agent_account = array(
                'agent_id' => $data['user_id'],
                'amount' => $data['money'] * (-1),
                'order_sn' => $order_no,
                'description' => "提现",
                'in_type' => 1,
                'channel' => 2, // 1易联2易宝
                'is_pay' => '-1', // -1未支付，1已支付
                'type' => 2,
                'create_time' => Lib::getMs()
            );
            $this->db->insert('agent_account', $agent_account);
            $req2 = $this->db->id();

            if (!$req2) {
                $this->db->pdo->rollBack();
                return array(
                    'status' => 'fail',
                    'code' => 1000,
                    'msg' => '提现失败'
                );
            } else {
                // 添加账单表记录
                $bill_data = array(
                    'user_id' => 0,
                    'plan_id' => 0,
                    'agent_id' => $data['user_id'],
                    'amount' => $data['money'],
                    'poundage' => WITHDRAW_POUNDAGE,
                    'rpoundage' => 0,
                    'bill_type' => 3,
                    'card_type' => 2,
                    'bank_id' => $debit_result['bank_id'],
                    'bank_name' => $debit_result['bank_name'],
                    'card_no' => $debit_result['card_no'],
                    'is_pay' => '-1', // 未支付
                    'status' => -2,
                    'order_sn' => $order_no,
                    'sysOrderSn' => '',
                    'channel' => 2, // 1易联2易宝
                    'create_time' => Lib::getMs()
                );
                // $req3 = DBQ::add('bill', $bill_data);
                $this->db->insert('bill', $bill_data);
                $req3 = $this->db->id();
                if ($req3) {
                    // 同步数据到总服务器

                    $bill_data = [
                        'agent_id' => $data['user_id'],
                        'amount' => $data['money'],
                        'bill_type' => 3,
                        'card_type' => 2,
                        'status' => -2,
                        'order_sn' => $order_no,
                        'is_pay' => '-1',
                        'create_time' => Lib::getMs(),
                        'poundage' => WITHDRAW_POUNDAGE,
                        'appid' => $data['appid'],
                        'user_id' => 0,
                        'version' => OEM_CTRL_URL_VERSION
                    ];

                    Lib::httpPostUrlEncode(MAINURL, $bill_data);
                    // 请求支付
                    $sdkdata = array(
                        'userCode' => $agentCode['userCode'], // 子商编号
                        'cardType' => 2, // 银行卡类型
                        'orderType' => 'T', // 订单类型
                        'sysId' => $debitcard['sysId'], // 订单类型
                        'amount' => $nowmoney, // 提现金额
                        'poundage' => WITHDRAW_POUNDAGE, // 手续费 + 还款笔数费
                        'userOrderSn' => $order_no, //
                        // 'notifyUrl' => ZF_DIFF_PATH.'notice/txResponce/?appid='.$data['appid'], //异步通知地址
                        'notifyUrl' => ''
                    );
                    Lib::tempLog('agentTX.txt',$sdkdata,'Pay');
                    //$sdkpay = new Pay();
                    $this->payModelPath->balanceUserRate($agentCode['userCode']);
                    $sdkres = $this->payModelPath->payDf($sdkdata);

                    Lib::tempLog('agentTX.txt',$sdkres,'Pay');
                    if ($sdkres['error'] == 0) {
                        $sysOrderSn = ''; // 平台订单号
                        $order_no = ''; // 订单号
                        if (isset($sdkres['userOrderSn'])) {
                            $order_no = $sdkres['userOrderSn'];
                        }
                        if (isset($sdkres['sysOrderSn'])) {
                            $sysOrderSn = $sdkres['sysOrderSn'];
                        }
                        if ($sdkres['status'] == 'FAILURE') {
                            // 扣除用户金额

                            DBQ::del('agent_account', [
                                'order_sn' => $order_no
                            ]);
                            DBQ::del('bill', [
                                'order_sn' => $order_no
                            ]);
                        }
                        /*else {

                            $redis = Redis::instance('plan');
                            $socre = Lib::getMs();
                            $keySign = md5('[' . ZF_VERSION . ']|[' . $sysOrderSn . ']|[' . $order_no . ']|[' . MERCHANT_ID . ']' . ZF_SIGN_OUT); // md5,[version]|[sysOrderSn]|[userOrderSn]|[merSn]入款密钥
                            $js_redis = array(
                                'version' => ZF_VERSION,
                                'merSn' => MERCHANT_ID,
                                'sysOrderSn' => $sysOrderSn,
                                'userOrderSn' => $order_no,
                                'Sign' => $keySign,
                                'appid' => $data['appid']
                            );
                            $redis->zAdd('aWithdraw', $socre, json_encode($js_redis));
                        }*/
                        $this->db->pdo->commit();
                        //更新账单信息
                        $this->txResponceUpdate($order_no);
                        return array(
                            'status' => 'success',
                            'code' => 10000,
                            'msg' => '提现成功',
                            'data' => $order_no
                        );
                    } else {
                        DBQ::del('agent_account', [
                            'order_sn' => $order_no
                        ]);
                        DBQ::del('bill', [
                            'order_sn' => $order_no
                        ]);
                        return array(
                            'status' => 'fail',
                            'code' => 1000,
                            'msg' => '提现失败'.$sdkres['error_msg']
                        );
                    }
                } else {
                    $this->db->pdo->rollBack();
                    return array(
                        'status' => 'fail',
                        'code' => 1000,
                        'msg' => '提现失败'
                    );
                }
            }
        } catch (Exception $e) {
            $this->db->pdo->rollBack();
            throw $e;
            return array(
                'status' => 'fail',
                'code' => 1000,
                'msg' => '提现失败'
            );
        }
    }
    //提现回调
    public function txResponceUpdate($order_sn)
    {
        // 查询账单
        $row = DBQ::getRow('bill', '*', [
            'order_sn' => $order_sn
        ]);
        if ($row['bill_type'] == 3) {
            DBQ::upd('bill', [
                'is_pay' => 1,
                'status' => 1
            ], [
                'order_sn' => $order_sn
            ]);



            // 判断代理还是用户
            if (! empty($row['user_id'])) {
                // 更新用户的账户流水
                $user_account_info = DBQ::getRow('user_account', '*', [
                    'user_id' => $row['user_id'],
                    'order_sn' => $row['order_sn']
                ]);
                DBQ::upd('user_account', [
                    'is_pay' => 1,
                    'status' => 1
                ], [
                    'user_id' => $row['user_id'],
                    'order_sn' => $row['order_sn']
                ]);
                // 更新用户账户
                $user_ext = DBQ::getRow('user_ext', '*', [
                    'user_id' => $row['user_id']
                ]);
                $money = $user_ext['balance'] - $user_account_info['amount'];
                DBQ::upd('user_ext', [
                    'balance' => $money
                ], [
                    'id' => $user_ext['id']
                ]);
            } elseif (! empty($row['agent_id'])) {
                // 更代理的账户流水
                $user_account_info = DBQ::getRow('agent_account', '*', [
                    'agent_id' => $row['agent_id'],
                    'order_sn' => $row['order_sn']
                ]);
                DBQ::upd('agent_account', [
                    'is_pay' => 1
                ], [
                    'agent_id' => $row['agent_id'],
                    'order_sn' => $row['order_sn']
                ]);
                // 更代理账户
                $agent_ext = DBQ::getRow('agent_ext', '*', [
                    'agent_id' => $row['agent_id']
                ]);
                $money = $agent_ext['total_commission'] - $user_account_info['amount'];
                DBQ::upd('agent_ext', [
                    'total_commission' => $money
                ], [
                    'id' => $agent_ext['id']
                ]);
            }
        }
    }
}