<?php
namespace App\CALL\Controller;

use Core\Base\Controller;
use Core\Lib;
use Core\DB\DBQ;

class YbNotice extends  Controller
{
    public function notice()
    {
        $ipAllows = ['60.205.206.165','39.106.180.60','47.95.119.52'];
        $ip = $_SERVER['REMOTE_ADDR'];
        if(in_array($ip,$ipAllows)){
            $dataXml = file_get_contents("php://input");
            $data = explode('&',urldecode($dataXml));
            $arr = [];
            foreach($data as $k => $v){
                $subdata = explode('=',$v);
                $arr[] = [$subdata[0]=>$subdata[1]];
                if($subdata[0] == 'orderid'){
                    $oId = $subdata[1];
                }
                if($subdata[0] == 'amount'){
                    $Fee = $subdata[1];
                }
            }
            $orderid = substr($oId,3);
            if($orderid){
                $row = DBQ::getRow('bill',['id','amount','poundage','bill_type','user_id','agent_id','card_no','card_type'],['order_sn'=>$orderid]);
                //$row['bill_type'] 判断账单类型 进行不同的操作

                //1.还款

                //2.消费
                if($row['bill_type'] == 2){
                    if($Fee / 100 >= $row['amount']){
                        DBQ::upd('bill',['is_pay' => 1],['order_sn' => $orderid]);
                    }else{
                        DBQ::upd('bill',['status' => -2],['order_sn' => $orderid]);
                    }
                    $log_url = '/www/web/dev_dizaozhe_cn/public_html/Logs/repayment.txt';
                    $ext_data = ['orderid'=>$orderid,'ip'=>$ip,'row'=>$row];
                    \file_put_contents($log_url,json_encode([$arr,$ext_data]),FILE_APPEND);
                    echo "success";
                }
                //3.提现

                //4.充值
                if($row['bill_type'] == 4){
                    if($Fee / 100 >= $row['amount']){
                        DBQ::upd('bill',['is_pay' => 1],['order_sn' => $orderid]);
                        DBQ::upd('user_account',['is_pay' => 1],['order_sn' => $orderid]);
                        DBQ::upd('user_ext',['balance[+]' => $row['amount']-$row['poundage']],['user_id' => $row['user_id']]);
                    }else{
                        DBQ::upd('bill',['status' => -2],['order_sn' => $orderid]);
                    }
                    echo "success";
                }
                //5.卡验证
                if($row['bill_type'] == 5){
                    if($Fee / 100 >= $row['amount']){
                        DBQ::upd('bill',['is_pay' => 1],['order_sn' => $orderid]);
                        //判断信用卡还是储蓄卡操作对应的表
                        if($row['card_type'] == 1){
                            DBQ::upd('credit_card',['status' => 1],['user_id' => $row['user_id'],'card_no'=>$row['card_no']]);
                        }elseif($row['card_type'] == 2){
                            //
                            if($row['user_id']){
                                DBQ::upd('debit_card',['status' => 1],['user_id' => $row['user_id'],'card_no'=>$row['card_no']]);
                            }
                            if($row['agent_id']){
                                DBQ::upd('debit_card',['status' => 1],['user_id' => $row['agent_id'],'user_type'=>2,'card_no'=>$row['card_no']]);
                            }
                        }
                    }else{
                        DBQ::upd('bill',['status' => -2],['order_sn' => $orderid]);
                    }
                    echo "success";
                }
            }
        }
        file_put_contents('/www/web/dev_dizaozhe_cn/public_html/Logs/notice.txt',json_encode($arr).$orderid.$ip.'|--|'.json_encode($row),FILE_APPEND);
    }
    public function noticeResponce() {
        $order_sn = Lib::request('order_sn');
        //查询账单
        $row = DBQ::getRow('bill','*',['order_sn' => $order_sn]);
        if ($row['bill_type'] == 5) {
            DBQ::upd('bill',['is_pay' => 1],['order_sn' => $order_sn]);
            //判断信用卡还是储蓄卡操作对应的表
            if($row['card_type'] == 1){
                DBQ::upd('credit_card',['status' => 1],['user_id' => $row['user_id'],'card_no'=>$row['card_no']]);
            }elseif($row['card_type'] == 2){
                if($row['user_id']){
                    DBQ::upd('debit_card',['status' => 1],['user_id' => $row['user_id'],'card_no'=>$row['card_no']]);
                }
                if($row['agent_id']){
                    DBQ::upd('debit_card',['status' => 1],['user_id' => $row['agent_id'],'user_type'=>2,'card_no'=>$row['card_no']]);
                }
            }
        }

    }
}

