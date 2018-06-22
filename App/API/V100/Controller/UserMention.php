<?php
/**
 * 提现
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/2/26
 * Time: 13:43
 */

namespace App\API\V100\Controller;
use App\API\V100\Controller\BaseUser;
use Core\Lib;

class UserMention   extends BaseUser
{

    public function __construct(){
        parent::__construct();
        $billModelPath = "\\App\\API\\" . $this->headerData['VERSION'] . "\\Model\\Bill";
        $this->billModel   = new $billModelPath();
    }
    /**
     * 提现详情(手续费2元/笔)
     */
    public function mentionDetail(){
        $id = Lib::post('id');
        $result   = $this->billModel->getBillById($id);
        $data['bank_str']   =   $result['bank_name']."(".substr($result['card_no'],-4).")";
        $data['amount']     =   $result['amount'];
        $data['poundage']   =   '2.00';
        Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '获取成功','data'=>$data));
    }

}