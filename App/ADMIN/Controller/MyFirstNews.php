<?php
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;
use Core\Extend\Dwz;
use \App\ADMIN\Model\MyFirstNews as col;
class MyFirstNews extends Controller {
    /**
     *
     * @name 查询
     */
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
        foreach($data['list'] as $k=>$v){
            $data['list'][$k]['content']=mb_substr(strip_tags($v['content']),0,30).'.....';
        }
        $this->assign ( "data", $data );
        $this->view ();
    }

    /**
     *
     * @name 添加
     */
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

    /**
     *
     * @name 删除
     * @param number $id
     */
    public function del($id = 0) {
        if ($this->M ()->del( $id )) {
            Dwz::success ( Lib::getUrl ( $this->M ()->modelName), $this->M()->modelName );
        } else {
            Dwz::err ();
        }
    }

    /**
     *
     * @name 批量删除
     */
    public function delAll() {
        $ids = explode ( ',', Lib::post ( 'ids' ) );
        if ($this->M ()->delAll ( $ids )) {
            Dwz::success ( Lib::getUrl ( $this->M ()->modelName ), $this->M ()->modelName );
        } else {
            Dwz::err ();
        }
    }

    /**
     *
     * @name 编辑
     * @param number $id
     * @param $act
     */
    public function edit($id = 0, $act = null) {


    	




        if ($act == 'edit' && ! empty ( $id )) {
            
            if ($this->M ()->edit ($id)) {
                Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
            } else {
                Dwz::err ();
            }
        }

        $list = $this->M ()->db->get ( "my_first_news", "*", [
            'id' => $id
        ] );
        // $pid = $list['pid'];
        //$articleCategoryModel = new \App\ADMIN\Model\ArticleCategory();
        //$categoryOptionStr = $articleCategoryModel->getOptionList ( 0, '', $list ['category_id'] );
       // $this->assign ( 'categoryOptionStr', $categoryOptionStr );
        $this->assign ( "list", $list );
        $this->view ();
    }
}