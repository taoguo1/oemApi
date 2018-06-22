<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/23
 * Time: 15:45
 */

namespace App\ADMIN\Model;

use Core\Lib;
use Core\Base\Model;
use Core\DB\DBQ;
class Bill extends Model
{
    public function add($data)
    {
        return DBQ::add('bill',$data);
    }

    public function getList($pageArr = null, $condition = null)
    {

        // $pageArr, $table, $join, $columns = null, $where = null
        $data = DBQ::pages(
            //1
            $pageArr,
            //2
            'bill(A)',
            //3
            [
                '[>]user (B)' => [
                    'A.user_id' => 'id'
                ],
                '[>]agent (C)' => [
                    'A.agent_id' => 'id'
                ]
            ],

            //4
            [
                'A.id',
                'A.user_id',
                'A.agent_id',
                'A.bank_name',
                'B.real_name',
            	'A.poundage',
                'C.real_name (agent_real_name)',
                'A.plan_id',
                'A.amount',
                'A.card_type',
                'A.bill_type',
                'A.order_sn',
                'A.bank_id',
                'A.card_no',
                'A.task_no',
                'A.transaction_id',
                'A.channel',
                'A.status',
                'A.create_time',
            	'B.mobile'
            ],
            //5
            $condition
        );
        return $data;
    }

    public function getAgent($pageArr = null, $condition = null)
    {
        // $pageArr, $table, $join, $columns = null, $where = null
        $data = DBQ::pages($pageArr, 'agent', '*' , $condition);
        return $data;
    }

    public function getUser($pageArr = null, $condition = null)
    {
        // $pageArr, $table, $join, $columns = null, $where = null
        $data = DBQ::pages($pageArr, 'user', '*' , $condition);
        return $data;
    }

    public function delAll($ids)
    {
        return DBQ::del('bill', [
            'id' => $ids
        ]);
    }
}