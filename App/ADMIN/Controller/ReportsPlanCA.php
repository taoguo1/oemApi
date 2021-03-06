<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/29
 * Time: 12:15
 */

namespace App\ADMIN\Controller;

use Core\Lib;
use Core\Base\Controller;
use Core\DB\DBQ;

class ReportsPlanCA extends Controller
{
    public function index()
    {
        $type 		= Lib::request ( 'type' );
        $year 		= Lib::request ( 'year' );
        $month 		= Lib::request ( 'month' );


        $userid 		= Lib::request ( 'user_id' );


        //$condition['AND']['status'] = '5';
        $condition['AND']['plan_type'] = '2';

        ($type) ? $type=$type : $type='day';
        ($year) ? $year= $year : $year= date("Y");
        if(!empty($month)){

            $daystart=strtotime($year."-".$month."-1"." 00:00:00")."000";
            $dayend=strtotime($year."-".$month."-".Lib::getMonthLastDay($year,$month)." 23:59:59")."999";
            $condition['AND']['create_time[<>]'] = [$daystart,$dayend];
        }else{
            $daystart=strtotime($year."-1-1"." 00:00:00")."000";
            $dayend=strtotime($year."-12-31"." 23:59:59")."999";
            $condition['AND']['create_time[<>]'] = [$daystart,$dayend];

        }

        ($userid) ? $condition ['AND'] ['user_id'] = $userid : null;


        $condition['ORDER'] = ['create_time'=>"ASC"];
        $data = $this->M()->getList ($type,$condition,$year,$month);
        $this->assign ( "data", $data );
        $this->assign ( "year", $year );

        $userlist = DBQ::getAll('user',['id','real_name']);

        $this->assign ( "userlist", $userlist );
        $this->view();
    }
}