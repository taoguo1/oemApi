<?php
namespace App\ADMIN\Model;

use Core\Lib;
use Core\Base\Model;
use Core\DB\DBQ;
use Core\Extend\Dwz;

class SystemMessage extends Model{
    public function getList($pageArr = null, $condition = null) {
        $join=[];
        $array=[];
        if($condition['AND']['M.user_type']==1){
            $join=[
                    '[>]user(U)'=> [
                        'M.uid' => 'id'
                    ]
                ];
            $array=[
                'M.id',
                'M.uid',
                'U.real_name',
                'M.user_type',
                'M.status',
                'M.type',
                'M.read_unread',
                'M.title',
                'M.describe',
                'M.content',
                'M.create_time'
            ];
        }else{
            $join=[
                '[>]agent(A)'=> [
                    'M.uid' => 'id'
                ]
            ];
            $array=[
                'M.id',
                'M.uid',
                'A.real_name',
                'M.user_type',
                'M.status',
                'M.type',
                'M.read_unread',
                'M.title',
                'M.describe',
                'M.content',
                'M.create_time'
            ];
        }
        $data = DBQ::pages($pageArr, 'system_message(M)',$join,$array,$condition);
        return $data;
    }
    public function add()
    {
    	$user = DBQ::getRow('user', '*', ['id' => Lib::post('user_id')]);
        return $this->insert('system_message', [
    			'uid' => $user['id'],
        		'status' => 1 ,
        		'title' => Lib::post ( 'title' ),
                'user_type' => Lib::post ( 'user_type' ),
                'type' => Lib::post ( 'type' ),
                'describe' => Lib::post ( 'describe' ),
                'content' => Lib::post ( 'content' ),
                'read_unread' => Lib::post ( 'read_unread' ),
                'create_time' => lib::getMs()
        ]);
    }

    public function del($id = 0)
    {
        return DBQ::del('system_message', [
            'id' => $id
        ]);
    }

    public function delAll($ids)
    {
        return DBQ::del('system_message', [
            'id' => $ids
        ]);
    }

    public function edit($id = 0, $data=[])
    {   
    	$user = DBQ::getRow('user', '*', ['id' => Lib::post('user_id')]);
        $data=[
//      		'uid' => $user['id'],
				'uid' => Lib::post('user_id'),
        		'status' => 1 ,
        		'read_unread' => Lib::post ( 'read_unread' ),
                'title' => Lib::post ( 'title' ),
                'user_type' => Lib::post ( 'user_type' ),
                'type' => Lib::post ( 'type' ),
                'describe' => Lib::post ( 'describe' ),
                'content' => Lib::post ( 'content' ),
               
        ];
        return DBQ::upd('system_message', $data, [
            'id' => $id
        ]);
    }
}