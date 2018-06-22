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

class AgentMention extends BaseUser
{

    protected $uid           = null;
    protected $bill_type     = 3;
    protected $status        = 1;
    protected $card_type     = 2;

    public function __construct(){
        parent::__construct();
         $headerData = Lib::getAllHeaders();
        $this->uid          = $this->user_info['uid'];


        $model = "\\App\API\\".$headerData['VERSION']."\\Model\\Bill";


        $this->model_bill   = new $model;
    }

    /**
     * 提现明细列表
     */
    public function index(){
        $condition = null;
        $condition['user_id']       = $this->uid;
        $condition['bill_type']     = $this->bill_type;
        $condition['card_type']     = $this->card_type;
        $condition['status']        = $this->status;
        $condition ['ORDER'] = [
            'create_time' => 'DESC'
        ];
        $data   = $this->model_bill->getListByWhere($condition);
        if(!empty($data)){
            foreach($data as $k=>$v){
                $data[$k]['bank_str']       = $v['bank_name'].'  储蓄卡'."(".substr($v['card_no'],-4).")";
                $data[$k]['create_time']    = Lib::uDate('Y-m-d H:i:s',$v['create_time']);
            }
        }
        Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '获取成功','data'=>$data));
    }

    /**
     * 提现详情(手续费2元/笔)
     */
    public function mentionDetail(){
        $id = Lib::post('id');
        $result   = $this->model_bill->getBillById($id);
        $data['bank_str']   =   $result['bank_name']."(".substr($result['card_no'],-4).")";
        $data['amount']     =   $result['amount'];
        $data['poundage']   =   '2.00';
        Lib::outputJson($data);
    }

}