<?php
namespace App\ADMIN\Model;

use Core\Lib;
use Core\Base\Model;
use Core\DB\DBQ;
use Core\Extend\Dwz;

class MyFirstNews extends Model{
    public function getList($pageArr = null, $condition = null) {
        $data = $this->page($pageArr, 'my_first_news', '*', $condition);
        return $data;
    }

    public function add()
    {
        return $this->insert('my_first_news', [
                'title' => Lib::post ( 'title' ),
                'img_url' => Lib::post ( 'img_url' ),
                'content' => Lib::post ( 'content' ),
                'status' => Lib::post ( 'content' ),
                'sort' => Lib::post ( 'sort' ),
                'create_time' => lib::getMs()
        ]);
    }
    public function del($id = 0)
    {
        return DBQ::del('my_first_news', [
            'id' => $id
        ]);
    }

    public function delAll($ids)
    {
        return DBQ::del('my_first_news', [
            'id' => $ids
        ]);
    }

    public function edit($id = 0, $data=[])
    {   
        $data=[
                'title' => Lib::post ( 'title' ),
                'img_url' => Lib::post ( 'img_url' ),
                'content' => Lib::post ( 'content' ),
                'status' => Lib::post ( 'content' ),
                'sort' => Lib::post ( 'sort' )
        ];
        return DBQ::upd('my_first_news', $data, [
            'id' => $id
        ]);
    }
}