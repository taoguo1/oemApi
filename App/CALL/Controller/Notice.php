<?php
namespace App\CALL\Controller;

use Core\Base\Controller;
use Core\Lib;
use Core\DB\DBQ;

class Notice extends Controller
{

    // 信用卡，储蓄卡绑卡回调
    public function bkResponce()
    {
        $order_sn = Lib::request('userOrderSn');
        $sysId = Lib::request('sysId');
        //  查询账单 
        $row = DBQ::getRow('bill', '*', [
            'order_sn' => $order_sn
        ]);
        if ($row['bill_type'] == 5) {
            DBQ::upd('bill', [
                'is_pay' => 1
            ], [
                'order_sn' => $order_sn
            ]);

            // 判断信用卡还是储蓄卡操作对应的表
            if ($row['card_type'] == 1) {
                DBQ::upd('credit_card', [
                    'status' => 1,
                    'sysId' => $sysId
                ], [
                    'user_id' => $row['user_id'],
                    'card_no' => $row['card_no']
                ]);
            } elseif ($row['card_type'] == 2) {
                if ($row['user_id']) {
                    DBQ::upd('debit_card', [
                        'status' => 1,
                        'sysId' => $sysId
                    ], [
                        'user_id' => $row['user_id'],
                        'card_no' => $row['card_no']
                    ]);
                }
                if ($row['agent_id']) {
                    DBQ::upd('debit_card', [
                        'status' => 1,
                        'sysId' => $sysId
                    ], [
                        'user_id' => $row['agent_id'],
                        'user_type' => 2,
                        'card_no' => $row['card_no']
                    ]);
                }
            }
        }
        echo 'success';
    }
    // 提现回调
    public function txResponce()
    {
        $order_sn = Lib::request('userOrderSn');
        // 查询账单
        $row = DBQ::getRow('bill', '*', [
            'order_sn' => $order_sn
        ]);
        if ($row['bill_type'] == 3) {
            DBQ::upd('bill', [
                'is_pay' => 1
            ], [
                'order_sn' => $order_sn
            ]);
            //添加日志
            $bill_data = DBQ::getRow('bill', '*', [
                'order_sn' => $order_sn
            ]);
            $logsData = array('signMsg' => '绑卡回调更新账单表');
			Lib::tempLog('restTX.txt',$bill_data,'Pay');
            //Lib::recordLogs(LOGS_PATH,array_merge($bill_data,$logsData));
            // 判断代理还是用户
            if (! empty($row['user_id'])) {
                // 更新用户的账户流水
                $user_account_info = DBQ::getRow('user_account', '*', [
                    'user_id' => $row['user_id'],
                    'order_sn' => $row['order_sn']
                ]);
                DBQ::upd('user_account', [
                    'is_pay' => 1
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
                echo 'success';
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
                echo 'success';
            }
        }
    }
    // 充值回调
    public function czResponce()
    {
        $order_sn = Lib::request('userOrderSn');
        $attach_data = Lib::request('attach');
        $amount = Lib::request('amount');
        $user_id = '';
        $bank_id = '';
        $bank_name = '';
        $card_no = '';
        $cardType = '';
        $appid='';
        if ($attach_data) {
            $attach_data = explode("|", $attach_data);
            $user_id = $attach_data[0];
            $bank_id = $attach_data[1];
            $bank_name = $attach_data[2];
            $card_no = $attach_data[3];
            $cardType = $attach_data[4];
            $appid = $attach_data[5];
            if ($cardType == 2) {
                $cardType = 1;
            } else {
                $cardType = 2;
            }
        }
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
        
        // 更新用户账户金额
        $user_ext = DBQ::getRow('user_ext', '*', [
            'user_id' => $user_id
        ]);
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
            'channel' => 2, // 1易联2易宝
            'create_time' => Lib::getMs()
        );
        DBQ::add('bill', $bill_data);
        //添加日志
        $logsData = array('signMsg' => '绑定信用卡回调插入账单');
		Lib::tempLog('restCZ.txt',$bill_data,'Pay');
       
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
            'appid' => $appid,
            'agent_id' => 0,
            'version'=>OEM_CTRL_URL_VERSION
        ];

        Lib::httpPostUrlEncode(MAINURL, $mbill_data);
        echo 'success';
    }
}

