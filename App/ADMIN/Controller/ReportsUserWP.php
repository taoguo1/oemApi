<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/29
 * Time: 13:40
 */

namespace App\ADMIN\Controller;

use Core\Lib;
use Core\Base\Controller;
use Core\DB\DBQ;

class ReportsUserWP extends Controller
{
    public function index()
    {

        $type 		= Lib::request ( 'type' );
        $year 		= !empty(Lib::request ( 'year' )) ? Lib::request('year') : date("Y",\time());
//        echo $year;die;
        $month 		= !empty(Lib::request ( 'month' )) ? Lib::request('month') :"";

        $keyword 		= Lib::request ( 'keyword' );
        $userid 		= Lib::request ( 'user_id' );
        if($keyword) {
            $condition['AND']['B.mobile'] = $keyword;
        }
        if($userid) {
            $condition['AND']['A.user_id'] = $userid;
        }


        $condition['AND']['A.bill_type'] = 3;
        $condition['AND']['A.user_id[!]'] = 0;
        $condition['AND']['is_pay'] = 1;
        if( !empty($month) ) {
            $startTime = strtotime($year."-01-01 00:00:00")."000";
            $endTime = strtotime($year."-12-31 23:59:59")."999";
            $condition['AND']['A.create_time[<>]'] = [$startTime,$endTime];
        } else {
            $data = Lib::mFristAndLast($year,$month);
            $startTime = $data['firstday'];
            $endTime = $data['lastday'];
            $condition['AND']['A.create_time[<>]'] = [$startTime,$endTime];
        }
//
        $condition['ORDER'] = ['A.create_time'=>"ASC"];
        $type = ($type) ? $type : 'day';

        $data = $this->M()->getList ($type,$condition,$year,$month);
        $this->assign('year',$year);
        $this->assign ( "data", $data );
        $this->assign('userid',$userid);
        $this->assign('phone',$keyword);
        $this->view();
    }
}