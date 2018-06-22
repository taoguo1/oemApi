<?php

namespace App\WWW\Controller;

use Core\Base\Controller;
use Core\DB\DBQ;
use Core\Lib;
use App\API\V100\Model\Pay;

class Index extends Controller
{
    public function index()
    {
        $db = new \App\WWW\Model\Index();
        $sql = "select sum(amount) as total_account,dzz_user.real_name,dzz_user_ext.userCode,dzz_user_ext.user_id from dzz_user_account left join dzz_user on dzz_user.id=dzz_user_account.user_id left join dzz_user_ext on dzz_user.id=dzz_user_ext.user_id  group by dzz_user_account.user_id";
        $data = $db->getuseraccount($sql);
        //var_dump($data);exit;
        $dataInfo = [];
        foreach ($data as $k => $v) {
            $info = new Pay();
            $a = $info->SdkBalanceQuery($v['userCode']);
            $dataInfo[$k]['user_id'] = $v['user_id'];
            $dataInfo[$k]['canTxMoney'] = Lib::getMayUseMoney($v['user_id']);
            $dataInfo[$k]['amountByGuo'] = $a['balance'];
            $dataInfo[$k]['balanceChannel'] = $a['balanceChannel'];
            $dataInfo[$k]['total_account'] = $v['total_account'];
            $dataInfo[$k]['real_name'] = $v['real_name'];
            $dataInfo[$k]['userCode'] = $v['userCode'];
            //$dataInfo[$k]['sysId'] = $v['sysId'];
        }

        $this->assign('data', $dataInfo);
        $this->view();
    }
/*
    public function index()
    {
        $db = new \App\WWW\Model\Index();
        $sql = "select sum(amount) as total_account,dzz_user.real_name,dzz_user_ext.userCode,dzz_user_ext.user_id,dzz_credit_card.sysId from dzz_user_account left join dzz_user on dzz_user.id=dzz_user_account.user_id left join dzz_user_ext on dzz_user.id=dzz_user_ext.user_id  left join dzz_credit_card on dzz_user_account.user_id=dzz_credit_card.user_id group by dzz_user_account.user_id";
        $data = $db->getuseraccount($sql);
        //var_dump($data);exit;
        $dataInfo = [];
        foreach ($data as $k => $v) {
            $info = new Pay();
            $a = $info->SdkBalanceQuery($v['userCode']);
            $dataInfo[$k]['user_id'] = $v['user_id'];
            $dataInfo[$k]['canTxMoney'] = Lib::getMayUseMoney($v['user_id']);
            $dataInfo[$k]['amountByGuo'] = $a['balance'];
            $dataInfo[$k]['balanceChannel'] = $a['balanceChannel'];
            $dataInfo[$k]['total_account'] = $v['total_account'];
            $dataInfo[$k]['real_name'] = $v['real_name'];
            $dataInfo[$k]['userCode'] = $v['userCode'];
            $dataInfo[$k]['sysId'] = $v['sysId'];
        }

        $this->assign('data', $dataInfo);
        $this->view();
    }
*/
    public function trans($userCode,$sysId,$amount,$userid)
    {
        $db = new \App\WWW\Model\Index();
        $post = array(
            'userCode' => $userCode, // 子商编号
            'cardType' => 2, // 银行卡类型
            'orderType' => 'T', // 订单类型
            'sysId' => $sysId,
            'amount' => $amount  - 2, // 金额
            'poundage' => 2,
            'userOrderSn' => Lib::createOrderNo(),
            'notifyUrl' => ''
        );
        print_r($post);
        echo "<hr>";
        $pay = new Pay();
        $r = $pay->payDf($post);
        print_r($r);
        if($r['error'] == 0) {
            $user_account = array(
                'user_id' => $userid,
                'amount' => $amount * (-1),
                'order_sn' => $post['userOrderSn'],
                'desciption' => "提现",
                'in_type' => 1,
                'channel' => 2, // 1易联2易宝
                'is_pay' => '1', // -1未支付，1已支付
                'status' => '-2',
                'create_time' => Lib::getMs()
            );
            DBQ::add('user_account',$user_account);
            //$db->addAccountid($user_account);

            // 添加账单表记录
            $bill_data = array(
                'user_id' => $userid,
                'plan_id' => 0,
                'agent_id' => 0,
                'amount' => $amount,
                'poundage' => WITHDRAW_POUNDAGE,
                'rpoundage' => 0,
                'bill_type' => 3,
                'card_type' => 2,
                'bank_id' => '',
                'bank_name' => '',
                'card_no' => '',
                'is_pay' => '1', // 未支付
                'status' => 1,
                'order_sn' => $post['userOrderSn'],
                'userOrderSn' => $post['userOrderSn'],
                'sysOrderSn' => '',
                'channel' => 2, // 1易联2易宝
                'create_time' => Lib::getMs()
            );
            DBQ::add('bill', $bill_data);
        }
    }


    public function test()
    {
        Lib::tempLog('plan_test.txt', 'test', 'Plan');
    }

    public function test1()
    {
        $billDataIn = [
            'user_id' => 1000000,
            'amount' => 0,
            'bill_type' => 8,
            'card_type' => 1,
            'bank_name' => '',
            'card_no' => '',
            'bank_id' => 0,
            'poundage' => 0,
            'order_sn' => '',
            'userOrderSn' => '',
            'sysOrderSn' => '',
            'channel' => 2,
            'status' => -1,
            'intatus' => 1,
            'is_pay' => -1,
            'create_time' => Lib::getMs(),
        ];
        $inId = $this->M()->addBillRid($billDataIn);
        var_dump($inId);
    }


}
