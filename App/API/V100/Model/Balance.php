<?php
namespace App\API\V100\Model;
use Core\Base\Model;
use Core\DB\DBQ;
use Core\Lib;
class Balance extends Model{
	public function getList($user_id){
        //获取可提现金额
        $data['balance']=lib::getMayUseMoney($user_id); 
        $data['user_id']=$user_id;			
        return $data;
    }
}