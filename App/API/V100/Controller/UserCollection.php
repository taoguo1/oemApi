<?php
/**
 * 收款
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/2/26
 * Time: 9:37
 */

namespace App\API\V100\Controller;
use App\API\V100\Controller\BaseUser;
use Core\Lib;

class UserCollection extends BaseUser
{
    protected $uid           = null;
    protected $bill_type     = 8;
    protected $status        = 1;

    public function __construct(){
        parent::__construct();
        $this->uid          = $this->userInfo['uid'];
        $billModelPath = "\\App\\API\\" . $this->headerData['VERSION'] . "\\Model\\Bill";
        $this->billModel   = new $billModelPath();
    }
    /**
     * 收款明细列表
     */
    public function index(){
        $condition = null;
        $condition['user_id']       = $this->uid;
        $condition['bill_type']     = $this->bill_type;
        $condition['status']        = $this->status;
        $condition ['ORDER'] = [
            'create_time' => 'DESC'
        ];
        $data   = $this->billModel->getListByWhere($condition);
        if(!empty($data)){
            foreach($data as $k=>$v){
                $data[$k]['card_no']        = Lib::strreplace($v['card_no'],6,4);
                $data[$k]['create_time']    = Lib::uDate('Y-m-d H:i:s',$v['create_time'] );
            }
        }
        Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '获取成功','data'=>$data));
    }

    /**
     * 添加收款（后续支付,出）
     */
    public function add(){
        $billData['bill_type'] = $this->bill_type;
        $billData['amount']    = Lib::post('amount');
        $billData['card_no']   = Lib::post('card_no');
        $billData['channel']   = Lib::post('channel');
        $billData['card_type'] = 1;        //信用卡
        $data = $this->addBill($billData,$this->uid);
        Lib::outputJson($data);
    }

}