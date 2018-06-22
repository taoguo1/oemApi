<?php
namespace App\ADMIN\Model;
use Core\Lib;
use Core\Base\Model;
use Core\Extend\Dwz;

class AppConfig extends Model {
	function getList() {
		$act = Lib::post ( 'act' );
		if ($act == 'edit') {
			$mobile=explode('|',Lib::post ( 'custom_mobile' ));
			if(count($mobile)>5){
				return Dwz::mobile();
			}
			$qq=explode('|',Lib::post ( 'custom_qq' ));
			if(count($qq)>5){
				return Dwz::qq();
			}
			$this->delete ( 'appconfig', ['id[!]'=>0]);
			$this->insert ( 'appconfig', [ 
					'id'=>1,
    			    'custom_qq' => Lib::post ( 'custom_qq' ),
    			    'custom_mobile' => Lib::post ( 'custom_mobile' ),
    			    'custom_qq_status' => Lib::post ( 'custom_qq_status' ),
    			    'custom_mobile_status' => Lib::post ( 'custom_mobile_status' ),
    			    'debit_card_url' => Lib::post ( 'debit_card_url' ),
    			    'credit_card_url' => Lib::post ( 'credit_card_url' ) ,
    			    'loan_url'=>Lib::post('loan_url'),
    			    'user_notice'=>lib::post('user_notice'),
    			    'common_problem'=>lib::post('common_problem'),
    			    'registration_agreement'=>lib::post('registration_agreement'),
    			    'redbag_status'=>lib::post('redbag_status'),
    			    'contact_us'=>lib::post('contact_us')
			] );
			return Dwz::success ( Lib::getUrl('AppConfig' ) );
		}
		$list = $this->get ( 'appconfig', '*' );
		return $list;
	}
}