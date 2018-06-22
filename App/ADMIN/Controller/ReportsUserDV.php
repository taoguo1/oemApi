<?php
namespace App\ADMIN\Controller;
use Core\Base\Controller;
use Core\Extend\Session;
use Core\Lib;
use Core\DB\DBQ;
use Core\Base\Model;
use Core\Extend\Dwz;
use \App\ADMIN\Model\ReportsUserDV as edv;

//use Core\Extend\Redis;
class ReportsUserDV extends Controller{
     public function index(){
        $model=new edv();
        //默认查询方式为day
    	//where条件
        
    	$userid = Lib::request('users_id');//下拉获取用户id
        $type = Lib::request('type');//week  day  默认为day
        $year = Lib::request('year');//按年查询
        $month = Lib::request('month');//按月查询
        $condition = null;
        if($userid!=''){
            $userlist=DBQ::getOne('user',['id','mobile','real_name'],  ["id"=>$userid]);
            $condition ['AND']['user_id']=$userlist['id'];
        }else{
            $userlist['real_name']='';
        }
        $this->assign('real_name',$userlist['real_name']);
        if($year==''){
        	$year=date("Y",time());
        }
    	if($month!=''){
    		$first=$year.'-'.$month.'-'.'01';
            $end=$year.'-'.$month.'-'.'31';
    	}else{
    		$first=$year."-01-01";
            $end=$year."-12-31";
    	}
        $condition ['AND']['is_pay'] =1;
    	$condition ['AND']['bill_type'] =4;
		$condition ['AND']['create_time[>=]'] =strtotime($first. " 00:00:00")*1000;
        $condition ['AND']['create_time[<=]'] =strtotime($end. " 23:59:59")*1000;
        $day_data = DBQ::getAll('bill', ['amount','create_time'],$condition);
		//查询当前年按天查询数据
        if($type==''||$type=='day'){
	        //组装数据
	        if($month==''){
	        	$allDate=$model->selByMonth($day_data,$year);
	        	//p($allDate);
		        $this->assign('total',$allDate['total']);//总和
			    $this->assign('sum',$allDate['sum']);//每月数据和
		        $this->assign('day_data',$allDate['day_data']);//每日数据
	        }else{
	        	//每月按小时查询
	        	$allDate=$model->selByHour($day_data,$year,$month);
	        	//p($allDate);
		        $this->assign('total1',$allDate['total1']);
			    $this->assign('sum1',$allDate['sum1']);
		        $this->assign('day_data1',$allDate['day_data1']);
	        }
	    }else{
	    	if($month==''){
                //按周查询
                $allDate=$model->selByWeek($day_data,$year); 
                foreach($allDate['weekMoney'] as $k=>$v){
                    $allDate['weekMoney'][$k]=array_sum($v);
                }              
                $this->assign('total',$allDate['total']);
                $this->assign('weekMoney',$allDate['weekMoney']);
                $this->assign('day_data',$allDate['day_data']);
            }else{
                //每月按小时查询
                $allDate=$model->selByHour($day_data,$year,$month);
                //p($allDate);
                $this->assign('total1',$allDate['total1']);
                $this->assign('sum1',$allDate['sum1']);
                $this->assign('day_data1',$allDate['day_data1']);
            }
	    }    
        $this->view();
    }


    public function testRedis(){
        
    }
}