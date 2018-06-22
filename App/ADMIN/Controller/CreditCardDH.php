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

class CreditCardDH extends Controller
{
    public function index()
    {
        $type = Lib::request ( 'type' );
        $year = Lib::request ( 'year' );
        $type = ($type) ? $type : "year";
        $user_id = Lib::request('user_id');
		$agentid = Lib::request ( 'agent_agent_id' );
		$status = Lib::request ( 'status' );
        if($type=="month"){
            $month = Lib::request ( 'month' );
        }else{
            $month =null;
        }
        ($year) ? $year= $year : $year= date("Y");

        //$userid = Lib::request ( 'user_id' );
        $xAxis=[];
        $condition = "1=1";
        $condition .= " AND bill_type = 1 ";
        $condition .= " AND card_type = 1 ";
		($status) ? $condition.= " and status=".$status :"";
		 ($agentid) ? $condition.= " and agent_id=".$agentid :"";

        if(!empty($user_id) && is_numeric($user_id))
         $condition .= " AND user_id=".$user_id;

        if(!empty($month)){

            $daystart=strtotime($year."-".$month."-1"." 00:00:00")."000";
            $dayend=strtotime($year."-".$month."-".Lib::getMonthLastDay($year,$month)." 23:59:59")."999";

            $condition .= " AND (
                            `create_time` BETWEEN ".$daystart."
                            AND ".$dayend."
                        )";
            for($d=1;$d<=Lib::getMonthLastDay($year,$month);$d++){
                if($d<10){
                    $xd='0'.$d;
                }else{
                    $xd=$d;
                }
                array_push($xAxis,$xd.'日');
            }


        }else{
            $xAxis=array("1月","2月","3月","4月","5月","6月","7月","8月","9月","10月","11月","12月");
            $daystart=strtotime($year."-1-1"." 00:00:00")."000";
            $dayend=strtotime($year."-12-31"." 23:59:59")."999";
            $condition .= "  AND (
                                `create_time` BETWEEN ".$daystart."
                                AND ".$dayend."
                            )";
        }

        $this->assign ( "xAxis", json_encode($xAxis));

        $this->assign ( "year", $year );
        $data = $this->M()->getList ($type,$condition,$year,$month);
        $this->assign ( "datasum", array_sum($data));
        $this->assign ( "data", json_encode($data));



        $this->view();
    }


}