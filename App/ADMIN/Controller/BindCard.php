<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/23
 * Time: 15:14
 */

namespace App\ADMIN\Controller;

use Core\Base\Controller;
use Core\DB\DBQ;
use Core\Lib;
use Core\Extend\Dwz;
class BindCard extends Controller
{
    /**
     *
     * @name 绑卡记录查询
     */
    public function index(){
        $user_id = Lib::request ( 'user_id' );
        $bank_id = Lib::request ( 'bank_id' );
        $channel = Lib::request ( 'channel' );
        $card_type = Lib::request ( 'card_type' );
        $status =Lib::request('status');
        $start_create_time  = Lib::request ( 'start_create_time' );
        $end_create_time    = Lib::request ( 'end_create_time' );
        $condition = null;
        ($user_id) ? $condition ['AND'] ['user_id'] = $user_id : null;
        ($bank_id) ? $condition ['AND'] ['bank_id'] = $bank_id : null;
        ($channel) ? $condition ['AND'] ['channel'] = $channel : null;
        ($card_type) ? $condition['AND']['card_type'] = $card_type : null;
        ($status) ? $condition['AND']['B.status'] = $status : null;
        ($start_create_time) ? $condition['AND']['B.create_time[>=]'] = strtotime($start_create_time. " 00:00:00")*1000: null;
        ($end_create_time) ? $condition['AND']['B.create_time[<=]'] = strtotime($end_create_time. " 23:59:59")*1000 : null;

        $condition ['ORDER'] = [
            'B.id' => 'DESC'
        ];

        $pageArr = Lib::setPagePars ();
        if ($pageArr ['orderField']) {
            $columns ['ORDER'] = [
                $pageArr ['orderField'] => strtoupper ( $pageArr ['orderDirection'] )
            ];
        }
        $data = $this->M()->getList ( $pageArr, $condition );
        //引入通道字典数组
        $dictionary = Lib::loadFile('Config/Dictionary.php');
        //引入银行字典
        $bank = Lib::getBankConfig(-1);
        $this->assign("data", $data);
        $this->assign("channel", $dictionary['channel']);
        $this->assign("bindcardStatus", $dictionary['bindcardStatus']);
        $this->assign("cardType", $dictionary['cardType']);
        $this->assign("bank", $bank);
        $this->view();
    }
    /**
     *
     * @name 绑卡记录删除
     */
    public function del($id = 0){
        $del = DBQ::del('bind_card', [
            'id' => $id
        ]);
        if ($del) {
            Dwz::success(Lib::getUrl($this->M()->modelName), $this->M()->modelName);
        } else {
            Dwz::err();
        }
    }
}