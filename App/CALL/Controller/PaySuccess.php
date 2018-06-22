<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 2018/3/15
 * Time: 9:34
 */

namespace App\CALL\Controller;


use Core\Base\Controller;

class PaySuccess extends Controller
{
    public function frame($pars=null){
        //print_r($_REQUEST);
        $this->assign('pars',$pars);
        $this->view();
    }
}