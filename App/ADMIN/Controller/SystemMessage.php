<?php
namespace App\ADMIN\Controller;
use Core\Base\Controller;
use Core\DB\DBQ;
use Core\Lib;
use Core\Extend\Dwz;
use Core\Extend\Session;
class SystemMessage extends Controller {
    public function index()
    {
    	$dictionaryData     = Lib::loadFile('Config/Dictionary.php');
        $title = Lib::request('title'); 
        $user_type = Lib::request('user_type');
        $type = Lib::request('type');  
        $read_unread = Lib::request('read_unread');      
        $condition = null;
        ($title) ? $condition['AND']['M.title'] = $title : null;
        ($user_type) ? $condition['AND']['M.user_type'] = $user_type : $condition['AND']['M.user_type'] =1;
        ($type) ? $condition['AND']['M.type'] = $type : null;
        ($read_unread) ? $condition['AND']['M.read_unread'] = $read_unread : null;
        $condition ['ORDER'] = [
            'M.id' => 'ASC'
        ];
        $pageArr = Lib::setPagePars();
        if ($pageArr['orderField']) {
            $columns['ORDER'] = [
                $pageArr['orderField'] => strtoupper($pageArr['orderDirection'])
            ];
        }
        $data = $this->M()->getList($pageArr, $condition);       
        $this->assign('user_type',$dictionaryData['user_type']);      
        $this->assign('type',$dictionaryData['type']);
        $this->assign('read_unread',$dictionaryData['read_unread']);
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
        $list = DBQ::getRow('system_message (A)', [
            '[>]user (B)' => [
                'A.uid' => 'id'
            ]
        ], [
            'A.id',
            'A.uid',
            'A.user_type',
            'A.status',
            'A.type',
            'A.read_unread',
            'A.title',
            'A.describe',
            'A.content',
            'A.create_time',
            'B.real_name',
        ],[
            'A.id' => $id
        ]);
        $this->assign('list', $list);
        $this->view();

    }
}