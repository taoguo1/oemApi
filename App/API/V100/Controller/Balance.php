<?php

namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;
use Core\DB\DBQ;
class Balance  extends Controller
{
	public function index(){

			
    	$this->headers = Lib::getAllHeaders();
    	$model = "\\App\API\\".$this->headers['VERSION']."\\Model\\Balance";
		$yy = new $model;
		$datalist = $yy->getList($this->headers['UID']);
		$data = [
				'status' => 'success',
				'code' => 10000,
				'msg' => '获取成功',
				'data' => $datalist
		];	
		Lib::outputJson($data);
		}
}
	
