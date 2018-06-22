<?php
namespace App\ADMIN\Controller;
use Core\Base\Controller;
use Core\DB\DBQ;
use Core\Lib;
use Core\Extend\Dwz;
use Core\Extend\Session;
class SystemMessageTP extends Controller {
    public function index()
    {
    	$dictionaryData     = Lib::loadFile('Config/Dictionary.php');
        $title = Lib::request('title'); 
        $message_type = Lib::request('message_type');  
         
        $condition = null;
        ($title) ? $condition['AND']['title'] = $title : null;   
      
        ($message_type) ? $condition['AND']['message_type'] = $message_type : null;  
        
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
            
        $this->assign('message_type',$dictionaryData['message_type']);
       
        $this->assign("data", $data);
        $this->view();
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

    public function del($id = 0) {
        if ($this->M ()->del( $id )) {
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
        $list = $this->M ()->db->get ( "system_messagetp", "*", [
            'id' => $id
        ] );
        $this->assign ( "list", $list );
        $this->view ();
    }
}