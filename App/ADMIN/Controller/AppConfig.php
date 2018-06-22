<?php
namespace App\ADMIN\Controller;
use Core\Base\Controller;
class AppConfig extends Controller{
    /**
     * @name App设置
     */
    public function index() {
		$list = $this->M()->getList();
		$this->assign('list', $list);
		$this->view ();
	}
}