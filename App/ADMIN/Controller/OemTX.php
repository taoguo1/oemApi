<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/18
 * Time: 11:56
 */

/****
 * @name TX
 *
 *
 */

namespace App\ADMIN\Controller;

use\Core\DB\DBQ;
use Core\Base\Controller;
use Core\Lib;
use Core\Extend\Dwz;

class OemTX extends Controller
{
    /**
     *
     * @name TX查询
     */
    public function index()
    {

        echo "请使用手机提现";die;
        $oem_no = Lib::request('oem_no');
        $oem_status = Lib::request('oem_status');

        $start_oem_createtime = Lib::request('start_oem_createtime');
        $end_oem_createtime = Lib::request('end_oem_createtime');
        $start_oem_amount = Lib::request('start_oem_amount');
        $end_oem_amount = Lib::request('end_oem_amount');

        $condition = null;
        ($oem_no) ? $condition['AND']['oem_no'] = $oem_no : null;
        ($oem_status) ? $condition['AND']['oem_status'] = $oem_status : null;

        ($start_oem_createtime) ? $condition ['AND'] ['oem_createtime[>=]'] =  (strtotime($start_oem_createtime))*1000 : null;
        ($end_oem_createtime) ? $condition ['AND'] ['oem_createtime[<=]'] = (strtotime($end_oem_createtime))*1000 : null;
        ($start_oem_amount) ? $condition ['AND'] ['oem_amount[>=]'] =  $start_oem_amount : null;
        ($end_oem_amount) ? $condition ['AND'] ['oem_amount[<=]'] = $end_oem_amount : null;

        $condition ['ORDER'] = [
            'id' => 'ASC'
        ];
        $pageArr = Lib::setPagePars();
        if ($pageArr['orderField']) {
            $columns['ORDER'] = [
                $pageArr['orderField'] => strtoupper($pageArr['orderDirection'])
            ];
        }

        $recordMoney = $this->M()->recordMoney();//历史提现
        $maxMoney = $this->M()->withAmount();//可提现最大额度

        $data = $this->M()->getList($pageArr, $condition);
        $this->assign("maxMoney",$maxMoney);
        $this->assign("recordMoney",$recordMoney);
        $this->assign("data", $data);
        $this->view();
    }


    /**
     *
     * @name TX申请
     */
    public function add($act = null,$appid= null)
    {

        if ($act == 'add') {
            $oem_amount=Lib::post('oem_amount');
            $oem_name=Lib::post('oem_name');
            if(intval($oem_amount)<100){
                Dwz::err ('每次申请金额不能小于100元');
            }
            if(empty($oem_name)){
                Dwz::err ('申请人不能为空');
            }
            if(empty($appid)){
                Dwz::err ('appid不能为空');
            }

            $withAmount = $this->M()->withAmount();

            if( $oem_amount > $withAmount ) {

                Dwz::err("您最大提现额度为".$withAmount);
                exit;
            }

            $data = [
                'oem_no' => Lib::createOrderNo(),
                'oem_appid' => $appid,
                'oem_name' => $oem_name,
                'oem_amount' => $oem_amount,
                'oem_desciption' => Lib::post('oem_desciption'),
                'oem_createtime' => Lib::getMs(),
                'oem_status' => 0,
                ];
            $insertId = $this->M()->add($data);
            if ($insertId) {
                Dwz::successDialog($this->M()->modelName, '', 'closeCurrent');
            }
        }
        $this->view();
    }



    /***
     *
     * @name TX修改
     */
    public function edit($id = 0, $act = null,$appid= null)
    {
        if(empty($id)){
            Dwz::err ('请选择你要修改的选项');
        }
        $dataone = DBQ::getOne('oemtx',['oem_status'], ['id'=>$id]);
        if($dataone['oem_status']!=0){
            Dwz::err ('该订单已审核，不可修改');
        }
        if ($act == 'edit') {
            $oem_amount=Lib::post('oem_amount');
            $oem_name=Lib::post('oem_name');
            if(intval($oem_amount)<100){
                Dwz::err ('每次申请金额不能小于100元');
            }
            if(empty($oem_name)){
                Dwz::err ('申请人不能为空');
            }
            if(empty($appid)){
                Dwz::err ('appid不能为空');
            }
            $withAmount = $this->M()->withAmount();

            if( $oem_amount > $withAmount ) {

                Dwz::err("您最大提现额度为".$withAmount);
                exit;
            }

            $data = [
                'oem_no' => Lib::createOrderNo(),
                'oem_appid' => $appid,
                'oem_name' => $oem_name,
                'oem_amount' => $oem_amount,
                'oem_desciption' => Lib::post('oem_desciption'),
                'oem_createtime' => Lib::getMs(),
                'oem_status' => 0,
            ];
            $insertId = $this->M()->edit($data,$id);
            if ($insertId) {
                Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
            } else {
                Dwz::err ();
            }
        }

        $list = DBQ::getRow('oemtx', '*', ['id' => $id]);
        $this->assign('list', $list);
        $this->view();
    }

}