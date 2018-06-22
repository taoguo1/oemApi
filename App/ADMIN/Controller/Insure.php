<?php
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;
use Core\Extend\Dwz;
use \App\ADMIN\Model\Insure as col;
class Insure extends Controller {

    public function index(){
        $title = Lib::request('title' );
        $carousel = new col();
        $condition = null;
        ($title) ? $condition ['AND'] ['title[~]'] = $title : null;
        $condition ['ORDER'] = [
            'id' => 'DESC'
        ];
        $pageArr = Lib::setPagePars ();
        if ($pageArr ['orderField']) {
            $columns ['ORDER'] = [
                $pageArr ['orderField'] => strtoupper($pageArr ['orderDirection'] )
            ];

        }
        $data = $carousel->getList ($pageArr,$condition );
        $this->assign ( "data", $data );
        $this->view ();
    }

    public function add($act=null){     
        if ($act == 'add') {
			$insertId=$this->M()->add();
			if ($insertId) {
				Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
			} else {
				Dwz::err ();
			}
		}
        $this->view ();
    }

    public function del($id = 0){
        if ($this->M ()->del( $id )){
            Dwz::success ( Lib::getUrl ( $this->M ()->modelName), $this->M()->modelName );
        } else {
            Dwz::err ();
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

    public function edit($id = 0, $act = null) {
        if ($act == 'edit' && ! empty ( $id )) {
            if ($this->M ()->edit ($id)) {
                Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
            } else {
                Dwz::err ();
            }
        }
        $list = $this->M ()->db->get ( "insure", "*", [
            'id' => $id
        ] );
        $this->assign ( "list", $list );
        $this->view ();
    }
}