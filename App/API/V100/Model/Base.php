<?php

/**
 * 基础模型
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/2/23
 * Time: 10:02
 */
namespace App\API\V100\Model;

use Core\Base\Model;
use Core\DB\DBQ;
use Core\Extend\Redis;
use Core\Lib;
use Core\Aliyun\Oss;

class Base extends Model {
	/**
	 * 校样验证码
	 * 
	 * @param
	 *        	$mobile
	 * @param
	 *        	$code
	 * @param $check_reg //验证是否注册
	 *        	true 验证 false 不验证
	 * @param
	 *        	type //用户 1 验证 代理 2
	 * @return array|bool
	 */
	public function  checkCodeValidity($mobile, $code,$app_id, $check_reg = true, $type = 1) {
		if (empty ( $mobile ) || empty ( $code )) {
			return array (
					'status' => 'fail',
					'code' => 1000,
					'msg' => '验证码错误' 
			);
		}
		
		// 手机号码是否注册
// 		if ($check_reg) {
// 			$table = $type == 1 ? 'user' : 'agent';
// 			$condition ['AND'] ['mobile'] = $mobile;
// 			$user_info_ext = DBQ::getRow ( $table, '*', $condition );
// 			if (empty ( $user_info_ext )) {
// 				return array (
// 						'status' => 'fail',
// 						'code' => 1000,
// 						'msg' => '手机号尚未注册' 
// 				);
// 			}
// 		}
		
		// 验证码是否过期
/*		$condition ['AND'] ['mobile'] = $mobile;
		$condition ['AND'] ['code'] = $code;
		$condition ['ORDER'] = [
				'create_time' => 'DESC' 
		];*/
        $redis = Redis::instance('msg');
        $mobile_redis = $redis->zRangeByScore($app_id.'_code',$mobile,$mobile,['withscores'=>true,'limit'=>[0,100000]]);
        $verifyCode = null;
        if (!empty($mobile_redis)) {
            foreach ($mobile_redis as $k => $v) {
                $row_redis = null;
                $row_redis = json_decode($k,true);
                if ($row_redis['code'] == $code &&  $row_redis['mobile'] == $mobile) {
                    $verifyCode = $row_redis;
                    break;
                }
            }
        }
		if (empty ( $verifyCode )) {
			return array (
					'status' => 'fail',
					'code' => 1000,
					'msg' => '无效的验证码'
			);
		}
		// 已使用
		if ($verifyCode ['status'] != 1) {
			return array (
					'status' => 'fail',
					'code' => 1000,
					'msg' => '无效的验证码'
			);
		}
		
		$compareTime = Lib::getMs () - $verifyCode ['create_time'] - 900000;
		if ($compareTime > 0) {
			return array (
					'status' => 'fail',
					'code' => 1000,
					'msg' => '验证码已过期' 
			);
		}
		return array (
				'status' => 'success',
				'code' => 1000,
				'msg' => '验证码状态正常',
				'verifycode' => $verifyCode 
		);
	}
	
	/**
	 * 上传文件
	 * 
	 * @param
	 *        	$upload_file
	 * @param
	 *        	$folder
	 * @return array|string
	 */
	public function Upload($upload_file, $folder) {
		if (empty ( $upload_file )) {
			return array (
					'status' => 'fail',
					'code' => 1000,
					'msg' => '上传失败' 
			);
		}
		// 过滤base64，头信息标示
		$upload_file = str_replace ( 'data:image/jpeg;base64,', '', $upload_file );
		$upload_file = str_replace ( 'data:image/png;base64,', '', $upload_file );
		$upload_file = str_replace ( 'data:image/gif;base64,', '', $upload_file );
		$upload_file = str_replace ( 'data:image/bmp;base64,', '', $upload_file );
		
		// 上传位置
		$img_path = "Public/Uploads/{$folder}/" . date ( 'YmdHis' ) . "_" . rand ( 100000, 999999 ) . "-local.jpg";
		$save_path = APP_PATH . $img_path;
		
		$result = file_put_contents ( $save_path, base64_decode ( $upload_file ) );
		if ($result) {
			return $img_path;
		} else {
			return false;
		}
	}
	
	/**
	 * 修改验证码状态为已使用
	 * 
	 * @param
	 *        	$id
	 */
/*	public function setCaptchaStatus($id) {
		if (empty ( $id ))
			return false;
		DBQ::upd ( 'verifycode', [ 
				'status' => 2 
		], [ 
				'id' => $id 
		] );
		return true;
	}*/
    public function setCaptchaStatus($code_info) {
        if (empty ( $code_info )) return false;
        $redis =  Redis::instance('msg');
        $redis_list  = $redis->zRangeByScore($code_info['appid'].'_code',$code_info['mobile'],$code_info['mobile'],['withscores'=>true,'limit'=>[0,100000]]);
        if (!empty($redis_list)) {
            foreach ($redis_list as $k => $v) {
                $row_redis = null;
                $row_redis = json_decode($k,true);
                if ($row_redis['code'] == $code_info['code'] &&  $row_redis['mobile'] == $code_info['mobile']) {
                    $verifyCode = $row_redis;
                    $verifyCode['status'] = 2;
                    //删除掉redis存的该元素
                    $redis->zRem($code_info['appid'].'_code',$k);
                    $redis->zAdd($code_info['appid'].'_code',$code_info['mobile'],json_encode($verifyCode));
                }
            }
        }
        return true;
    }
	
	/**
	 * 上传文件
	 * 
	 * @param
	 *        	$file_path
	 * @return bool
	 */
	public function uploadFileOss($file_path) {
		if (empty ( $file_path ))
			return false;
		$oss_obj = Oss::instance ();
		$file_oss_name = Lib::request('appid').'/app/'.date ( 'YmdHis' ) . "_" . rand ( 100000, 999999 ) . "-local.jpg";
		$resp = $oss_obj->uploadOss ( $file_oss_name, $file_path );
		if (! empty ( $resp )) {
			return $resp ['info'] ['url'];
		}
		return false;
	}
	/**
	 * 银行卡识别
	 * 
	 * @param
	 *        	$file
	 */
	public function cardUpload($file) {
		if (empty ( $file ['file'] ['name'] )) {
			Lib::outputJson ( array (
					'status' => 'fail',
					'code' => 1000,
					'msg' => '上传失败' 
			) );
		}
		
		$fileUrl = $this->uploadFileOss ( $file ['file'] ['tmp_name'] );
		if (! $fileUrl) {
			Lib::outputJson ( array (
					'status' => 'fail',
					'code' => 1000,
					'msg' => '上传失败' 
			) );
		}
		// 提取表单内容
		$result = json_decode ( Lib::ocrBankCard ( $fileUrl ), true );
		if($result==null){
            Lib::outputJson ( array (
                'status' => 'fail',
                'code' => 1000,
                'msg' => '系统内部错误'
            ) );
        }
		if ($result ['error_code'] != 0) {
			Lib::outputJson ( array (
					'status' => 'fail',
					'code' => $result ['error_code'],
					'msg' => $result ['reason'] 
			) );
		}
		$data ['status'] = 'success';
		$data ['code'] = $result ['error_code'];
		$data ['reason'] = $result ['reason'];
		$data ['file_url'] = $fileUrl;
		$res = json_decode ( Lib::searchBankCard ( \preg_replace ( '# #', '', $result ['result'] ['cardnumber'] ) ), true );
        if($res ['error_code'] != 0 || empty($res ['result']['bankname'])){
            Lib::outputJson ( array (
                'status' => 'fail',
                'code' => $res ['error_code'],
                'msg' => "未识别到该银行卡"
            ) );
        }
		$data ['result'] = $res ['result'];
		$data ['result'] ['cardnumber'] = $result ['result'] ['cardnumber'];
		return $data;
	}
	/**
	 * 银行卡号查询
	 * 
	 * @param
	 *        	$file
	 */
	public function cardNumber($cardnumber) {
		if (empty ( $cardnumber ) || ! (\ctype_digit ( $cardnumber ))) {
			Lib::outputJson ( array (
					'status' => 'fail',
					'code' => 1000,
					'msg' => '卡号不能为空或格式错误' 
			) );
		}
		$result = json_decode ( Lib::searchBankCard ( $cardnumber ), true );

		if ($result ['error_code'] != 0 || empty($result ['result']['bankname']) ) {
			Lib::outputJson ( array (
					'status' => 'fail',
					'code' => $result ['error_code'],
					'msg' => "未识别到该银行卡"
			) );
		}
		$data ['status'] = 'success';
		$data ['code'] = $result ['error_code'];
		$data ['result'] = $result ['result'];
		$data ['result'] ['cardnumber'] = $cardnumber;
		return $data;
	}
	public static function getBank() {
	    $data = Lib::getBankConfig(-1);
		return $data;
	}
	public static function getBanker() {
		$data = DBQ::getAll ( 'banker', [
				"id",
				"name",
				"description",
				"url",
				'img'
		] );
		return $data;
	}
    /**
     * 银行卡号认证
     *
     * @param
     *
     */
    public function cardVerification($real_name,$card_no,$id_card,$mobile)
    {
        $data = [
            'name' =>$real_name,
            'bankno' =>$card_no,
            'idnumber' =>$id_card,
            'mobile' => $mobile
        ];
        $cardVerification = Lib::httpPostUrlEncode(EX_SERVICE.'exchange/RealnameAuth/cardFourItemsAndImage',$data);
        return \json_decode($cardVerification,true);
    }
    //写日志
    public function myLog($name,$content){
        $content['LogTime']=time();
        file_put_contents('Logs/'.date('Ymd-H',time()).'-'.$name,json_encode($content)."\n",FILE_APPEND);
    }
}