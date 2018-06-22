<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/27
 * Time: 11:56
 */

namespace App\ADMIN\Model;

use Core\DB\DBQ;
use Core\Base\Model;

class Verifycode extends Model
{

    public function add($data)
    {
        return DBQ::add('verifycode',$data);
    }


    public function getList($pageArr = null, $condition = null)
    {
        // $pageArr, $table, $join, $columns = null, $where = null
        $data = DBQ::pages($pageArr, 'verifycode', '*', $condition);

        return $data;
    }
    /**
     * 短信批量删除
     */
    public function delAll($ids)
    {
        return DBQ::del('verifycode', [
            'id' => $ids
        ]);
    }
}