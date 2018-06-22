<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/29
 * Time: 10:56
 */
namespace App\ADMIN\Controller;
use Core\DB\DBQ;
use Core\Lib;
use Core\Extend\Dwz;
use Core\Base\Controller;

class ReportsYilianPV extends Controller
{	
    public function index()
    {
        $type = Lib::request ( 'type' );
        $year = Lib::request ( 'year' );
        $month = Lib::request ( 'month' );
        $keyword = Lib::request ( 'keyword' );
        $userid = Lib::request ( 'userid' );
		$condition['AND']['A.bill_type'] = '3'; //提现代付
//		$condition['AND']['is_pay'] = '1'; //-1未支付1已支付
		$condition['AND']['A.channel'] = '1'; //1易联2易宝		
        ($type) ? $type=$type : $type='day';
        ($year) ? $year= $year : $year= date("Y");
        if(!empty($month)){
                $daystart=strtotime($year."-".$month."-1"." 00:00:00")."000";
                $dayend=strtotime($year."-".$month."-".Lib::getMonthLastDay($year,$month)." 23:59:59")."999";
                $condition['AND']['A.create_time[<>]'] = [$daystart,$dayend];
        }else{
                $daystart=strtotime($year."-1-1"." 00:00:00")."000";
                $dayend=strtotime($year."-12-31"." 23:59:59")."999";
                $condition['AND']['A.create_time[<>]'] = [$daystart,$dayend];
        }
        ($keyword) ? $condition['AND']['B.mobile'] = $keyword : null;
        ($userid) ? $condition ['AND'] ['user_id'] = $userid : null;		
        $condition['ORDER'] = ['A.create_time'=>"ASC"];
        $data = $this->M()->getList ($type,$condition,$year,$month);
        $this->assign ( "data", $data );
		$this->assign ( "year", $year );
        $userlist = DBQ::getAll('user',['id','real_name']);
        $this->assign ( "userlist", $userlist );
        $this->view();
    }
}	
