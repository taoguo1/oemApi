<?php
//session_start();
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

require_once APP_PATH."Core/WxPay/lib/WxPay.Api.php";
require_once APP_PATH.'Core/WxPay/lib/WxPay.Notify.php';
require_once APP_PATH.'Core/WxPay/source/log.php';
//初始化日志
$logHandler= new CLogFileHandler("/www/web/dev_dizaozhe_cn/public_html/Logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
class PayNotifyCallBack extends WxPayNotify {
    // 重写回调处理函数
    public function NotifyProcess($data, &$msg) {
        Log::DEBUG ( "call back:" . json_encode ( $data ) );
        $notfiyOutput = array ();
        $result = json_encode ( $data );
        if (empty ( $data ['transaction_id'] )) {
            return false;
        } else {
            $result_code = $data ["result_code"];
            $return_code = $data ["return_code"];
            $transaction_id = $data ['transaction_id'];
            $out_trade_no = $data ['attach'];
            if ($result_code == 'SUCCESS' && $return_code == 'SUCCESS') {
                // 微信传过来的支付金额,需要判断支付金额是否大于等于当前的实际应该支付的订单金额            
//                 $total_fee = $data ['total_fee'] / 100;             
//                 $out_trade_no_array = explode ( "-", $out_trade_no );            
//                 $out_trade_no = $out_trade_no_array [0];
//                 $order_type = $out_trade_no_array [1];
                file_put_contents('/www/web/dev_dizaozhe_cn/public_html/Logs/aaaad.txt',json_encode ( $data ));
                return true;
            } else {
                
                return false;
            }
        }
    }
}
Log::DEBUG ( "begin notify" );
$notify = new PayNotifyCallBack ();
$notify->Handle ( false );