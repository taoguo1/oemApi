<?php
namespace App\ADMIN\Model;

use Core\Base\Model;
use Core\DB\DBQ;

class Article extends Model
{

    public function getList($pageArr = null, $condition = null)
    {

        // $pageArr, $table, $join, $columns = null, $where = null
        $data = DBQ::pages($pageArr, 'article (A)', [
            '[>]article_category (B)' => [
                'A.category_id' => 'id'
            ]
        ], [
            'A.id',
        	'A.category_id',
            'A.title',
            'B.name (category_name)',
            'A.recommend_level',
            'A.last_update_time',
        	'A.pic',
            'A.sort',
            'A.click_number'
        ], $condition);
        
        return $data;
    }

    public function add($data)
    {
        return DBQ::add('article', $data);
    }

    public function del($id = 0)
    {
        return DBQ::del('article', [
            'id' => $id
        ]);
    }

    public function delAll($ids)
    {
        return DBQ::del('article', [
            'id' => $ids
        ]);
    }

    public function edit($id = 0, $data)
    {
        return DBQ::upd('article', $data, [
            'id' => $id
        ]);
    }
}