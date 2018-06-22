<?php
namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;
use Core\DB\DBQ;
// use Core\WxPay\WxPay;
class Pay extends Controller
{
    public function pay()
    {
        $headers = Lib::getAllHeaders();
        $uid = $headers['UID'];
        $WxPay = new \Core\WxPay\WxPay();
        $orderSn = Lib::createOrderNo();
        $orderType = 1;
        $orderPrice = 0.01;
        $title = "购买邀请码";
        //插入数据库成功执行下面
        //记账，计入bill表
        $billData = [
            'user_id' => $uid,
            'amount' => $orderPrice,
            'bill_type' => 10,
            'card_type' => 1,
            'bank_name' => '微信支付',
            'card_no' => 0,
            'bank_id' => 0,
            'poundage' => 0,
            'channel' => 2,
            'order_sn' => $orderSn,
            'status' => 1,
            'is_pay' => -1,
            'intatus' => 1,
            'create_time' => Lib::getMs(),
        ];

        $billRet = DBQ::add('bill',$billData);
        //同步数据到总服务器
        $bill_data = [
            'user_id' => $uid,
            'amount' => $orderPrice,
            'bill_type' => 10,
            'card_type' => 1,
            'bank_name' => '微信支付',
            'status' => 1,
            'order_sn' => $orderSn,
            'is_pay' => -1,
            'create_time' => Lib::getMs(),
            'poundage' => 0,
            'appid' => Lib::request("appid"),
            'agent_id' => 0,
            'transaction_id' => 0,
            'version' => OEM_CTRL_URL_VERSION
        ];
        Lib::httpPostUrlEncode(MAINURL, $bill_data);
        
        $prepayRes = $WxPay->getAppApiParameters($orderSn, $orderType, $title, $orderPrice);
        $data['data']['code'] = "prepay_success";
        $data['data']['orderType'] = $orderType;
        $data['data']['orderSn'] = $orderSn;
        $data['data']['prepayRes'] = $prepayRes;
        Lib::outputJson($data);
    }
}