<?php
namespace App\ADMIN\Controller;
use Core\Base\Controller;
use Core\Extend\Session;
use Core\Lib;
use Core\DB\DBQ;
use Core\Base\Model;
use Core\Extend\Dwz;
class ReportsUserDA extends Controller{
    
    public function index(){
        //默认查询方式为day
    	//查询会员
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
	        	$allDate=$this->M()->selByMonth($day_data,$year);
	        	//p($allDate);
		        $this->assign('total',$allDate['total']);//总和
			    $this->assign('sum',$allDate['sum']);//每月数据和
		        $this->assign('day_data',$allDate['day_data']);//每日数据
	        }else{
	        	//每月按小时查询
	        	$allDate=$this->M()->selByHour($day_data,$year,$month);
	        	//p($allDate);
		        $this->assign('total1',$allDate['total1']);
			    $this->assign('sum1',$allDate['sum1']);
		        $this->assign('day_data1',$allDate['day_data1']);
	        }
	    }else{
            if($month==''){
                //按周查询
                $allDate=$this->M()->selByWeek($day_data,$year); 
                foreach($allDate['weekMoney'] as $k=>$v){
                    $allDate['weekMoney'][$k]=array_sum($v);
                }              
                $this->assign('total',$allDate['total']);
                $this->assign('weekMoney',$allDate['weekMoney']);
                $this->assign('day_data',$allDate['day_data']);
            }else{
                //每月按小时查询
                $allDate=$this->M()->selByHour($day_data,$year,$month);
                //p($allDate);
                $this->assign('total1',$allDate['total1']);
                $this->assign('sum1',$allDate['sum1']);
                $this->assign('day_data1',$allDate['day_data1']);
            }
	    }    
        $this->view();
    }
    //循环查数据库，不可取
     /*public function index2(){
    	$list = DBQ::getAll('user', '*', '');
    	$this->assign('list',$list);
    	$userid = Lib::request('userid');//下拉获取用户id
        $type = Lib::request('type');//week  day  默认为day
        $year = Lib::request('year');//按年查询
        $month = Lib::request('month');//按月查询
        $phoneOrname = Lib::request('phoneOrname');//用户名或手机号查询
        $condition = null;
		//查询当前年按天查询数据
        if($type==''&&$userid==''&&$year==''&&$year==''&&$month==''&&$phoneOrname==''){
        	for($i=1;$i<=12;$i++){
        		for($j=1;$j<=31;$j++){
        			$start_date=date('Y',time()).'-'.$i.'-'.$j;
        			$condition ['AND']['create_time[>=]'] =strtotime($start_date. " 00:00:00")*1000;
        	        $condition ['AND']['create_time[<=]'] =strtotime($start_date. " 23:59:59")*1000;
        	        $day_data[$i][$j] = DBQ::getSum('bill', 'amount',$condition);
        		}
        	}
        	$total=0;
        	$sum=[];
        	for($m=1;$m<=count($day_data);$m++){
        		$month_total[$m]=array_sum($day_data[$m]);
        		$total+=$month_total[$m];
        		for($a=1;$a<=31;$a++){
			  	    @$sum[$a]+=$day_data[$m][$a];
			    }
        	}
        	$this->assign('sum',$sum);
        	$this->assign('total',$total);
        	$this->assign('month_total',$month_total);
        }
        $this->assign('day_data',$day_data);
        $this->view();
    }*/
}