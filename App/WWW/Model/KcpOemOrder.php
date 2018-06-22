<?php
namespace App\WWW\Model;

use Core\Base\Model;
use Core\DB\DBQ;
use Core\Lib;

class KcpOemOrder extends Model{
    public function addkcporder($data)
    {
        return DBQ::add('kcpoemorder',$data);
    }

}



?>