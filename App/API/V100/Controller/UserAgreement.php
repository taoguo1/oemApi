<?php
namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;

class UserAgreement extends Controller
{
	public function agreement(){

			$header=Lib::getallheaders();
		    $V = "\\App\\API\\" .$header['VERSION'] . "\\Model\\News";
			
			$datalist = $V->agreementss();
			$data = [
					'status' => 'success',
					'code' => 10000,
					'msg' => '获取成功',
					'data' => $datalist
			];
		
			Lib::outputJson($data);
		}
	}
