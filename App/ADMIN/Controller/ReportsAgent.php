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

class ReportsAgent extends Controller
{
    public function index()
    {
        $type = Lib::request ( 'type' );
        $year = Lib::request ( 'year' );
        $month = Lib::request ( 'month' );
        $nickname = Lib::request('agent_agent_name');
        $condition['AND']['status'] = '1'; //状态1正常，-1禁用',
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
        ($nickname) ? $condition['AND']['nickname'] = $nickname : null;
        $condition['ORDER'] = ['create_time'=>"ASC"];
        $data = $this->M()->getList ($type,$condition,$year,$month);
        $this->assign ( "data", $data );
        $this->assign ( "year", $year );
        $userlist = DBQ::getAll('user',['id','real_name','mobile']);
        $this->assign ( "userlist", $userlist );


        $this->view();
    }
}
