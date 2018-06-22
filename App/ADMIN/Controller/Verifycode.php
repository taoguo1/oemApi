<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/27
 * Time: 11:56
 */

namespace App\ADMIN\Controller;

use Core\DB\DBQ;
use Core\Lib;
use Core\Extend\Dwz;
use Core\Base\Controller;

class Verifycode extends Controller
{

    /**
     *
     * @name 短信发送查询
     */
    public function index()
    {
        $code = Lib::request ( 'code' );
        $mobile = Lib::request ( 'mobile' );
        $status = Lib::request ( 'status' );
        $start_date = Lib::request ( 'start_date' );
        $end_date = Lib::request ( 'end_date' );
        $condition = null;
        ($code) ? $condition ['AND'] ['code'] = $code : null;
        ($mobile) ? $condition ['AND'] ['mobile'] = $mobile : null;
        ($status) ? $condition ['AND'] ['status'] = $status : null;
        ($start_date) ? $condition ['AND'] ['create_time[>=]'] = strtotime($start_date) : null;
        ($end_date) ? $condition ['AND'] ['create_time[<=]'] = strtotime($end_date) : null;
        $condition ['ORDER'] = [
            'id' => 'ASC'
        ];

        $pageArr = Lib::setPagePars ();
        if ($pageArr ['orderField']) {
            $columns ['ORDER'] = [
                $pageArr ['orderField'] => strtoupper ( $pageArr ['orderDirection'] )
            ];
        }
        $data = $this->M()->getList ( $pageArr, $condition );

        $this->assign ( "data", $data );
        $this->view();
    }

    /**
     *
     * @name 短信发送添加
     */
    public function add($act = null)
    {
        if ($act == 'add') {
            $data = [
                'code' => Lib::post ( 'code' ),
                'mobile' => Lib::post ( 'mobile' ),
                'status' => Lib::post ( 'status' ),
                'create_time' => Lib::getMs(),
            ];
            $insertId = $this->M()->add ($data);
            if ($insertId) {
                Dwz::successDialog ( $this->M()->modelName, '', 'closeCurrent' );
            } else {
                Dwz::err ();
            }
        }
        $id = Lib::post ( 'id' );
        $list = DBQ::getAll('verifycode', '*', [
            'id' => $id]);
        $this->assign('list',$list);
        $this->view ();
    }



    /**
     *
     * @name 短信发送编辑
     */
    public function edit($id = 0, $act = null)
    {
        if ($act == 'edit' && ! empty ( $id )) {
            $data = [
                'code' => Lib::post ( 'code' ),
                'mobile' => Lib::post ( 'mobile' ),
                'status' => Lib::post ( 'status' ),
                'create_time' => Lib::getMs(),
            ];
            if ( $upd = DBQ::upd('verifycode',$data,['id'=>$id])) {
                Dwz::successDialog ( $this->M()->modelName, '', 'closeCurrent' );
            } else {
                Dwz::err();
            }
        }
        $list = DBQ::getRow('verifycode', '*', [
            'id' => $id
        ]);
        $this->assign('list', $list);
        $this->view();
    }

    /**
     *
     * @name 短信发送删除
     */
    public function del($id = 0)
    {
        $del = DBQ::del('verifycode', [
            'id' => $id
        ]);
        if ($del) {
            Dwz::success(Lib::getUrl($this->M()->modelName), $this->M()->modelName);
        } else {
            Dwz::err();
        }
    }

    public function delAll() {
        $ids = explode ( ',', Lib::post ( 'ids' ) );
        if ($this->M ()->delAll ( $ids )) {
            Dwz::success ( Lib::getUrl ( $this->M ()->modelName ), $this->M ()->modelName );
        } else {
            Dwz::err ();
        }
    }
}