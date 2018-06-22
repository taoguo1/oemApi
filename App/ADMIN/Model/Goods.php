<?php
namespace App\ADMIN\Model;

use Core\Base\Model;
use Core\DB\DBQ;

class Goods extends Model
{
    public function getList($pageArr = null, $condition = null)
    {
        $data = DBQ::pages($pageArr, 'goods (A)', [
            '[>]goods_category (B)' => [
                'A.category_id' => 'id'
            ]
        ], [
            'A.id',
            'A.goods_name',
            'B.category_name(category_namess)'
        ], $condition);

        return $data;
    }

    public function getGoodsCategory()
    {
        return DBQ::getAll('goods_category', '*');
    }

    public function add($data)
    {
        return DBQ::add('goods', $data);
    }
}

