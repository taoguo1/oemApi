<?php
namespace App\ADMIN\Model;

use Core\Base\Model;
use Core\DB\DBQ;

class AgentAccount extends Model
{
    public function getList($pageArr = null, $condition = null)
    {
        // $pageArr, $table, $join, $columns = null, $where = null
        $data = DBQ::pages($pageArr, 'agent_account (A)', [
            '[>]agent (B)' => [
                'A.agent_id' => 'id'
            ]
        ], [
            'A.id',
            'A.amount',
            'A.order_sn',
            'A.description',
            'A.in_type',
            'A.channel',
            'A.create_time',
            'B.nickname',
        ], $condition);

        return $data;
    }



    public function add($data)
    {
        return DBQ::add('agent_account', $data);
    }


    public function edit($data,$id)
    {
        return DBQ::upd('agent_account', $data, [
            'id' => $id
        ]);
    }


	    
}
