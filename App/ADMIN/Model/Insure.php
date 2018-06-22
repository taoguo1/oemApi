<?php
namespace App\ADMIN\Model;
use Core\Lib;
use Core\Base\Model;
use Core\DB\DBQ;
use Core\Extend\Dwz;
class Insure extends Model{
    public function getList($pageArr = null, $condition = null){
        $data = $this->page($pageArr, 'insure', '*', $condition);
        return $data;
    }

    public function add(){
        return $this->insert('insure', [
                'title' => Lib::post ( 'title' ),
                'pic' => Lib::post ( 'pic' ),
                'article_source' => Lib::post ( 'article_source' ),
                'status' =>  Lib::post ( 'status' ),
                'sort' => Lib::post ( 'sort' ),
                'remarks' => Lib::post ( 'remarks' )
        ]);
    }

    public function del($id = 0){
        return DBQ::del('insure', [
            'id' => $id
        ]);
    }

    public function delAll($ids){
        return DBQ::del('insure', [
            'id' => $ids
        ]);
    }

    public function edit($id = 0, $data=[]){   
        $data=[
                'title' => Lib::post ( 'title' ),
                'pic' => Lib::post ( 'pic' ),
                'article_source' => Lib::post ( 'article_source' ),
                'status' =>  Lib::post ( 'status' ),
                'sort' => Lib::post ( 'sort' ),
                'remarks' => Lib::post ( 'remarks' )
        ];
        return DBQ::upd('insure', $data, [
            'id' => $id
        ]);
    }
}