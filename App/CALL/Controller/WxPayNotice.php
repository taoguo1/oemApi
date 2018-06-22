<?php
namespace App\CALL\Controller;

use Core\Base\Controller;
use Core\Lib;
use WxPayNotify;
use WxPayApi;
use Core\DB\DBQ;
//aaa
class WxPayNotice extends  Controller
{
    public function index()
    {
        //file_put_contents('/www/web/dev_dizaozhe_cn/public_html/Logs/111.txt','ddd123');
        //Lib::loadFile('Core/WxPay/source/notify.php');
        $notify = new PayNotifyCallBack ();
        $notify->Handle ( false );
    }
}
error_reporting(0);
// Lib::loadFile('Core/WxPay/source/notify.php');
require_once APP_PATH."Core/WxPay/lib/WxPay.Api.php";
require_once APP_PATH.'Core/WxPay/lib/WxPay.Notify.php';
require_once APP_PATH.'Core/WxPay/source/log.php';
class PayNotifyCallBack extends WxPayNotify {
    // 重写回调处理函数
    public function NotifyProcess($data, &$msg) {
        //file_put_contents('/www/web/dev_dizaozhe_cn/public_html/Logs/aaaad.txt','aaa');
       // Log::DEBUG ( "call back:" . json_encode ( $data ) );
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
                //update 数据库 status=已经支付
                $attach = explode('-',$data['attach']);
                $row = DBQ::getOne('bill',['id','amount'],['order_sn'=>$attach[0]]);
                if($data['total_fee'] / 100 >= $row['amount']){
                    DBQ::upd('bill',['is_pay' => 1],['order_sn' => $attach[0]]);
                }else{
                    DBQ::upd('bill',['status' => -2],['order_sn' => $attach[0]]);
                }
                //file_put_contents('/www/web/dev_dizaozhe_cn/public_html/Logs/aaaad.txt',json_encode ( $data ));
                //return true;
                echo "success";
            } else {
                
                return false;
            }
        }
    }
}


