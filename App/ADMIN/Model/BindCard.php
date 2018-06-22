<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/23
 * Time: 15:45
 */

namespace App\ADMIN\Model;

use Core\Base\Model;
use Core\DB\DBQ;

class BindCard extends Model
{
    public function add($data)
    {
        return DBQ::add('bind_card',$data);
    }


    public function getList($pageArr = null, $condition = null)
    {
        // $pageArr, $table, $join, $columns = null, $where = null
        $data = DBQ::pages($pageArr,'bind_card (B)',[
            '[>]user (U)' => [
                'B.user_id' => 'id'
            ]
        ], [
            'B.id',
            'B.user_id',
            'B.bank_id',
            'B.bank_name',
            'B.card_no',
            'U.real_name (user_name)',
            'B.id_card',
            'B.card_no',
            'B.card_type',
            'B.description',
            'B.status',
            'B.channel',
            'B.create_time'
        ],$condition);

        return $data;
    }
}