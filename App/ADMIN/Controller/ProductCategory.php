<?php
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;
use Core\Extend\Dwz;
class ProductCategory extends Controller {
	/**
	 *
	 * @name 查询
	 */
	public function index() {
		$listStr = $this->M ()->getList ( 0, '' );
		$this->assign ( "listStr", $listStr );
		$this->view ();
	}
	/**
	 *
	 * @name 添加
	 * @param $act        	
	 */
	public function add($act = null) {
		if ($act == 'add') {
			$insertId = $this->M ()->add ();
			if ($insertId) {
				Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
			} else {
				Dwz::err ();
			}
		}
		
		$listOptionStr = $this->M ()->getOptionList ( 0, '' );
		$this->assign ( "listOptionStr", $listOptionStr );
		$this->view ();
	}
	/**
	 *
	 * @name 删除
	 * @param number $id        	
	 */
	public function del($id = 0) {
		if ($this->M ()->del ( $id )) {
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
			if ($this->M ()->edit ( $id )) {
			    Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
			} else {
				Dwz::err ();
			}
		}
		
		$list = $this->M ()->db->get ( "product_category", "*", [
				'id' => $id 
		] );
		$listOptionStr = $this->M ()->getOptionList ( 0, '', $list ['pid'] );
		$this->assign ( "listOptionStr", $listOptionStr );
		$this->assign ( "list", $list );
		$this->view ();
	}
}