<?php
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;
use Core\Extend\Dwz;
use Core\DB\DBQ;
//马来境外消费账单管理
class OverseasProfitByMl extends Controller {
    public function index(){
        $start_date = Lib::request('start_date');
        $end_date = Lib::request('end_date');
        $appid = Lib::request('appid');
        $condition = " WHERE 1";
        if($start_date&&!$end_date){
            $condition .= " and create_time >= " . (strtotime($start_date)) * 1000;
        }
        if(!$start_date&&$end_date){
            $condition .= " and create_time <= " . (strtotime($end_date)) * 1000;
        }
        if($start_date && $end_date) {
            $condition .= " and create_time between " . (strtotime($start_date)) * 1000 . " and " . (strtotime($end_date)) * 1000;
        }
        
        $condition .= " and appid ='$appid'";
        $pageArr = Lib::setPagePars();
        if ($pageArr['orderField']) {
            $columns['ORDER'] = [
                $pageArr['orderField'] => strtoupper($pageArr['orderDirection'])
            ];
        }
        $data = $this->M()->getList($pageArr, $condition);
        $info=new \App\WWW\Model\Cron();
        $dbinfo=$info->getDb($appid);
        $mercInfo=$dbinfo->get('merc',['app_name','appid'],['appid'=>$appid]);
        foreach($data['list'] as $k=>$v){ 
            $data['list'][$k]['app_name']=$mercInfo['app_name']; 
        }
        //获取收益金额
        $count = DBQ::sum('myr_profit','profit',['appid'=>$appid]);
        //获取oem总金额
        $countAmount = DBQ::sum('myr_profit','amount',['appid'=>$appid]);
        $this->assign("count",$count);
        $this->assign("countAmount",$countAmount);
        $this->assign("data", $data);
        $this->view();

    }

    public function oemContact() {
        $appid = Lib::request('appid');
        $app_name = Lib::request('app_name');
        $status = Lib::request('status');
        $start_date = Lib::request('start_date');
        $end_date = Lib::request('end_date');

        $condition = " WHERE 1";
        if ($appid) {
            $condition .= " and appid like '%" . $appid . "%'";
        }
        if ($app_name) {
            $condition .= " and app_name like '%" . $app_name . "%'";
        }
        if ($status) {
            $condition .= " and status = '" . $status . "'";
        }

        if ($start_date || $end_date) {
            $condition .= " and A.create_time between " . (strtotime($start_date)) * 1000 . " and " . (strtotime($end_date)) * 1000;
        }

        $pageArr = Lib::setPagePars();
        if ($pageArr['orderField']) {
            $columns['ORDER'] = [
                $pageArr['orderField'] => strtoupper($pageArr['orderDirection'])
            ];
        }

        $data = $this->M()->oemList($pageArr, $condition);

        $this->assign("data", $data);
        $this->view();
    }

}