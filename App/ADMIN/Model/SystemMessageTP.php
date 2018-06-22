<?php
namespace App\ADMIN\Model;

use Core\Lib;
use Core\Base\Model;
use Core\DB\DBQ;
use Core\Extend\Dwz;

class SystemMessageTP extends Model{
    public function getList($pageArr = null, $condition = null) {
        $data = $this->page($pageArr, 'system_messagetp', '*', $condition);
        return $data;
    }
    public function add()
    {
    	$user = DBQ::getRow('user', '*', ['id' => Lib::post('user_id')]);
        return $this->insert('system_messagetp', [
        		'title' => Lib::post ( 'title' ),
        		'describe' => Lib::post ( 'describe' ),
        		'content' => Lib::post ( 'content' ),
        		'message_type' => Lib::post ( 'message_type' ),
                'create_time' => lib::getMs()
        ]);
    }
    public function del($id = 0)
    {
        return DBQ::del('system_messagetp', [
            'id' => $id
        ]);
    }

    public function delAll($ids)
    {
        return DBQ::del('system_messagetp', [
            'id' => $ids
        ]);
    }

    public function edit($id = 0, $data=[])
    {   
    	$user = DBQ::getRow('user', '*', ['id' => Lib::post('user_id')]);
        $data=[
        		'title' => Lib::post ( 'title' ),
        		'describe' => Lib::post ( 'describe' ),
        		'content' => Lib::post ( 'content' ),
        		'message_type' => Lib::post ( 'message_type' ),
               
        ];
        return DBQ::upd('system_messagetp', $data, [
            'id' => $id
        ]);
    }
}