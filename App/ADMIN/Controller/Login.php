<?php
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;
use Core\Extend\Session;

class Login extends Controller{
 public function index(){
        $session = new Session();       
        if (Lib::request('appid')==$session->get(Lib::request('appid'))) {
            header('location:' . Lib::getUrl('index'));
        }
        $this->assign('data', $this->M()->getLoginInfo());
        $this->view();
    }

    public function loginBox()
    {
        $this->assign('data', $this->M()
            ->getLoginInfo());
        $this->view();
    }

    public function login()
    {

        $this->M()->login();
    }

    public function logout()
    {
        $this->M()->logout();
    }
}