<?php
namespace App\API\V100\Model;
use Core\Base\Model;
use Core\DB\DBQ;

class Gather extends Model
{

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
            "A.create_time"
        ],$condition);
        return $data;
    }

    public function getCreditCardInfo($uid, $cardno){
        $data = DBQ::getRow('credit_card','*',['user_id'=>$uid,'id'=>$cardno]);
        return $data;
    }

    public function getDebitCardInfo($uid, $cardno,$utype){
        $data = DBQ::getRow('debit_card','*',['user_id'=>$uid,'id'=>$cardno,'user_type'=>$utype]);
        return $data;
    }
    public function getUserExt($uid){
        $data = DBQ::getRow('user_ext','*',['user_id'=>$uid]);
        return $data;
    }

    public function addBill($data){
        $data = DBQ::add('bill',$data);
        return $data;
    }

    public function addBillRid($data){
        $this->db->insert('bill',$data);
        return $this->insertID();
    }

}