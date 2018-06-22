<?php
namespace App\ADMIN\Model;

use Core\Base\Model;
use Core\DB\DBQ;

class DebitCard extends Model
{

 public function getList($pageArr = null, $condition = null)
    {
        // $pageArr, $table, $join, $columns = null, $where = null
        $data = DBQ::pages($pageArr,'debit_card (D)',[
            '[>]user (U)' => [
                'D.user_id' => 'id'
            ]
        ],[
            'D.id',
            'D.bank_name',
            'D.card_no',
            'D.real_name',
            'U.real_name (user_name)',
            'D.id_card',
            'D.status',
            'D.lb_mobile',
            'D.channel_type',
            'D.channel_code',
            'D.create_time',
            'D.user_type',
            'D.user_id'
        ],$condition);
        return $data;
    }
    public function add($data)
    {
        return DBQ::add('debit_card', $data);
    }
   

    
    public function logicalDeletion($statusData ,$id){
        if(empty($id) && !is_numeric($id))return false;
        $result = DBQ::upd('debit_card',$statusData, ['id' => $id ]);
        return $result;
    }
    public function del($id = 0)
    {
        return DBQ::del('debit_card', [
            'id' => $id
        ]);
    }
    public function delAll($ids)
    {
        return DBQ::del('debit_card', [
            'id' => $ids
        ]);
    }

    public function edit($id = 0, $data)
    {
        return DBQ::upd('debit_card', $data, [
            'id' => $id
        ]);
    }
}