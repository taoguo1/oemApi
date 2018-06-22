<?php

namespace Core\WxPay;
use Core\Lib;
use AppPay;
use WxPayApi;
use WxPayUnifiedOrder;
class WxPay
{
   
    public function getAppApiParameters($orderSn, $orderType,$title, $amount) {
       
        error_reporting(0);
        Lib::loadFile("Core/WxPay/lib/WxPay.Api.php");
        Lib::loadFile("Core/WxPay/source/WxPay.AppPay.php");
        $notify = new AppPay ();
        /* 首先生成prepayid */
        $input = new WxPayUnifiedOrder ();
        $amount = $amount * 100;
        $input->SetBody ( $title ); // 商品或支付单简要描述(必须填写)
        $input->SetOut_trade_no ( $orderSn."".time()); // 订单号(必须填写)
        $input->SetTotal_fee ( $amount ); // 订单金额(必须填写)
       
        $input->SetNotify_url ( SetNotifyUrl ); // 回调URL(必须填写)
        $input->SetAttach($orderSn."-".$orderType);
        $input->SetTrade_type ( "APP" ); // 交易类型(必须填写)
        $order = WxPayApi::unifiedOrder ( $input ); // 获得订单的基本信息，包括prepayid
        
        //如果商户订单号重复应该处理直接返回信息
        return  $appApiParameters = $notify->GetAppApiParameters ( $order ); // 生成提交给app的一些参数
    }
}

