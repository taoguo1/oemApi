<?php
namespace App\WX\Model;

use Core\Base\Model;
use Core\DB\DBQ;

class Test extends  Model
{
    public function getData()
    {
        return DBQ::getAll('article','*');
        
    }
}