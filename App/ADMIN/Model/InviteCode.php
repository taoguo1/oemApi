<?php
namespace App\ADMIN\Model;

use Core\Base\Model;
use Core\DB\DBQ;

class InviteCode extends Model
{
    public function getList($pageArr = null, $condition = null)
    {
        $data = DBQ::pages($pageArr, 'invite_code (A)', [
            '[>]agent (B)' => [
                'A.agent_id' => 'id'
            ]
        ], [
            'A.id',
            'A.code',
            'A.status',
            'A.create_time',
            'B.nickname',
        ], $condition);

        return $data;
    }

    public function add($data)
    {
        return DBQ::add('invite_code', $data);
    }

    public function del($id = 0)
    {
        return DBQ::del('invite_code', [
            'id' => $id
        ]);
    }

    public function delAll($ids)
    {
        return DBQ::del('invite_code', [
            'id' => $ids
        ]);
    }


    /**
     * �޸�������״̬
     * @param $status
     * @param $code
     * @return bool|\PDOStatement
     */
    public function updateInviteCodeStatus($status,$code){
        if(empty($code) || empty($status))return false;
        return DBQ::upd('invite_code',['status'=>$status],['code'=>$code]);
    }

}