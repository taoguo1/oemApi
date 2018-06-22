<?php
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;
use Core\Extend\Dwz;
use Core\DB\DBQ;
class Plan extends Controller {
	public function index() {
		$real_name 		= Lib::request ( 'real_name' );
		$card_no 		= Lib::request ( 'card_no' );
		$start_time1	= Lib::request ( 'start_time1' );
        $start_time2=Lib::request('start_time2');
		$end_time1 		= Lib::request ( 'end_time1' );
        $end_time2 =Lib::request('end_time2');
		$finish_type 	= Lib::request ( 'finish_type' );
        $mobile 		= Lib::request ( 'mobile' );
        $id_card		= Lib::request ( 'id_card' );
        $status 		= Lib::request ( 'status' );
		$condition = null;
		($real_name) ? $condition['AND']['U.real_name[~]'] = $real_name : null;
        ($mobile) ? $condition['AND']['U.mobile'] = $mobile : null;
        ($id_card) ? $condition['AND']['U.id_card'] = Lib::aesEncrypt($id_card) : null;
		($card_no) ? $condition ['AND'] ['P.card_no'] = $card_no : null;
		($start_time1) ? $condition ['AND'] ['P.start_time[>=]'] = strtotime($start_time1) : null;

        ($start_time2) ? $condition ['AND'] ['P.start_time[<=]'] = strtotime($start_time2. " 23:59:59") : null;

		($end_time1) ? $condition ['AND'] ['P.end_time[>=]'] = strtotime($end_time1 ) : null;

        ($end_time2) ? $condition ['AND'] ['P.end_time[<=]'] = strtotime($end_time2 . " 23:59:59") : null;

		($finish_type) ? $condition ['AND'] ['P.finish_type'] = $finish_type : null;
		($status) ? $condition ['AND'] ['P.status'] = $status : null;
		$condition ['ORDER'] = [
			'P.id' => 'DESC'
		];
		$pageArr = Lib::setPagePars ();
		if ($pageArr ['orderField']) {
			$columns ['ORDER'] = [
				$pageArr ['orderField'] => strtoupper ( $pageArr ['orderDirection'] )
			];
		}
		$data = $this->M()->getList ( $pageArr, $condition );
		$this->assign ( "data", $data );
        $dic = Lib::loadFile('Config/Dictionary.php');
        $this->assign ( "planFinishType", $dic['planFinishType'] );
        $this->assign ( "planStatus", $dic['planStatus'] );
		$this->view ();
	}
	
	public function add($act = null) {
        if ($act == 'add') {
            //根据账单日计算开始还款时间
            $stamp = mktime(null,null,null,date('m',time()),6,date('Y',time()));
            $startstampBase = strtotime('+32 hours',(int)$stamp);

            $data = [
                'user_id' => Lib::post ( 'user_id' ),
                'amount' => Lib::post ( 'amount' ),
                'card_no' => Lib::post ( 'card_no' ),
                'start_time' => $startstampBase,
                'end_time' => $startstampBase+3600*24*10,
                'duration' => 10,
                'status' => 1,
                'create_time' => Lib::getMs(),
            ];
            $r = DBQ::add('plan',$data);
            $insertId = DBQ::insertID();
            $tamount = 0;
            for($i = 1;$i <= 10; $i++){
                $paystampBase = strtotime("+$i days",(int)$stamp);
                $randBase = rand(10,240) + 4800;
                $paystamp = strtotime("+$randBase minutes",(int)$paystampBase);
                if($i == 10){
                    $current_amount = $data['amount'] - $tamount;
                }else{
                    $current_amount = $data['amount'] / 10 + rand(-50,50);
                }
                $tamount += $current_amount;
                $data_1 = [
                    'user_id' => Lib::post ( 'user_id' ),
                    'amount' => $current_amount,
                    'plan_id' => $insertId,
                    'plan_type' => 1,
                    'start_time' => $paystamp,
                    'end_time' => $paystamp + rand(1,60),
                    'order_sn' => Lib::createOrderNo(),
                    'status' => 1,
                    'channel' => 2,
                    'create_time' => Lib::getMs(),
                ];
                $rets_1 = DBQ::add('plan_list',$data_1);
                $tamount_2 = 0;
                $consumestampBase = $paystamp;
                $randconsumeBase = rand(10,30);
                $consumeNum = rand(2,3);
                for($j = 1;$j <= $consumeNum; $j++){
                    $consumestampBase = strtotime("+$randconsumeBase minutes",(int)$consumestampBase);
                    if($j == $consumeNum){
                        $current_amount_2 = $current_amount - $tamount_2;
                    }else{
                        $current_amount_2 = ($current_amount / $consumeNum) + rand(-100,100);
                    }
                    $tamount_2 += $current_amount_2;
                    $data_2 = [
                        'user_id' => Lib::post ( 'user_id' ),
                        'amount' => $current_amount_2,
                        'plan_id' => $insertId,
                        'plan_type' => 2,
                        'start_time' => $consumestampBase,
                        'end_time' => $consumestampBase + rand(1,60),
                        'order_sn' => Lib::createOrderNo(),
                        'status' => 1,
                        'channel' => 2,
                        'create_time' => Lib::getMs(),
                    ];
                    $rets_2 = DBQ::add('plan_list',$data_2);
                }

            }
            if ($r) {
                Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
            } else {
                Dwz::err ();
            }
        }

	    $this->view ();
	}
	
	public function del($id = 0) {
		if ($this->M ()->del ( $id )) {
		    Dwz::success ( Lib::getUrl ( $this->M ()->modelName), $this->M ()->modelName );
		} else {
			Dwz::err ();
		}
	}
	
	public function delAll() {
		$ids = explode ( ',', Lib::post ( 'ids' ) );
		if ($this->M ()->delAll ( $ids )) {
			Dwz::success ( Lib::getUrl ( $this->M ()->modelName ), $this->M ()->modelName );
		} else {
			Dwz::err ();
		}
	}
	//查询详情
    public function getDetails($id)
    {
        $condition = null;
        ($id) ? $condition['AND']['P.plan_id'] = $id : null;
        $condition ['ORDER'] = [
            'P.id' => 'DESC'
        ];
        $pageArr = Lib::setPagePars3 ();

        if ($pageArr ['orderField']) {
            $columns ['ORDER'] = [
                $pageArr ['orderField'] => strtoupper ( $pageArr ['orderDirection'] ),
                
            ];
        }
        $plistModel =  "App\ADMIN\Model\PlanList";
        $PlistModel = new $plistModel();
        $data = $PlistModel->getList( $pageArr, $condition );
        $dic = Lib::loadFile('Config/Dictionary.php');
        $this->assign ( "planlistType", $dic['planlistType'] );
        $this->assign ( "planlistStatus", $dic['planlistStatus'] );
        $this->assign ( "data", $data );
        $this->view();
    }
}