<?php
namespace App\ADMIN\Model;
use Core\Base\Model;
use Core\DB\DBQ;


class KcpOrder  extends Model
{
    public function getList($pageArr = null, $condition = null) {
        $data = DBQ::pages($pageArr,'kcpoemorder','*', $condition);

        return $data;
    }
}