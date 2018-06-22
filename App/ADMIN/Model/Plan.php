<?php
namespace App\ADMIN\Model;

use Core\Base\Model;
use Core\DB\DBQ;

class Plan extends Model
{

    public function getList($pageArr = null, $condition = null)
    {
        // $pageArr, $table, $join, $columns = null, $where = null
        $data = DBQ::pages($pageArr, 'plan (P)', [
            '[>]user (U)' => [
                'P.user_id' => 'id'
            ],
            '[>]agent (A)' => [
                'U.agent_id' => 'id'
            ]
        ], [
            'A.mobile(aMobile)',
            'A.nickname(aNickname)',
            'P.id',
            'U.real_name',
            'U.mobile',
            'U.id_card',
            'P.amount',
            'P.card_no',
            'P.start_time',
            'P.end_time',
            'P.duration',
            'P.poundage',
            'P.finish_time',
            'P.finish_type',
            'P.status',
            'P.create_time',
            //'P.plan_type'
        ], $condition);

        return $data;
    }

    public function add($data)
    {
        return DBQ::add('plan', $data);
    }

    public function del($id = 0)
    {
        return DBQ::del('plan', [
            'id' => $id
        ]);
    }

    public function delAll($ids)
    {
        return DBQ::del('plan', [
            'id' => $ids
        ]);
    }
    
}