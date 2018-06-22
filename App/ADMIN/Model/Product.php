<?php
namespace App\ADMIN\Model;

use Core\Base\Model;
use Core\DB\DBQ;

class Product extends Model
{

    public function getList($pageArr = null, $condition = null)
    {
        // $pageArr, $table, $join, $columns = null, $where = null
        $data = DBQ::pages($pageArr, 'product (A)', [
            '[>]product_category (B)' => [
                'A.category_id' => 'id'
            ]
        ], [
            'A.id',
            'A.title',
            'A.pic',
            'B.name (category_name)',
            'A.recommend_level',
            'A.last_update_time',
            'A.sort',
            'A.click_number'
        ], $condition);
        
        return $data;
    }

    public function add($data)
    {
        return DBQ::add('product', $data);
    }

    public function del($id = 0)
    {
        return DBQ::del('product', [
            'id' => $id
        ]);
    }

    public function delAll($ids)
    {
        return DBQ::del('product', [
            'id' => $ids
        ]);
    }

    public function edit($id = 0, $data)
    {
        return DBQ::upd('product', $data, [
            'id' => $id
        ]);
    }
}