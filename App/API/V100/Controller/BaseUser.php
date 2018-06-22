<?php
/**
 * 用户父类
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/2/22
 * Time: 15:31
 */

namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;
use Core\DB\DBQ;
use Core\Extend\RedisAliMulti;
class BaseUser extends Controller
{
    protected $userInfo = array();
    protected $headerData   = array();

    public function __construct()
    {

        $this->headerData = Lib::getAllHeaders();
        $V = "\\App\\API\\" . $this->headerData['VERSION'] . "\\Model\\User";
        $userModel = new $V();
        $conf = Lib::loadFile('Config/Redis.php');
/*      $redisObj =  RedisAliMulti::getRedisInstance(REDIS['token'], 0);
        $tokenInfo = $redisObj->getConnect()->zRangeByScore($this->headerData['APPID'].'_user_token',$this->headerData['UID'],$this->headerData['UID'],['withscores'=>false,'limit'=>[0,100000]]);
        if (count($tokenInfo) >= 1)$tokenInfo = json_decode($tokenInfo[0],true);
*/
        $this->userInfo = $userModel->getUserInfoByUserId($this->headerData['UID']);
    }

    /**
     * 添加账单信息（提现收款公用）
     * @param $billData
     * @param $id
     */
    public function addBill($billData, $id)
    {
        if (empty($billData) || empty($id)) return array('status' => 'fail', 'code' => 1000, 'msg' => '添加失败');
        $billDataExt = null;
        if (!empty($billData['amount'])) {
            $billDataExt['amount'] = $billData['amount'];
        }
        if (!empty($billData['bill_type'])) {
            $billDataExt['bill_type'] = $billData['bill_type'];
        }
        if (!empty($billData['bank_id'])) {
            $billDataExt['bank_id'] = $billData['bank_id'];
        }
        if (!empty($billData['channel'])) {
            $billDataExt['channel'] = $billData['channel'];
        }
        if ($billData['card_type'] == 1) {
            $table = 'credit_card';
        } else {
            $table = 'debit_card';
        }
        // 获取银行卡信息
        $condition = null;
        $condition ['AND'] ['card_no'] = Lib::aesEncrypt($billData['card_no']);
        $bankData = DBQ::getRow($table, '*', $condition);
        if (!empty($bankData['bank_id'])) $billDataExt['bank_id'] = $bankData['bank_id'];
        if (!empty($bankData['bank_name'])) $billDataExt['bank_name'] = $bankData['bank_name'];
        if (!empty($bankData['card_no'])) $billDataExt['card_no'] = $bankData['card_no'];
        $billDataExt['user_id'] = $id;
        $billDataExt['status'] = 1;
        $billDataExt['create_time'] = Lib::getMs();
        DBQ::add('bill', $billDataExt);
        return array('status' => 'success', 'code' => 1000, 'msg' => '添加成功');
    }

}