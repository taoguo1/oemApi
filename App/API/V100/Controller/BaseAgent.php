<?php
/**
 * 代理父类
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/2/22
 * Time: 16:24
 */

namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Extend\Redis;
use Core\Lib;
use Core\DB\DBQ;


class BaseAgent extends Controller
{
    protected $agentInfo = array();
    protected $headerData   = array();

    public function __construct()
    {
        $this->headerData = Lib::getAllHeaders();
        $V = "\\App\\API\\" . $this->headerData['VERSION'] . "\\Model\\Agent";
        $agentModel = new $V();
        //$tokenInfo = DBQ::getRow('token', '*', ['token' => $this->headerData['TOKEN']]);
        //$redis = Redis::instance('token');
        //$conf = Lib::loadFile('Config/Redis.php');
        //$redisObj =  RedisAliMulti::getRedisInstance(REDIS['token'], 0);
        $redis = Redis::instance('token');
        $tokenInfo = $redis->zRangeByScore(Lib::request('appid').'_agent_token',$this->headerData['UID'],$this->headerData['UID'],['withscores'=>false,'limit'=>[0,100000]]);
        if (count($tokenInfo) >= 1) {
            $tokenInfo = json_decode($tokenInfo[0],true);
        } else {
            Lib::outputJson(array('status' => 'fail', 'code' => 10012, 'msg' => '登录信息失效,请重新登录！'));
        }
        $this->agentInfo = $agentModel->getAgentInfoByAgentId($tokenInfo['uid']);
    }

    /**
     * 添加账单信息（提现收款公用）
     * @param $billData
     * @param $id
     */
    public function addBill($bill_data, $id)
    {
        if (empty($bill_data) || empty($id)) return array('status' => 'fail', 'code' => 1000, 'msg' => '添加失败');
        $bill_data_ext = null;
        if (!empty($bill_data['amount'])) {
            $bill_data_ext['amount'] = $bill_data['amount'];
        }
        if (!empty($bill_data['bill_type'])) {
            $bill_data_ext['bill_type'] = $bill_data['bill_type'];
        }
        if (!empty($bill_data['bank_id'])) {
            $bill_data_ext['bank_id'] = $bill_data['bank_id'];
        }
        if (!empty($bill_data['channel'])) {
            $bill_data_ext['channel'] = $bill_data['channel'];
        }
        if ($bill_data['card_type'] == 1) {
            $table = 'credit_card';
        } else {
            $table = 'debit_card';
        }
        // 获取银行卡信息
        $condition = null;
        $condition ['AND'] ['card_no'] = Lib::aesEncrypt($bill_data['card_no']);
        $bank_data = DBQ::getRow($table, '*', $condition);
        if (!empty($bank_data['bank_id'])) $bill_data_ext['bank_id'] = $bank_data['bank_id'];
        if (!empty($bank_data['bank_name'])) $bill_data_ext['bank_name'] = $bank_data['bank_name'];
        if (!empty($bank_data['card_no'])) $bill_data_ext['card_no'] = $bank_data['card_no'];
        $bill_data_ext['user_id'] = $id;
        $bill_data_ext['status'] = 1;
        $bill_data_ext['create_time'] = Lib::getMs();
        DBQ::add('bill', $bill_data_ext);
        return array('status' => 'success', 'code' => 1000, 'msg' => '添加成功');
    }
}