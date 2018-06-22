<?php
/**
 * Created by PhpStorm.
 * User: hqf
 * Date: 2018/4/25
 * Time: 15:42
 */
namespace App\CALL\Controller;

use Core\Lib;
use Core\DB\DBQ;
use Core\Base\Controller;

class AbroadConsume extends Controller{ 
    public function addAbordBill()
    {
        $jsonGet = '';
        $jsonGet = Lib::request('data');
        $myAppid = Lib::get('appid');
        if (empty($jsonGet)) {
            echo '没有接受到数据！';
        } else {
            $order_sn = '';
            $appid = '';
            $amount = 0;
            $data = json_decode($jsonGet, true);
            if (isset($data['order_sn']) && !empty($data['order_sn'])) {
                $order_sn = trim($data['order_sn']);
            } else {
                echo "订单号为空";
                exit();
            }
            if (isset($data['appid']) && !empty($data['appid'])) {
                $appid = trim($data['appid']);
            } else {
                echo "appid不能为空";
                exit();
            }
            if (isset($data['amount']) && !empty($data['amount'])) {
                $amount = $data['amount'];
            } else {
                echo "金额不能为0";
                exit();
            }
            //当前oem商的境外消费
            if ($myAppid == $appid) {
                $profit = $amount*ABROAD_CONSUME_RATE/10000;
                $data_insert = array(
                    'appid' =>  $appid,
                    'order_sn' => $order_sn,
                    'amount'   => $amount,
                    'profit'   => $profit,
                    'add_time' => Lib::getMs()
                );
                $result = DBQ::add('abroad_consume', $data_insert);
                echo "success";
            } else {
                echo "fail";
                exit();
            }
        }
    }
}

