<?php
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;
use Core\Extend\Dwz;
use Core\DB\DBQ;
class PlanList extends Controller {
    public function index($id = null) {
        $real_name 		= Lib::request ( 'real_name' );
        $plan_type 		= Lib::request ( 'plan_type' );
        $start_time1 	= Lib::request ( 'start_time1' );


        $start_time2=Lib::request('start_time2');
        $end_time1      = Lib::request ( 'end_time1' );
        $end_time2 =Lib::request('end_time2');

        $end_time 		= Lib::request ( 'end_time' );
        $finish_type 	= Lib::request ( 'finish_type' );
        $status 		= Lib::request ( 'status' );
        $order_sn 		= Lib::request ( 'order_sn' );
        $mobile 		= Lib::request ( 'mobile' );
        $id_card		= Lib::request ( 'id_card' );
        $agent_id = Lib::request('agent_agent_id');

        $condition = null;
        ($id) ? $condition['AND']['P.plan_id'] = $id : null;
        ($agent_id) ? $condition['AND']['U.agent_id'] = $agent_id : null;
        ($real_name) ? $condition['AND']['U.real_name[~]'] = $real_name : null;
        ($mobile) ? $condition['AND']['U.mobile'] = $mobile : null;
        ($id_card) ? $condition['AND']['U.id_card'] = Lib::aesEncrypt($id_card) : null;
        ($plan_type) ? $condition ['AND'] ['P.plan_type'] = $plan_type : null;
        ($order_sn) ? $condition ['AND'] ['P.order_sn'] = $order_sn : null;
        //($start_time) ? $condition ['AND'] ['P.start_time[>=]'] = strtotime($start_time) : null;
        //($end_time) ? $condition ['AND'] ['P.start_time[<=]'] = strtotime($end_time . " 23:59:59") : null;

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

        $dic = Lib::loadFile('Config/Dictionary.php');
        $data = $this->M()->getList ( $pageArr, $condition );
        $this->assign ( "data", $data );
        $this->assign('plan_type', $plan_type);
        $this->assign ( "planlistType", $dic['planlistType'] );
        $this->assign ( "planlistStatus", $dic['planlistStatus'] );
        $this->view ();
    }

    public function search($id = null) {
        $real_name 		= Lib::request ( 'real_name' );
        $plan_type 		= Lib::request ( 'plan_type' );
        $start_time 	= Lib::request ( 'start_time' );
        $end_time 		= Lib::request ( 'end_time' );
        $finish_type 	= Lib::request ( 'finish_type' );
        $status 		= Lib::request ( 'status' );
        $condition = null;
        ($id) ? $condition['AND']['P.plan_id'] = $id : null;
        ($real_name) ? $condition['AND']['U.real_name[~]'] = $real_name : null;
        ($plan_type) ? $condition ['AND'] ['P.plan_type'] = $plan_type : null;
        ($start_time) ? $condition ['AND'] ['P.start_time[>=]'] = strtotime($start_time) : null;
        ($end_time) ? $condition ['AND'] ['P.end_time[<=]'] = strtotime($end_time . " 23:59:59") : null;
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
        $dic = Lib::loadFile('Config/Dictionary.php');
        $data = $this->M()->getList ( $pageArr, $condition );
        $this->assign ( "data", $data );
        $this->assign ( "planlistType", $dic['planlistType'] );
        $this->assign ( "planlistStatus", $dic['planlistStatus'] );
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

    public function redisAll() {

        $ids = explode ( ',', Lib::post ( 'ids' ) );
        if ($this->M ()->redisAll ( $ids )) {
            Dwz::success ( Lib::getUrl ( $this->M ()->modelName ), $this->M ()->modelName );
        } else {
            Dwz::err ();
        }
    }


    public function edit($id = 0, $act = null){
        if($act=='edit'&&!empty($id)){ 
            $data['status']=Lib::post('status');
            $upt=$this->M()->edit($id,$data);
            if($upt==1){
                Dwz::successDialog ($this->M()->modelName,'','closeCurrent');
            }else{
                Dwz::err();
            }
        }
        $list=$this->M()->db->get( "plan_list","*",['id'=>$id]);
        $this->assign ("list",$list);
        $this->view();
    }

    public function update(){
        $id=Lib::post('id');
        $name=Lib::post('name');
        $str=Lib::post('str');
        $date[$name]=strtotime(Lib::post('str'));

        $ses=DBQ::getOne('plan_list','*',['id'=>$id]);
        if($ses[$name]==$date[$name]){
            echo json_decode('-1');
        }else{
            $res=DBQ::upd('plan_list',[$name=>$date[$name]],['id'=>$id]);
            echo json_decode($res);
        }
        
    }

    public function second($id = 0, $act = null){
        if($act=='second'&&!empty($id)){ 
            $data['status']=Lib::post('status');
            $upt = $this->M()->second($id,$data);
            if($upt==1){
                Dwz::successDialog ($this->M()->modelName,'','closeCurrent');
            }else{
                Dwz::err();
            }
        }
        $list=$this->M()->db->get( "plan_list","*",['id'=>$id]);
        $this->assign ("list",$list);
        $this->view();
    }
}