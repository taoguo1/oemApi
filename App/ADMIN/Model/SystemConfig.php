<?php
namespace App\ADMIN\Model;
use Core\Lib;
use Core\Base\Model;
use Core\Extend\Dwz;

class SystemConfig extends Model {
	function getList() {
		$act = Lib::post ( 'act' );
		if ($act == 'edit') {
			$this->delete ( 'system_config', ['id[!]'=>0]);
			$this->insert ( 'system_config', [ 
					'id'=>1,
    			    'login_header_title' => Lib::post ( 'login_header_title' ),
    			    'login_footer_copyright' => Lib::post ( 'login_footer_copyright' ),
    			    'login_title' => Lib::post ( 'login_title' ),
    			    'system_name' => Lib::post ( 'system_name' ),
    			    'system_title' => Lib::post ( 'system_title' ),
    			    'system_copyright' => Lib::post ( 'system_copyright' ) ,
    			    'is_show_left_tree'=>Lib::post('is_show_left_tree'),
    			    'is_show_custom'=>lib::post('is_show_custom'),//客服热线是否显示在app上 0：不显示 1：显示
    			    'my_custom'=>lib::post('my_custom'),//我的客服是否显示在app上 0：不显示 1：显示
    			    'is_show_redbag'=>lib::post('is_show_redbag'),//是否显示签到红包  0：不显示 1：显示
    			    //'company_mobile'=>lib::post('company_mobile'),
    			    'my_custom_num'=>lib::post('my_custom_num'),
    			    'custom_num'=>lib::post('custom_num')
			] );
			return Dwz::success ( Lib::getUrl ( 'SystemConfig' ) );
		}
		$list = $this->get ( 'system_config', '*' );
		return $list;
	}
	function edit() {
	}
}