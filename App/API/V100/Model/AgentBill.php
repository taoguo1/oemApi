<?php
namespace App\API\V100\Model;
use Core\Base\Model;
use Core\DB\DBQ;

class AgentBill extends Model
{

    public function getList($pageArr = null, $condition = null){
        $data = DBQ::pages($pageArr, 'agent_account (A)', [
            //'[>]agent (B)' => ['A.agent_id' => 'id'],
            '[>]user_account (C)' => ['A.order_sn' => 'order_sn'],
            '[>]user (D)' => ['C.user_id' => 'id']
        ], 
        [
            'A.id',
            'A.amount',
            'A.agent_id',
            'A.order_sn',
            'A.description',
            'A.create_time',
            'C.user_id',
            'D.real_name',
            'D.mobile'
        ],$condition);
        return $data;
    }


    public function getRepaymentList($agent_id,$bill_type){
        $data = DBQ::getAll('bill', [
            "id",
            "user_id",
            "amount",
            "bill_type",
            "bank_id",
            "card_no",
            "bank_name",
            "create_time"
        ],[
                "agent_id" => $agent_id,
                "bill_type" => 3,
                "ORDER" => ["create_time" => "DESC"],
            ]
        );
        return $data;
    }

    public function getRepaymentListALL($agent_id,$bill_type){
        $data = DBQ::getAll('bill', [
            "id",
            "user_id",
            "amount",
            "bill_type",
            "bank_id",
            "bank_name"
        ],[
                "agent_id" => $agent_id,
                "bill_type" => $bill_type
            ]
        );
        return $data;
    }





}