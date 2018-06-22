<?php
namespace App\ADMIN\Model;

use Core\Base\Model;
use Core\DB\DBQ;

class CreditCard extends Model
{

    public function getList($pageArr = null, $condition = null)
    {
        // $pageArr, $table, $join, $columns = null, $where = null
        $data = DBQ::pages($pageArr, 'credit_card (C)',[
            '[>]user (U)' => [
                    'C.user_id' => 'id',
            ],
            '[>]agent (A)' => [
                'U.agent_id' => 'id',
            ]
        ], [
            'C.id',
            'C.bank_name',
            'C.card_no',
            'C.real_name',
            'U.real_name (user_name)',
            'C.id_card',
            'C.cvn',
            'C.status',
            'C.expiry_date',
            'C.bill_day',
            'C.expiry_date',
            'C.repayment_day',
            'C.lb_mobile',
            'C.repayment_month',
            'C.channel_type',
            'C.channel_code',
            'C.create_time',
            'A.nickname(agent_name)',
            'A.mobile(agent_mobile)',
        ],$condition);
        return $data;
    }
    public function add($data)
    {
        return DBQ::add('credit_card', $data);
    }

    public function logicalDeletion($statusData ,$id){
        if(empty($id) && !is_numeric($id))return false;
        $result = DBQ::upd('credit_card',$statusData, ['id' => $id ]);
        return $result;
    }
    public function del($id = 0)
    {
        return DBQ::del('credit_card', [
            'id' => $id
        ]);
    }

    public function delAll($ids)
    {
        return DBQ::del('credit_card', [
            'id' => $ids
        ]);
    }

    public function edit($id = 0, $data)
    {
        return DBQ::upd('credit_card', $data, [
            'id' => $id
        ]);
    }
}