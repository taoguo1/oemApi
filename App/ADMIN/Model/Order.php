<?php
namespace App\ADMIN\Model;

use Core\Base\Model;
use Core\DB\DBQ;

class Order extends Model
{
    public function getList($pageArr = null, $condition = null)
    {
        $data = DBQ::pages($pageArr, 'order(A)','*',$condition);
        return $data;
    }
	
	public function getOrderCategory()
	{
		return DBQ::getAll('order', '*');
	}
	
	public function add($data)
	{
		return DBQ::add('order', $data);
	}

  

    public function del($id= 0)
    {
        return DBQ::del('order', [
            'id' => $id
        ]);
    }


    public function edit($id = 0, $data)
    {
        return DBQ::upd('order', $data, [
            'id' => $id
        ]);
    }
}