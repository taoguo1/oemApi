<?php
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;

//卡测评
class KcpOrder extends Controller {
    public function index(){

        $start_date = Lib::request('start_date');
        $end_date = Lib::request('end_date');

        $condition = null;


        ($start_date) ? $condition ['AND'] ['create_time[>=]'] =strtotime($start_date. " 00:00:00")*1000: null;
        ($end_date) ? $condition ['AND'] ['create_time[<=]'] =strtotime($end_date. " 23:59:59")*1000 : null;

        $condition ['ORDER'] = [
            'id' => 'ASC'
        ];
        $pageArr = Lib::setPagePars();
        if ($pageArr['orderField']) {
            $columns['ORDER'] = [
                $pageArr['orderField'] => strtoupper($pageArr['orderDirection'])
            ];
        }

        $data = $this->M()->getList($pageArr, $condition);

        $this->assign("data", $data);

        $this->view();

    }



}