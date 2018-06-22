<?php
namespace App\API\V100\Model;

use Core\Base\Model;
use Core\DB\DBQ;
use Core\Lib;
use Core\Aliyun\Oss;

class Bank extends Model {
	    public function credit_card(){
// 	    	$data = DBQ::getAll('bank', [
// 	    			"id",
// 	    			"name",
// 	    			"code_hlb",
// 	    			"code_yb"
// 	    	],[
// 	    			"bank_type" => 1,"status"=>1,"ybskb"=>1
// 	    	]
// 	    	);
	    	$data = Lib::getBankConfig(1);
	    	return $data;
	    }
	    public function deposit_card(){
	        $data = Lib::getBankConfig(2);
	    	return $data;
	    }
}