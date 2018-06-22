<?php
namespace App\API\V100\Model;
use Core\Base\Model;
use Core\DB\DBQ;

class Bill extends Model
{

    /*
    public function getList($user_id){
        $data = DBQ::getAll('bill', [
            "id",
            "user_id",
        	"amount",
        	"bill_type",
            "card_type",
            "bank_id",
            "bank_name",
            "card_no",
            "create_time"
        ],[
                "ORDER" =>['id'=>'DESC'],
                "user_id" => $user_id
            ]
        );
        return $data;
    }
    */
    public function getList($pageArr = null, $condition = null){
        $data = DBQ::pages($pageArr, 'bill (A)', [
            '[>]user (B)' => [
                'A.user_id' => 'id'
            ]
        ], [
            "A.id",
            "A.user_id",
            "A.amount",
            "A.bill_type",
            "A.card_type",
            "A.bank_id",
            "A.bank_name",
            "A.card_no",
            "A.balance",
            "A.create_time",
            'A.poundage'
        ],$condition);
        return $data;
    }

    public function editCreditBillDay($card_id,$day,$user_id,$repaymentmonth){
        $ret = DBQ::upd('credit_card',['bill_day'=>$day,'repayment_month'=>$repaymentmonth],['id'=>$card_id,'user_id'=>$user_id]);
        return $ret;
    }

    public function editCreditRepaymentDay($card_id,$day,$user_id,$repaymentmonth){
        $ret = DBQ::upd('credit_card',['repayment_day'=>$day,'repayment_month'=>$repaymentmonth],['id'=>$card_id,'user_id'=>$user_id]);
        return $ret;
    }

    public function editCreditStartDay($card_id,$day,$user_id){
        $ret = DBQ::upd('credit_card',['start_repayment_day'=>$day],['id'=>$card_id,'user_id'=>$user_id]);
        return $ret;
    }

    public function editCreditEndDay($card_id,$day,$user_id){
        $ret = DBQ::upd('credit_card',['end_repayment_day'=>$day],['id'=>$card_id,'user_id'=>$user_id]);
        return $ret;
    }


    /**
     * 通过明细查询列表
     * @param $condition
     */
    public function getListByWhere($condition){
        if(empty($condition))return false;
        return DBQ::getAll('bill', [
            "id",
            "user_id",
            "amount",
            "bill_type",
            "bank_id",
            "bank_name",
            "card_no",
            "create_time"
        ],$condition);
    }

    /**
     * 通过id查询详情
     * @param $condition
     */
    public function getBillById($id){
        if(empty($id))return false;
        return DBQ::getRow('bill', '*',['id'=>$id]);
    }
    public function getCreditCard($uid, $cardno){
        $data = DBQ::getOne('credit_card','*',['user_id'=>$uid,'id'=>$cardno]);
        return $data;
    }
    //通过用户id，订单号查询订单信息
    public function getOrderInfo($data){
        $data = DBQ::getOne('bill',['amount','poundage','bank_name'],['user_id'=>$data['user_id'],'order_sn'=>$data['order_sn']]);
        return $data;
    }

    //通过代理id，订单号查询订单信息
    public function getOrderInfoForAgent($data){
        $data = DBQ::getOne('bill',['amount','poundage','bank_name'],['agent_id'=>$data['agent_id'],'order_sn'=>$data['order_sn']]);
        return $data;
    }




}