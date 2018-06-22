<?php
namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;
use Core\DB\DBQ;
class AppConfig extends Controller{
    public function getAppConfig(){
        $res=DBQ::getOne('appconfig','*');
		if($res){
            $res['custom_qq']=explode('|',$res['custom_qq']);
            $res['custom_mobile']=explode('|',$res['custom_mobile']);

            $res['contact_us']=OSS_ENDDOMAIN.'/'.$res['contact_us'];
			$data=[
                'status' => 'success',
                'code' => 10000,
                'msg' => '获取成功',
                'data' => $res
            ];  
		}else{
			$data=[
                'status' => 'fail',
                'code' => 1000,
                'msg' => '获取失败'
            ];
		}
                 
        Lib::outputJson($data);
    }
}